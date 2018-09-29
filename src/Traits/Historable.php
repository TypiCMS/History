<?php

namespace TypiCMS\Modules\History\Traits;

use Illuminate\Database\Eloquent\Model;
use TypiCMS\Modules\History\Models\History;
use TypiCMS\Modules\History\Repositories\EloquentHistory;

trait Historable
{
    /**
     * boot method.
     *
     * @return null
     */
    public static function bootHistorable()
    {
        static::created(function (Model $model) {
            $model->writeHistory('created', str_limit($model->present()->title, 200, '…'), [], $model->toArray());
        });

        static::updated(function (Model $model) {
            $action = 'updated';

            $new = [];
            $old = [];
            foreach ($model->attributes as $key => $value) {
                if ($model->translatable and in_array($key, $model->translatable)) {
                    $values = (array) json_decode($value);
                    $originalValues = (array) json_decode($model->original[$key]);
                    foreach ($values as $locale => $newItem) {
                        if (isset($originalValues[$locale]) && $newItem !== $originalValues[$locale]) {
                            $new[$key][$locale] = $newItem;
                            $old[$key][$locale] = $originalValues[$locale];
                        }
                    }
                } else {
                    $originalValue = $model->original[$key];
                    if ($value != $originalValue) {
                        $new[$key] = $value;
                        $old[$key] = $originalValue;
                    }
                }
            }

            $model->writeHistory($action, str_limit($model->present()->title, 200, '…'), $old, $new);
        });

        static::deleted(function (Model $model) {
            $model->writeHistory('deleted', str_limit($model->present()->title, 200, '…'));
        });
    }

    /**
     * Write History row.
     *
     * @param string $action
     * @param string $title
     * @param string $locale
     *
     * @return null
     */
    public function writeHistory($action, $title = null, array $old = [], array $new = [])
    {
        $data['historable_id'] = $this->getKey();
        $data['historable_type'] = get_class($this);
        $data['user_id'] = auth()->id();
        $data['title'] = $title;
        $data['icon_class'] = $this->iconClass($action);
        $data['historable_table'] = $this->getTable();
        $data['action'] = $action;
        $data['old'] = $old;
        $data['new'] = $new;
        (new EloquentHistory())->create($data);
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

            case 'published':
                return 'fa-toggle-on';
                break;

            case 'unpublished':
                return 'fa-toggle-off';
                break;

            default:
                return;
                break;
        }
    }

    /**
     * Model has history.
     */
    public function history()
    {
        return $this->morphMany(History::class, 'historable');
    }
}
