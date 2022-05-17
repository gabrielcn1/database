<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Database Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\Database\Commands;

use Framework\CLI\Console;
use Framework\Database\Commands\Command;
use Framework\Database\Definition\Table\TableDefinition;
use Tests\Database\TestCase;

abstract class CommandTestCase extends TestCase
{
    protected Command $command;
    /**
     * @var class-string<Command>
     */
    protected string $commandClass;
    protected Console $console;

    protected function setUp() : void
    {
        $this->console = new Console();
        $this->command = new $this->commandClass();
        $this->console->addCommand($this->command);
        $this->command->setDatabase(static::$database);
        $this->dropSchema();
        $this->createSchema();
    }

    protected function dropSchema() : void
    {
        static::$database->dropSchema('app-test')->ifExists()->run();
    }

    protected function createSchema() : void
    {
        static::$database->createSchema('app-test')->run();
        static::$database->use('app-test');
        static::$database->createTable('Users')
            ->definition(static function (TableDefinition $def) : void {
                $def->column('id')->int()->primaryKey();
                $def->column('email')->varchar(255)->uniqueKey();
                $def->column('name')->varchar(64);
            })->run();
        static::$database->createTable('Posts')
            ->definition(static function (TableDefinition $def) : void {
                $def->column('id')->int()->primaryKey();
                $def->column('title')->varchar(128);
                $def->column('contents')->mediumtext();
                $def->column('user_id')->int();
                $def->index()->fulltextKey('contents');
                $def->index()->foreignKey('user_id')->references('Users', 'id');
            })->run();
    }
}
