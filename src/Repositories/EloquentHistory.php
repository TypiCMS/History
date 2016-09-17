<?php

namespace TypiCMS\Modules\History\Repositories;

use Illuminate\Database\Eloquent\Model;
use TypiCMS\Modules\Core\Repositories\RepositoriesAbstract;

class EloquentHistory extends RepositoriesAbstract implements HistoryInterface
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all models.
     *
     * @param array $with Eager load related models
     * @param bool  $all  Show published or all
     *
     * @return Collection|NestedCollection
     */
    public function all(array $with = [], $all = false)
    {
        $query = $this->make($with);

        // Query ORDER BY
        $query->order();

        // Get
        return $query->get();
    }

    /**
     * Get latest models.
     *
     * @param int   $number number of items to take
     * @param array $with   array of related items
     *
     * @return Collection
     */
    public function latest($number = 10, array $with = [])
    {
        $query = $this->make($with);

        return $query->order()->take($number)->get();
    }
}
