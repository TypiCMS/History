<?php

namespace TypiCMS\Modules\History\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\History\Models\History;

class ApiController extends BaseApiController
{
    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(History::class)
            ->paginate($request->input('per_page'));

        return $data;
    }

    public function destroy()
    {
        $cleared = $this->model->clear();

        return response()->json([
            'error' => (bool) !$cleared,
        ]);
    }
}
