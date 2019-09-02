<?php

namespace TypiCMS\Modules\History\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\History\Models\History;

class ApiController extends BaseApiController
{
    public function index(Request $request): LengthAwarePaginator
    {
        $data = QueryBuilder::for(History::class)
            ->paginate($request->input('per_page'));

        return $data;
    }

    public function destroy(): JsonResponse
    {
        $cleared = $this->model->clear();

        return response()->json([
            'error' => (bool) !$cleared,
        ]);
    }
}
