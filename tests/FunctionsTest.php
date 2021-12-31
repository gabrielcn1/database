<?php
/*
 * This file is part of Aplus Framework Database Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\Database;

use Framework\Database\Database;
use Framework\Database\Functions;

final class FunctionsTest extends TestCase
{
    protected Functions $fs;

    protected function setUp() : void
    {
        parent::setUp();
        $this->fs = static::$database->functions;
    }

    public function testInvoke() : void
    {
        $fs = $this->fs;
        self::assertSame('Foo', $fs('Foo')());
    }

    public function testValues() : void
    {
        $fs = new class(static::$database) extends Functions {
            public function implodeValues(array $values) : string
            {
                return parent::implodeValues($values);
            }
        };
        self::assertSame(
            "(Select '\\';hahah'), 1, 1.618, 'boo', NULL, TRUE",
            $fs->implodeValues([
                static fn (Database $db) => 'Select ' . $db->quote("';hahah"),
                1,
                1.618,
                'boo',
                null,
                true,
            ])
        );
    }

    public function testSame() : void
    {
        self::assertSame('Foo', $this->fs->same('Foo')());
    }

    public function testConcat() : void
    {
        self::assertSame("CONCAT('foo', 123)", $this->fs->concat('foo', 123)());
    }

    public function testHex() : void
    {
        self::assertSame("HEX('foo')", $this->fs->hex('foo')());
    }

    public function testNow() : void
    {
        self::assertSame('NOW()', $this->fs->now()());
        self::assertSame('NOW(3)', $this->fs->now(3)());
    }
}
