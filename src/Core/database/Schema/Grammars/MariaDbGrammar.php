<?php

namespace NINA\Database\Schema\Grammars;

use NINA\Database\Connection;
use NINA\Database\Query\Expression;
use NINA\Database\Schema\Blueprint;
use NINA\Database\Schema\ColumnDefinition;
use Illuminate\Support\Fluent;

class MariaDbGrammar extends MySqlGrammar
{
    /**
     * Compile a rename column command.
     *
     * @param  \NINA\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @param  \NINA\Database\Connection  $connection
     * @return array|string
     */
    public function compileRenameColumn(Blueprint $blueprint, Fluent $command, Connection $connection)
    {
        if (version_compare($connection->getServerVersion(), '10.5.2', '<')) {
            $column = collect($connection->getSchemaBuilder()->getColumns($blueprint->getTable()))
                ->firstWhere('name', $command->from);

            $modifiers = $this->addModifiers($column['type'], $blueprint, new ColumnDefinition([
                'change' => true,
                'type' => match ($column['type_name']) {
                    'bigint' => 'bigInteger',
                    'int' => 'integer',
                    'mediumint' => 'mediumInteger',
                    'smallint' => 'smallInteger',
                    'tinyint' => 'tinyInteger',
                    default => $column['type_name'],
                },
                'nullable' => $column['nullable'],
                'default' => $column['default'] && str_starts_with(strtolower($column['default']), 'current_timestamp')
                    ? new Expression($column['default'])
                    : $column['default'],
                'autoIncrement' => $column['auto_increment'],
                'collation' => $column['collation'],
                'comment' => $column['comment'],
            ]));

            return sprintf('alter table %s change %s %s %s',
                $this->wrapTable($blueprint),
                $this->wrap($command->from),
                $this->wrap($command->to),
                $modifiers
            );
        }

        return parent::compileRenameColumn($blueprint, $command, $connection);
    }

    /**
     * Create the column definition for a uuid type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeUuid(Fluent $column)
    {
        return 'uuid';
    }

    /**
     * Create the column definition for a spatial Geometry type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeGeometry(Fluent $column)
    {
        $subtype = $column->subtype ? strtolower($column->subtype) : null;

        if (! in_array($subtype, ['point', 'linestring', 'polygon', 'geometrycollection', 'multipoint', 'multilinestring', 'multipolygon'])) {
            $subtype = null;
        }

        return sprintf('%s%s',
            $subtype ?? 'geometry',
            $column->srid ? ' ref_system_id='.$column->srid : ''
        );
    }
}
