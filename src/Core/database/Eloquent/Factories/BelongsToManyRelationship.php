<?php

namespace NINA\Database\Eloquent\Factories;

use NINA\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BelongsToManyRelationship
{
    /**
     * The related factory instance.
     *
     * @var \NINA\Database\Eloquent\Factories\Factory|\Illuminate\Support\Collection|\NINA\Database\Eloquent\Model|array
     */
    protected $factory;

    /**
     * The pivot attributes / attribute resolver.
     *
     * @var callable|array
     */
    protected $pivot;

    /**
     * The relationship name.
     *
     * @var string
     */
    protected $relationship;

    /**
     * Create a new attached relationship definition.
     *
     * @param  \NINA\Database\Eloquent\Factories\Factory|\Illuminate\Support\Collection|\NINA\Database\Eloquent\Model|array  $factory
     * @param  callable|array  $pivot
     * @param  string  $relationship
     * @return void
     */
    public function __construct($factory, $pivot, $relationship)
    {
        $this->factory = $factory;
        $this->pivot = $pivot;
        $this->relationship = $relationship;
    }

    /**
     * Create the attached relationship for the given model.
     *
     * @param  \NINA\Database\Eloquent\Model  $model
     * @return void
     */
    public function createFor(Model $model)
    {
        Collection::wrap($this->factory instanceof Factory ? $this->factory->create([], $model) : $this->factory)->each(function ($attachable) use ($model) {
            $model->{$this->relationship}()->attach(
                $attachable,
                is_callable($this->pivot) ? call_user_func($this->pivot, $model) : $this->pivot
            );
        });
    }

    /**
     * Specify the model instances to always use when creating relationships.
     *
     * @param  \Illuminate\Support\Collection  $recycle
     * @return $this
     */
    public function recycle($recycle)
    {
        if ($this->factory instanceof Factory) {
            $this->factory = $this->factory->recycle($recycle);
        }

        return $this;
    }
}
