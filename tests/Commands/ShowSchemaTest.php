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
use Framework\Database\Commands\ShowSchema;

/**
 * @runTestsInSeparateProcesses
 */
final class ShowSchemaTest extends CommandTestCase
{
    protected string $commandClass = ShowSchema::class;

    public function testRun() : void
    {
        Stdout::init();
        $this->console->exec('showschema app-test');
        $contents = Stdout::getContents();
        self::assertStringContainsString('app-test', $contents);
        self::assertStringContainsString('| Table ', $contents);
        self::assertStringContainsString('| Engine ', $contents);
        self::assertStringContainsString('| Collation ', $contents);
        self::assertStringContainsString('| Posts ', $contents);
        self::assertStringContainsString('| Users ', $contents);
        self::assertStringContainsString('Total: 2', $contents);
    }
}
