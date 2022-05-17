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
use Framework\Database\Commands\ListSchemas;

/**
 * @runTestsInSeparateProcesses
 */
final class ListSchemasTest extends CommandTestCase
{
    protected string $commandClass = ListSchemas::class;

    public function testRun() : void
    {
        Stdout::init();
        $this->console->exec('listschemas');
        $contents = Stdout::getContents();
        self::assertStringContainsString('| Schema ', $contents);
        self::assertStringContainsString('| Collation ', $contents);
        self::assertStringContainsString('| Tables ', $contents);
        self::assertStringContainsString('| Size ', $contents);
        self::assertStringContainsString('| app-test ', $contents);
        self::assertStringContainsString('| framework-tests ', $contents);
        self::assertStringContainsString('| information_schema ', $contents);
        self::assertStringContainsString('| mysql ', $contents);
        self::assertStringContainsString('| performance_schema ', $contents);
        self::assertStringContainsString('| sys ', $contents);
        self::assertStringContainsString('| utf8mb4_general_ci ', $contents);
        self::assertStringContainsString('Total: ', $contents);
    }
}
