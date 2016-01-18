<?php

namespace TypiCMS\Modules\History\Http\Controllers;

use Illuminate\Support\Facades\Request;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\History\Repositories\HistoryInterface as Repository;

class ApiController extends BaseApiController
{
    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * List resources.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $models = $this->repository->latest(25, ['historable', 'user'], true);

        return response()->json($models);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $model = $this->repository->create(Request::all());
        $error = $model ? false : true;

        return response()->json([
            'error' => $error,
            'model' => $model,
        ]);
    }

    /**
     * Clear history.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        $cleared = $this->repository->clear();

        return response()->json([
            'error' => (bool) !$cleared,
        ]);
    }
}
