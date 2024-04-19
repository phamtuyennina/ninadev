<?php

namespace NINA\Database;

use Exception;
use NINA\Database\Query\Grammars\MySqlGrammar as QueryGrammar;
use NINA\Database\Query\Processors\MySqlProcessor;
use NINA\Database\Schema\Grammars\MySqlGrammar as SchemaGrammar;
use NINA\Database\Schema\MySqlBuilder;
use NINA\Database\Schema\MySqlSchemaState;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use PDO;

class MySqlConnection extends Connection
{

    /**
     * Escape a binary value for safe SQL embedding.
     *
     * @param  string  $value
     * @return string
     */
    protected function escapeBinary($value)
    {

        $hex = bin2hex($value);

        return "x'{$hex}'";
    }

    /**
     * Determine if the given database exception was caused by a unique constraint violation.
     *
     * @param  \Exception  $exception
     * @return bool
     */
    protected function isUniqueConstraintError(Exception $exception): bool
    {
        return boolval(preg_match('#Integrity constraint violation: 1062#i', $exception->getMessage()));
    }

    /**
     * Determine if the connected database is a MariaDB database.
     *
     * @return bool
     */
    public function isMaria(): bool
    {
        return str_contains($this->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION), 'MariaDB');
    }

    /**
     * Get the server version for the connection.
     *
     * @return string
     */
    public function getServerVersion(): string
    {
        return str_contains($version = parent::getServerVersion(), 'MariaDB')
            ? Str::between($version, '5.5.5-', '-MariaDB')
            : $version;
    }

    /**
     * Get the default query grammar instance.
     *
     * @return Grammar
     */
    protected function getDefaultQueryGrammar(): Grammar
    {
        ($grammar = new QueryGrammar)->setConnection($this);

        return $this->withTablePrefix($grammar);
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \NINA\Database\Schema\MySqlBuilder
     */
    public function getSchemaBuilder(): MySqlBuilder
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new MySqlBuilder($this);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return Grammar
     */
    protected function getDefaultSchemaGrammar(): Grammar
    {
        ($grammar = new SchemaGrammar)->setConnection($this);

        return $this->withTablePrefix($grammar);
    }

    /**
     * Get the schema state for the connection.
     *
     * @param  \Illuminate\Filesystem\Filesystem|null  $files
     * @param  callable|null  $processFactory
     * @return \NINA\Database\Schema\MySqlSchemaState
     */
    public function getSchemaState(Filesystem $files = null, callable $processFactory = null): MySqlSchemaState
    {
        return new MySqlSchemaState($this, $files, $processFactory);
    }

    /**
     * Get the default post processor instance.
     *
     * @return \NINA\Database\Query\Processors\MySqlProcessor
     */
    protected function getDefaultPostProcessor(): MySqlProcessor
    {
        return new MySqlProcessor;
    }
}
