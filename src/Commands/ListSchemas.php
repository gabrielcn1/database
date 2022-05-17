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
 * Class ListSchemas.
 *
 * @package database
 */
class ListSchemas extends Command
{
    protected string $description = 'Lists database schemas.';

    public function run() : void
    {
        $sql = 'SELECT `SCHEMA_NAME` AS "database",
`DEFAULT_COLLATION_NAME` AS "collation"
FROM `information_schema`.`SCHEMATA`
ORDER BY `SCHEMA_NAME`';
        $schemas = $this->getDatabase()->query($sql)->fetchArrayAll();
        if ( ! $schemas) {
            CLI::write('No database.');
            return;
        }
        $sql = 'SELECT `TABLE_SCHEMA` AS "database",
SUM(`DATA_LENGTH` + `INDEX_LENGTH`) AS "size",
COUNT(DISTINCT CONCAT(`TABLE_SCHEMA`, ".", `TABLE_NAME`)) AS "tables"
FROM `information_schema`.`TABLES`
GROUP BY `TABLE_SCHEMA`';
        $infos = $this->getDatabase()->query($sql)->fetchArrayAll();
        foreach ($schemas as &$schema) {
            $schema['size'] = $schema['tables'] = 0;
            foreach ($infos as $info) {
                if ($info['database'] === $schema['database']) {
                    $schema['tables'] = $info['tables'];
                    $schema['size'] = Debugger::convertSize((int) $info['size']);
                    break;
                }
            }
        }
        unset($schema);
        CLI::table($schemas, [
            'Schema',
            'Collation',
            'Tables',
            'Size',
        ]);
        CLI::write('Total: ' . \count($schemas));
    }
}
