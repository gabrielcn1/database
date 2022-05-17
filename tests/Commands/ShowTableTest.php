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
use Framework\Database\Commands\ShowTable;

/**
 * @runTestsInSeparateProcesses
 */
final class ShowTableTest extends CommandTestCase
{
    protected string $commandClass = ShowTable::class;

    public function testRun() : void
    {
        Stdout::init();
        $this->console->exec('showtable app-test.Posts');
        $contents = Stdout::getContents();
        self::assertStringContainsString('| Column ', $contents);
        self::assertStringContainsString('| Type ', $contents);
        self::assertStringContainsString('| Nullable ', $contents);
        self::assertStringContainsString('Indexes', $contents);
        self::assertStringContainsString('PRIMARY', $contents);
        self::assertStringContainsString('Foreign Keys', $contents);
        self::assertStringContainsString('Source', $contents);
        self::assertStringContainsString('Target', $contents);
        self::assertStringContainsString('Users(id)', $contents);
    }
}
