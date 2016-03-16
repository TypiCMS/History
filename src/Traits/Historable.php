<?php

namespace TypiCMS\Modules\History\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use TypiCMS\Modules\History\Models\History;

trait Historable
{
    /**
     * boot method.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::created(function (Model $model) {
            if (!$model->owner) { // don't write history for each translation
                $model->writeHistory('created', $model->present()->title);
            }
        });
        static::updated(function (Model $model) {
            $action = 'updated';
            if (!$model->owner) { // if model has no owner, $model is not a translation
                $model->writeHistory($action, $model->present()->title);
            } else { // if model has owner, $model is a translation
                // When owner is dirty, history will be written for owner model
                if ($model->owner->getDirty()) {
                    return;
                }
                // When item is set online or offline,
                // getDirty returns only two columns : updated_at and status
                if (count($model->getDirty()) == 2 && $model->isDirty('status')) {
                    $action = $model->status ? 'set online' : 'set offline';
                }
                $model->owner->writeHistory($action, $model->owner->present()->title, $model->locale);
            }
        });
        static::deleting(function (Model $model) {
            $model->writeHistory('deleted', $model->present()->title);
        });
    }

    /**
     * Write History row.
     *
     * @param string $action
     * @param string $title
     * @param string $locale
     *
     * @return void
     */
    public function writeHistory($action, $title = null, $locale = null)
    {
        $history = app('TypiCMS\Modules\History\Repositories\HistoryInterface');
        $data['historable_id'] = $this->getKey();
        $data['historable_type'] = get_class($this);
        $data['user_id'] = $this->getUserId();
        $data['title'] = $title;
        $data['locale'] = $locale;
        $data['icon_class'] = $this->iconClass($action);
        $data['historable_table'] = $this->getTable();
        $data['action'] = $action;
        $history->create($data);
    }

    /**
     * Return icon class for each action.
     *
     * @param string $action
     *
     * @return string|null
     */
    private function iconClass($action = null)
    {
        switch ($action) {
            case 'deleted':
                return 'fa-trash';
                break;

            case 'updated':
                return 'fa-edit';
                break;

            case 'created':
                return 'fa-plus-circle';
                break;

            case 'set online':
                return 'fa-toggle-on';
                break;

            case 'set offline':
                return 'fa-toggle-off';
                break;

            default:
                return;
                break;
        }
    }

    /**
     * Get current user id.
     *
     * @return int|null
     */
    private function getUserId()
    {
        if ($user = Auth::user()) {
            return $user->id;
        }
    }

    /**
     * Model has history.
     */
    public function history()
    {
        return $this->morphMany('TypiCMS\Modules\History\Models\History', 'historable');
    }
}
