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
use Framework\Debug\Debugger;

/**
 * Class ShowSchema.
 *
 * @package database
 */
class ShowSchema extends Command
{
    protected string $description = 'Shows database schema information.';

    public function run() : void
    {
        $schema = $this->console->getArgument(0);
        if (empty($schema)) {
            $schema = CLI::prompt('Enter a schema name');
            CLI::newLine();
        }
        $show = $this->getDatabase()->query(
            'SHOW DATABASES LIKE ' . $this->getDatabase()->quote($schema)
        )->fetchArray();
        if (empty($show)) {
            CLI::beep();
            CLI::error('Schema not found: ' . $schema);
            return;
        }
        $list = $this->getTableList($schema);
        if ($list) {
            CLI::write(
                CLI::style('Schema: ', 'white') . CLI::style($schema, 'yellow')
            );
            CLI::table($list, \array_keys($list[0]));
            CLI::write('Total: ' . \count($list));

            return;
        }
        CLI::write('No tables.');
    }

    /**
     * @param string $schema
     *
     * @return array<int,array<string,string>>
     */
    public function getTableList(string $schema) : array
    {
        $sql = 'SELECT TABLE_NAME, ENGINE, TABLE_COLLATION, DATA_LENGTH, INDEX_LENGTH, DATA_FREE, AUTO_INCREMENT, TABLE_ROWS, TABLE_COMMENT
FROM information_schema.TABLES WHERE TABLE_SCHEMA = ' . $this->getDatabase()
            ->quote($schema) . ' ORDER BY TABLE_NAME';
        $tables = $this->getDatabase()->query($sql)->fetchArrayAll();
        $list = [];
        foreach ($tables as $table) {
            $list[] = [
                'Table' => $table['TABLE_NAME'],
                'Engine' => $table['ENGINE'],
                'Collation' => $table['TABLE_COLLATION'],
                'Data Length' => Debugger::convertSize($table['DATA_LENGTH']),
                'Index Length' => Debugger::convertSize($table['INDEX_LENGTH']),
                'Data Free' => Debugger::convertSize($table['DATA_FREE']),
                'Auto Increment' => $table['AUTO_INCREMENT'],
                'Rows' => $table['TABLE_ROWS'],
                'Comment' => $table['TABLE_COMMENT'],
            ];
        }
        return $list;
    }
}
