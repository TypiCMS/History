<?php

namespace TypiCMS\Modules\History\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Filters\FilterOr;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\History\Models\History;

class ApiController extends BaseApiController
{
    public function index(Request $request): LengthAwarePaginator
    {
        $data = QueryBuilder::for(History::class)
            ->allowedFields([
                'history.id',
                'history.created_at',
                'history.title',
                'history.locale',
                'history.icon_class',
                'history.historable_id',
                'history.historable_type',
                'history.action',
                'history.user_id',
            ])
            ->addSelect(
                DB::raw(
                    '(SELECT CONCAT(`first_name`, \' \', `last_name`) FROM `'.
                    DB::getTablePrefix().
                    'users` WHERE `id` = `'.
                    DB::getTablePrefix().
                    "history`.`user_id`) AS 'user_name'"
                )
            )
            ->allowedSorts(['created_at', 'title', 'historable_type', 'action', 'user_name'])
            ->allowedFilters([
                AllowedFilter::custom('title,historable_type,action,user_name', new FilterOr()),
            ])
            ->paginate($request->input('per_page'));

        return $data;
    }

    public function destroy(): JsonResponse
    {
        $deleted = History::truncate();

        return response()->json([
            'error' => !$deleted,
        ]);
    }
}
