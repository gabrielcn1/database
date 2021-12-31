<?php
/*
 * This file is part of Aplus Framework Database Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\Database;

use Closure;

/**
 * Class Functions.
 *
 * @see https://mariadb.com/kb/en/built-in-functions/
 */
class Functions
{
    protected Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    protected function renderValue(
        Closure | int | string | float | null $value
    ) : string | float | int {
        return $value instanceof Closure
            ? '(' . $value($this->database) . ')'
            : $this->database->quote($value);
    }

    public function same(mixed $value) : Closure
    {
        return static function () use ($value) : mixed {
            return $value;
        };
    }

    public function concat(string $value1, string $value2, string ...$values) : Closure
    {
        $values = [$value1, $value2, ...$values];
        foreach ($values as &$value) {
            $value = $this->renderValue($value);
        }
        unset($value);
        $values = \implode(', ', $values);
        return static function () use ($values) : string {
            return 'CONCAT(' . $values . ')';
        };
    }

    public function hex(Closure | int | string | null $value) : Closure
    {
        $value = $this->renderValue($value);
        return static function () use ($value) : string {
            return 'HEX(' . $value . ')';
        };
    }

    public function now() : Closure
    {
        return static function () : string {
            return 'NOW()';
        };
    }
}
