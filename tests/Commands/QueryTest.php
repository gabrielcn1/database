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

use Framework\CLI\Streams\Stdout;
use Framework\Database\Commands\Query;

/**
 * @runTestsInSeparateProcesses
 */
final class QueryTest extends CommandTestCase
{
    protected string $commandClass = Query::class;

    public function testRun() : void
    {
        Stdout::init();
        $this->console->exec('query "describe `app-test`.`Users`"');
        $contents = Stdout::getContents();
        self::assertStringContainsString('| Field ', $contents);
        self::assertStringContainsString('| Type ', $contents);
        self::assertStringContainsString('| Null ', $contents);
        self::assertStringContainsString('| Key ', $contents);
        self::assertStringContainsString('| Default ', $contents);
        self::assertStringContainsString('| Extra ', $contents);
        self::assertStringContainsString('| id ', $contents);
        self::assertStringContainsString('| int(11) ', $contents);
        self::assertStringContainsString('| email ', $contents);
        self::assertStringContainsString('| varchar(255) ', $contents);
        self::assertStringContainsString('Total: ', $contents);
    }

    public function testNoResults() : void
    {
        Stdout::init();
        $this->console->exec('query "select * from `app-test`.`Users`"');
        $contents = Stdout::getContents();
        self::assertStringContainsString('No results.', $contents);
    }
}
