<?php

namespace App\Http\Controllers\Api;

use App\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class LogController
 *
 * @package App\Http\Controllers\Api
 */
class LogController extends Controller
{
    /**
     * @var \App\Log
     */
    private $log;
    
    /**
     * LogController constructor.
     *
     * @param \App\Log $document
     */
    public function __construct(Log $log)
	{
        $this->log = $log;
		
        $this->middleware('xss', ['only' => ['store']]);
    }
	
    /**
     * Index.
     *
     * @return \App\Http\Resources\Log\LogCollection
     *
     * @api GET /api/v1/logs
     */
    public function index()
    {
		$this->log->limit = request('limit', 100);

		$items = $this->log->getAll();

        return $items;
    }
	
    /**
     * Show.
     *
     * @param int $id
     * @return \App\Http\Resources\Log\LogResource
     *
     * @api GET /api/v1/logs/{id}
     */
    public function show($id)
    {
        $item = $this->log->getOne($id);
		$item->model = $item->loggable;
  
		return $item;
    }
    
    /**
     * Store.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\Log\LogResource
     *
     * @api POST /api/v1/logs
     */
    public function store(Request $request)
    {
        $attributes = $request->get('log');
		
		foreach($attributes as $attribute)
		{
		    $this->clearPreviousLog($attribute);
		    
			$this->log->create($attribute);
		}

        return $attributes;
    }
    
    /**
     * @param array $attribute
     * @return void
     */
    private function clearPreviousLog($attribute)
    {
        Log::where('loggable_type', $attribute['loggable_type'])->where('loggable_id', $attribute['loggable_id'])->delete();
    }
}
