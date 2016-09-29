<?php

namespace TypiCMS\Modules\History\Repositories;

use TypiCMS\Modules\Core\Repositories\EloquentRepository;
use TypiCMS\Modules\History\Models\History;

class EloquentHistory extends EloquentRepository
{
    protected $repositoryId = 'history';

    protected $model = History::class;

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

    /**
     * Clear history.
     *
     * @return bool
     */
    public function clear()
    {
        return $this->make()->delete();
    }
}
