<?php
namespace TypiCMS\Modules\History\Http\Controllers;

use Illuminate\Support\Facades\Input;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\History\Repositories\HistoryInterface as Repository;

class ApiController extends BaseApiController
{
    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Get models
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function index()
    {
        $models = $this->repository->latest(25, ['historable', 'user'], true);
        return response()->json($models, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Model|false
     */
    public function store()
    {
        return $this->repository->create(Input::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $model
     * @return boolean
     */
    public function update($model)
    {
        return $this->repository->update(Input::all());
    }
}
