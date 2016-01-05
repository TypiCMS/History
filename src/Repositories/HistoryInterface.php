<?php

namespace TypiCMS\Modules\History\Repositories;

use TypiCMS\Modules\Core\Repositories\RepositoryInterface;

interface HistoryInterface extends RepositoryInterface
{
    /**
     * Clear history.
     *
     * @return boolean
     */
    public function empty();
}
