<?php
namespace TypiCMS\Modules\History\Http\Controllers;

use Response;
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
     * @return Response
     */
    public function index()
    {
        $models = $this->repository->latest(25, ['historable', 'user'], true);
        return Response::json($models, 200);
    }
}
