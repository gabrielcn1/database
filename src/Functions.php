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
use InvalidArgumentException;

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
        Closure | bool | float | int | string | null $value
    ) : float | int | string {
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

    public function ascii(
        Closure | bool | float | int | string | null $value
    ) : Closure {
        $value = $this->renderValue($value);
        return static fn () : string => 'ASCII(' . $value . ')';
    }

    public function bitLength(
        Closure | bool | float | int | string | null $value
    ) : Closure {
        $value = $this->renderValue($value);
        return static fn () : string => 'BIT_LENGTH(' . $value . ')';
    }

    public function cast(
        Closure | bool | float | int | string | null $value,
        string $type
    ) : Closure {
        $typeUpper = \strtoupper($type);
        if ( ! \in_array($typeUpper, [
            'BINARY',
            'CHAR',
            'DATE',
            'DATETIME',
            'DOUBLE',
            'FLOAT',
            'INTEGER',
            'UNSIGNED INTEGER',
            'TIME',
            'VARCHAR',
        ], true)) {
            throw new InvalidArgumentException('Invalid CAST value: ' . $type);
        }
        $value = $this->renderValue($value);
        return static fn () : string => 'CAST(' . $value . ' AS ' . $typeUpper . ')';
    }

    public function chr(
        Closure | bool | float | int | string | null $value
    ) : Closure {
        $value = $this->renderValue($value);
        return static fn () : string => 'CHR(' . $value . ')';
    }

    public function concat(
        Closure | bool | float | int | string | null $value,
        Closure | bool | float | int | string | null ...$values
    ) : Closure {
        $values = $this->implodeValues([$value, ...$values]);
        return static fn () : string => 'CONCAT(' . $values . ')';
    }

    public function elt(
        Closure | bool | float | int | string | null $value,
        Closure | bool | float | int | string | null ...$values
    ) : Closure {
        $values = $this->implodeValues([$value, ...$values]);
        return static fn () : string => 'ELT(' . $values . ')';
    }

    public function hex(
        Closure | bool | float | int | string | null $value
    ) : Closure {
        $value = $this->renderValue($value);
        return static fn () : string => 'HEX(' . $value . ')';
    }

    public function instr(
        Closure | bool | float | int | string | null $value,
        Closure | bool | float | int | string | null $sub
    ) : Closure {
        $values = $this->implodeValues([$value, $sub]);
        return static fn () : string => 'INSTR(' . $values . ')';
    }

    public function left(
        Closure | bool | float | int | string | null $value,
        Closure | bool | float | int | string | null $length
    ) : Closure {
        $values = $this->implodeValues([$value, $length]);
        return static fn () : string => 'LEFT(' . $values . ')';
    }

    public function lower(
        Closure | bool | float | int | string | null $value
    ) : Closure {
        $value = $this->renderValue($value);
        return static fn () : string => 'LOWER(' . $value . ')';
    }

    public function now(int $precision = null) : Closure
    {
        return static fn () : string => 'NOW(' . $precision . ')';
    }

    public function ord(
        Closure | bool | float | int | string | null $value
    ) : Closure {
        $value = $this->renderValue($value);
        return static fn () : string => 'ORD(' . $value . ')';
    }

    public function repeat(
        Closure | bool | float | int | string | null $value,
        Closure | bool | float | int | string | null $count
    ) : Closure {
        $values = $this->implodeValues([$value, $count]);
        return static fn () : string => 'REPEAT(' . $values . ')';
    }

    public function replace(
        Closure | bool | float | int | string | null $value,
        Closure | bool | float | int | string | null $from,
        Closure | bool | float | int | string | null $to
    ) : Closure {
        $values = $this->implodeValues([$value, $from, $to]);
        return static fn () : string => 'REPLACE(' . $values . ')';
    }

    public function reverse(
        Closure | bool | float | int | string | null $value
    ) : Closure {
        $value = $this->renderValue($value);
        return static fn () : string => 'REVERSE(' . $value . ')';
    }

    public function right(
        Closure | bool | float | int | string | null $value,
        Closure | bool | float | int | string | null $length
    ) : Closure {
        $values = $this->implodeValues([$value, $length]);
        return static fn () : string => 'RIGHT(' . $values . ')';
    }

    public function rpad(
        Closure | bool | float | int | string | null $value,
        Closure | bool | float | int | string | null $length,
        Closure | bool | float | int | string | null $pad = ' '
    ) : Closure {
        $values = $this->implodeValues([$value, $length, $pad]);
        return static fn () : string => 'RPAD(' . $values . ')';
    }

    public function rtrim(
        Closure | bool | float | int | string | null $value
    ) : Closure {
        $value = $this->renderValue($value);
        return static fn () : string => 'RTRIM(' . $value . ')';
    }

    public function soundex(
        Closure | bool | float | int | string | null $value
    ) : Closure {
        $value = $this->renderValue($value);
        return static fn () : string => 'SOUNDEX(' . $value . ')';
    }

    public function space(
        Closure | bool | float | int | string | null $value
    ) : Closure {
        $value = $this->renderValue($value);
        return static fn () : string => 'SPACE(' . $value . ')';
    }

    public function strcmp(
        Closure | bool | float | int | string | null $value1,
        Closure | bool | float | int | string | null $value2
    ) : Closure {
        $values = $this->implodeValues([$value1, $value2]);
        return static fn () : string => 'STRCMP(' . $values . ')';
    }

    public function toBase64(
        Closure | bool | float | int | string | null $value
    ) : Closure {
        $value = $this->renderValue($value);
        return static fn () : string => 'TO_BASE64(' . $value . ')';
    }

    public function trim(
        Closure | bool | float | int | string | null $value,
        Closure | bool | float | int | string | null $toRemove = ' ',
        string $specifier = 'BOTH'
    ) : Closure {
        $spec = \strtoupper($specifier);
        if ( ! \in_array($spec, [
            'BOTH',
            'LEADING',
            'TRAILING',
        ], true)) {
            throw new InvalidArgumentException('Invalid TRIM specifier: ' . $specifier);
        }
        $trim = '';
        if ($spec !== 'BOTH') {
            $trim = $spec . ' ';
        }
        if ($trim !== '' || $toRemove !== ' ') {
            $trim .= $this->renderValue($toRemove) . ' FROM ';
        }
        $trim .= $this->renderValue($value);
        return static fn () : string => 'TRIM(' . $trim . ')';
    }

    public function unhex(
        Closure | bool | float | int | string | null $value
    ) : Closure {
        $value = $this->renderValue($value);
        return static fn () : string => 'UNHEX(' . $value . ')';
    }

    public function upper(
        Closure | bool | float | int | string | null $value
    ) : Closure {
        $value = $this->renderValue($value);
        return static fn () : string => 'UPPER(' . $value . ')';
    }
}
