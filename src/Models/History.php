<?php

namespace TypiCMS\Modules\History\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Laracasts\Presenter\PresentableTrait;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\History\Presenters\ModulePresenter;
use TypiCMS\Modules\Users\Models\User;

class History extends Base
{
    use PresentableTrait;

    protected $table = 'history';

    protected $presenter = ModulePresenter::class;

    protected $guarded = [];

    protected $appends = ['href'];

    protected $casts = [
        'old' => 'object',
        'new' => 'object',
    ];

    public $order = 'id';

    public $direction = 'desc';

    public function historable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getHrefAttribute(): ?string
    {
        if ($this->historable) {
            return $this->historable->editUrl();
        }

        return null;
    }
}
