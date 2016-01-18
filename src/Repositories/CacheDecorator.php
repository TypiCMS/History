<?php

namespace TypiCMS\Modules\History\Repositories;

use TypiCMS\Modules\Core\Repositories\CacheAbstractDecorator;
use TypiCMS\Modules\Core\Services\Cache\CacheInterface;

class CacheDecorator extends CacheAbstractDecorator implements HistoryInterface
{
    public function __construct(HistoryInterface $repo, CacheInterface $cache)
    {
        $this->repo = $repo;
        $this->cache = $cache;
    }

    /**
     * Clear history.
     *
     * @return bool
     */
    public function clear()
    {
        $this->cache->flush();
        $this->cache->flush('dashboard');
        return $this->repo->clear();
    }
}
