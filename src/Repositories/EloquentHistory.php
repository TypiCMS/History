<?php

namespace TypiCMS\Modules\History\Repositories;

use TypiCMS\Modules\Core\Repositories\EloquentRepository;
use TypiCMS\Modules\History\Models\History;

class EloquentHistory extends EloquentRepository
{
    protected $repositoryId = 'history';

    protected $model = History::class;

    /**
     * Get latest models.
     *
     * @param int $number number of items to take
     *
     * @return Collection
     */
    public function latest($number = 10)
    {
        return $this->executeCallback(get_called_class(), __FUNCTION__, func_get_args(), function () use ($number) {
            return $this->prepareQuery($this->createModel())
                ->order()
                ->with(['user', 'historable'])
                ->take($number)
                ->get();
        });
    }

    /**
     * Clear history.
     *
     * @return bool
     */
    public function clear()
    {
        $deleted = $this->getQuery()->delete();
        $this->forgetCache();
        return $deleted;
    }
}
