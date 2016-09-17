<?php

namespace TypiCMS\Modules\History\Facades;

use Illuminate\Support\Facades\Facade;

class History extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'History';
    }
}
