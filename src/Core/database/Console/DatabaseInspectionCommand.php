<?php

namespace NINA\Database\Console;

use Illuminate\Console\Command;
use NINA\Database\ConnectionInterface;
use NINA\Database\MariaDbConnection;
use NINA\Database\MySqlConnection;
use NINA\Database\PostgresConnection;
use NINA\Database\SQLiteConnection;
use NINA\Database\SqlServerConnection;
use Illuminate\Support\Arr;

abstract class DatabaseInspectionCommand extends Command
{
    /**
     * Get a human-readable name for the given connection.
     *
     * @param  \NINA\Database\ConnectionInterface  $connection
     * @param  string  $database
     * @return string
     */
    protected function getConnectionName(ConnectionInterface $connection, $database)
    {
        return match (true) {
            $connection instanceof MySqlConnection && $connection->isMaria() => 'MariaDB',
            $connection instanceof MySqlConnection => 'MySQL',
            $connection instanceof MariaDbConnection => 'MariaDB',
            $connection instanceof PostgresConnection => 'PostgreSQL',
            $connection instanceof SQLiteConnection => 'SQLite',
            $connection instanceof SqlServerConnection => 'SQL Server',
            default => $database,
        };
    }

    /**
     * Get the number of open connections for a database.
     *
     * @param  \NINA\Database\ConnectionInterface  $connection
     * @return int|null
     */
    protected function getConnectionCount(ConnectionInterface $connection)
    {
        $result = match (true) {
            $connection instanceof MySqlConnection => $connection->selectOne('show status where variable_name = "threads_connected"'),
            $connection instanceof PostgresConnection => $connection->selectOne('select count(*) as "Value" from pg_stat_activity'),
            $connection instanceof SqlServerConnection => $connection->selectOne('select count(*) Value from sys.dm_exec_sessions where status = ?', ['running']),
            default => null,
        };

        if (! $result) {
            return null;
        }

        return Arr::wrap((array) $result)['Value'];
    }

    /**
     * Get the connection configuration details for the given connection.
     *
     * @param  string  $database
     * @return array
     */
    protected function getConfigFromDatabase($database)
    {
        $database ??= config('database.default');

        return Arr::except(config('database.connections.'.$database), ['password']);
    }

    /**
     * Remove the table prefix from a table name, if it exists.
     *
     * @param  \NINA\Database\ConnectionInterface  $connection
     * @param  string  $table
     * @return string
     */
    protected function withoutTablePrefix(ConnectionInterface $connection, string $table)
    {
        $prefix = $connection->getTablePrefix();

        return str_starts_with($table, $prefix)
            ? substr($table, strlen($prefix))
            : $table;
    }
}
