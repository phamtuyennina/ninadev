<?php

namespace NINA\Database\Eloquent\Relations;

use Illuminate\Contracts\Database\Eloquent\SupportsPartialRelations;
use NINA\Database\Eloquent\Builder;
use NINA\Database\Eloquent\Collection;
use NINA\Database\Eloquent\Model;
use NINA\Database\Eloquent\Relations\Concerns\CanBeOneOfMany;
use NINA\Database\Eloquent\Relations\Concerns\ComparesRelatedModels;
use NINA\Database\Eloquent\Relations\Concerns\SupportsDefaultModels;
use NINA\Database\Query\JoinClause;

class MorphOne extends MorphOneOrMany implements SupportsPartialRelations
{
    use CanBeOneOfMany, ComparesRelatedModels, SupportsDefaultModels;

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        if (is_null($this->getParentKey())) {
            return $this->getDefaultFor($this->parent);
        }

        return $this->query->first() ?: $this->getDefaultFor($this->parent);
    }

    /**
     * Initialize the relation on a set of models.
     *
     * @param  array  $models
     * @param  string  $relation
     * @return array
     */
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->getDefaultFor($model));
        }

        return $models;
    }

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param  array  $models
     * @param  \NINA\Database\Eloquent\Collection  $results
     * @param  string  $relation
     * @return array
     */
    public function match(array $models, Collection $results, $relation)
    {
        return $this->matchOne($models, $results, $relation);
    }

    /**
     * Get the relationship query.
     *
     * @param  \NINA\Database\Eloquent\Builder  $query
     * @param  \NINA\Database\Eloquent\Builder  $parentQuery
     * @param  array|mixed  $columns
     * @return \NINA\Database\Eloquent\Builder
     */
    public function getRelationExistenceQuery(Builder $query, Builder $parentQuery, $columns = ['*'])
    {
        if ($this->isOneOfMany()) {
            $this->mergeOneOfManyJoinsTo($query);
        }

        return parent::getRelationExistenceQuery($query, $parentQuery, $columns);
    }

    /**
     * Add constraints for inner join subselect for one of many relationships.
     *
     * @param  \NINA\Database\Eloquent\Builder  $query
     * @param  string|null  $column
     * @param  string|null  $aggregate
     * @return void
     */
    public function addOneOfManySubQueryConstraints(Builder $query, $column = null, $aggregate = null)
    {
        $query->addSelect($this->foreignKey, $this->morphType);
    }

    /**
     * Get the columns that should be selected by the one of many subquery.
     *
     * @return array|string
     */
    public function getOneOfManySubQuerySelectColumns()
    {
        return [$this->foreignKey, $this->morphType];
    }

    /**
     * Add join query constraints for one of many relationships.
     *
     * @param  \NINA\Database\Query\JoinClause  $join
     * @return void
     */
    public function addOneOfManyJoinSubQueryConstraints(JoinClause $join)
    {
        $join
            ->on($this->qualifySubSelectColumn($this->morphType), '=', $this->qualifyRelatedColumn($this->morphType))
            ->on($this->qualifySubSelectColumn($this->foreignKey), '=', $this->qualifyRelatedColumn($this->foreignKey));
    }

    /**
     * Make a new related instance for the given model.
     *
     * @param  \NINA\Database\Eloquent\Model  $parent
     * @return \NINA\Database\Eloquent\Model
     */
    public function newRelatedInstanceFor(Model $parent)
    {
        return $this->related->newInstance()
                    ->setAttribute($this->getForeignKeyName(), $parent->{$this->localKey})
                    ->setAttribute($this->getMorphType(), $this->morphClass);
    }

    /**
     * Get the value of the model's foreign key.
     *
     * @param  \NINA\Database\Eloquent\Model  $model
     * @return mixed
     */
    protected function getRelatedKeyFrom(Model $model)
    {
        return $model->getAttribute($this->getForeignKeyName());
    }
}
