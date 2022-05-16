<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Database Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\Database\Commands;

use Framework\CLI\CLI;

/**
 * Class ShowTable.
 *
 * @package database
 */
class ShowTable extends Command
{
    protected string $description = 'Shows a database table structure.';

    public function run() : void
    {
        $table = $this->console->getArgument(0);
        if (empty($table)) {
            $table = CLI::prompt('Enter a table name');
            CLI::newLine();
        }
        if (\str_contains($table, '.')) {
            [$schema, $table] = \explode('.', $table, 2);
            $this->getDatabase()->use($schema);
        }
        $show = $this->getDatabase()->query(
            'SHOW TABLES LIKE ' . $this->getDatabase()->quote($table)
        )->fetchArray();
        if (empty($show)) {
            CLI::beep();
            CLI::error('Table not exist: ' . $table);
            return;
        }
        $fields = $this->getFields($table);
        CLI::write(
            CLI::style('Table: ', 'white')
            . CLI::style($table, 'yellow')
        );
        CLI::table($fields, \array_keys($fields[0]));
        CLI::newLine();
        $indexes = $this->getIndexes($table);
        if ($indexes) {
            CLI::write('Indexes', 'white');
            CLI::table($indexes, \array_keys($indexes[0]));
            CLI::newLine();
        }
        $foreignKeys = $this->getForeignKeys($table);
        if ($foreignKeys) {
            CLI::write('Foreign Keys', 'white');
            CLI::table($foreignKeys, \array_keys($foreignKeys[0]));
            CLI::newLine();
        }
    }

    /**
     * @param string $table
     *
     * @return array<int,array<string,string>>
     */
    protected function getFields(string $table) : array
    {
        $show = $this->getDatabase()->query(
            'SHOW FULL COLUMNS FROM ' . $this->getDatabase()->protectIdentifier($table)
        )->fetchArrayAll();
        if ( ! $show) {
            return [];
        }
        $columns = [];
        foreach ($show as $row) {
            \preg_match(
                '~^([^( ]+)(?:\\((.+)\\))?( unsigned)?( zerofill)?$~',
                $row['Type'],
                $match
            );
            $columns[] = [
                'field' => $row['Field'],
                'full_type' => $row['Type'],
                'type' => $match[1] ?? null,
                'length' => $match[2] ?? null,
                'unsigned' => \ltrim(($match[3] ?? null) . ($match[4] ?? null)),
                'default' => ($row['Default'] !== '' || \preg_match(
                    '~char|set~',
                    $match[1]
                ) ? $row['Default'] : null),
                'null' => ($row['Null'] === 'YES'),
                'auto_increment' => ($row['Extra'] === 'auto_increment'),
                'on_update' => (\preg_match('~^on update (.+)~i', $row['Extra'], $match)
                    ? $match[1] : ''),
                'collation' => $row['Collation'],
                // @phpstan-ignore-next-line
                'privileges' => \array_flip(\preg_split('~, *~', $row['Privileges'])),
                'comment' => $row['Comment'],
                'primary' => ($row['Key'] === 'PRI'),
            ];
        }
        $cols = [];
        foreach ($columns as $col) {
            $cols[] = [
                'Column' => $col['field'] . ($col['primary']
                        ? ' PRIMARY' : ''),
                'Type' => $col['full_type']
                    . ($col['collation'] ? ' ' . $col['collation'] : '')
                    . ($col['auto_increment'] ? ' Auto Increment' : ''),
                'Nullable' => $col['null'] ? 'Yes' : 'No',
                'Default' => $col['default'],
                'Comment' => $col['comment'],
            ];
        }
        return $cols;
    }

    /**
     * @param string $table
     *
     * @return array<int,array<string,string>>
     */
    protected function getIndexes(string $table) : array
    {
        $indexes = $this->getDatabase()->query(
            'SHOW INDEX FROM ' . $this->getDatabase()->protectIdentifier($table)
        )->fetchArrayAll();
        $result = [];
        foreach ($this->makeKeys($indexes) as $key) {
            $result[] = [
                'Name' => $key->name,
                'Type' => $key->type,
                'Columns' => \implode(', ', $key->fields),
            ];
        }
        return $result;
    }

    /**
     * @param array<mixed> $indexes
     *
     * @return array<object>
     */
    protected function makeKeys(array $indexes) : array
    {
        foreach ($indexes as $index) {
            if (empty($keys[$index['Key_name']])) {
                $keys[$index['Key_name']] = new \stdClass();
                $keys[$index['Key_name']]->name = $index['Key_name'];
                $type = 'UNIQUE';
                if ($index['Key_name'] === 'PRIMARY') {
                    $type = 'PRIMARY';
                } elseif ($index['Index_type'] === 'FULLTEXT') {
                    $type = 'FULLTEXT';
                } elseif ($index['Non_unique']) {
                    $type = $index['Index_type'] === 'SPATIAL' ? 'SPATIAL' : 'INDEX';
                }
                $keys[$index['Key_name']]->type = $type;
            }
            $keys[$index['Key_name']]->fields[] = $index['Column_name'];
        }
        return $keys ?? [];
    }

    /**
     * @param string $table
     *
     * @return array<int,array<string,string>>
     */
    protected function getForeignKeys(string $table) : array
    {
        $show = $this->getDatabase()->query(
            'SHOW CREATE TABLE ' . $this->getDatabase()->protectIdentifier($table)
        )->fetchArray();
        if ( ! $show) {
            return [];
        }
        $create_table = $show['Create Table'];
        $on_actions = 'RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT';
        $pattern = '`(?:[^`]|``)+`';
        \preg_match_all(
            "~CONSTRAINT (${pattern}) FOREIGN KEY ?\\(((?:${pattern},? ?)+)\\) REFERENCES (${pattern})(?:\\.(${pattern}))? \\(((?:${pattern},? ?)+)\\)(?: ON DELETE (${on_actions}))?(?: ON UPDATE (${on_actions}))?~",
            $create_table, // @phpstan-ignore-line
            $matches,
            \PREG_SET_ORDER
        );
        $foreign_keys = [];
        foreach ($matches as $match) {
            \preg_match_all("~${pattern}~", $match[2], $source);
            \preg_match_all("~${pattern}~", $match[5], $target);
            $foreign_keys[] = [
                'index' => \str_replace('`', '', $match[1]),
                'source' => \str_replace('`', '', $source[0][0]),
                'database' => \str_replace('`', '', $match[4] !== '' ? $match[3] : $match[4]),
                'table' => \str_replace('`', '', $match[4] !== '' ? $match[4] : $match[3]),
                'field' => \str_replace('`', '', $target[0][0]),
                'on_delete' => ( ! empty($match[6]) ? $match[6] : 'RESTRICT'),
                'on_update' => ( ! empty($match[7]) ? $match[7] : 'RESTRICT'),
            ];
        }
        $fks = [];
        foreach ($foreign_keys as $fk) {
            $fks[] = [
                'Source' => $fk['source'],
                'Target' => ( ! empty($fk['database']) ? $fk['database'] . '.' : '')
                    . $fk['table'] . '(' . $fk['field'] . ')',
                'ON DELETE' => $fk['on_delete'],
                'ON UPDATE' => $fk['on_update'],
            ];
        }
        return $fks;
    }
}
