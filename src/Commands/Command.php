<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Database Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\Database\Commands;

use Framework\Database\Database;

/**
 * Class Command.
 *
 * @package database
 */
abstract class Command extends \Framework\CLI\Command
{
    protected Database $database;

    public function setDatabase(Database $database) : static
    {
        $this->database = $database;
        return $this;
    }

    public function getDatabase() : Database
    {
        return $this->database;
    }
}
