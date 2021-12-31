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

    public function __invoke(mixed $value) : Closure
    {
        return $this->same($value);
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
        return static fn () : mixed => $value;
    }

    public function concat(string $value1, string $value2, string ...$values) : Closure
    {
        $values = [$value1, $value2, ...$values];
        foreach ($values as &$value) {
            $value = $this->renderValue($value);
        }
        unset($value);
        $values = \implode(', ', $values);
        return static fn () : string => 'CONCAT(' . $values . ')';
    }

    public function hex(Closure | int | string | null $value) : Closure
    {
        $value = $this->renderValue($value);
        return static fn () : string => 'HEX(' . $value . ')';
    }

    public function now() : Closure
    {
        return static fn () : string => 'NOW()';
    }
}
