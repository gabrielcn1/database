<?php declare(strict_types=1);
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
        Closure | float | bool | int | string | null $value
    ) : string | float | int {
        return $value instanceof Closure
            ? '(' . $value($this->database) . ')'
            : $this->database->quote($value);
    }

    /**
     * @param array<bool|Closure|float|int|string|null> $values
     *
     * @return string
     */
    protected function implodeValues(array $values) : string
    {
        foreach ($values as &$value) {
            $value = $this->renderValue($value);
        }
        return \implode(', ', $values);
    }

    public function same(mixed $value) : Closure
    {
        return static fn () : mixed => $value;
    }

    public function concat(
        Closure | float | bool | int | string | null $value,
        Closure | float | bool | int | string | null ...$values
    ) : Closure {
        $values = $this->implodeValues([$value, ...$values]);
        return static fn () : string => 'CONCAT(' . $values . ')';
    }

    public function hex(Closure | float | bool | int | string | null $value) : Closure
    {
        $value = $this->renderValue($value);
        return static fn () : string => 'HEX(' . $value . ')';
    }

    public function now(int $precision = null) : Closure
    {
        return static fn () : string => 'NOW(' . $precision . ')';
    }
}
