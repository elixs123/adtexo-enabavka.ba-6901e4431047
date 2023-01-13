<?php

namespace App\Http\Controllers;

use App\Document;
use App\Log;

/**
 * Class LogController
 *
 * @package App\Http\Controller
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

        $this->middleware('acl:view-log');
    }
	
    /**
     * Index.
     *
     * @return Resources
     *
     */
    public function index()
    {
        $keywords = request()->get('keywords');
        $startDate = request('start_date');
        $endDate = request('end_date');
		
		$this->log->dateFromValue = $startDate;
		$this->log->dateToValue = $endDate;
        $this->log->keywords = $keywords;
		$this->log->limit = 100;
		$this->log->paginate = true;
		$items = $this->log->getAll();

        return view('log.index', [
            'items' => $items,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }
	
    /**
     * Show.
     *
     * @param int $id
     * @return Resources
     *
	 */
    public function show($id)
    {
        $item = $this->log->getOne($id);
		$item->model = $item->loggable;
  
		return $item;
    }
    
    /**
     * @return array
     */
    public function synced()
    {
        $ids = [21230,23593,23410,20638,22944,22945,23134,23482,23629,20509,22905,23525,22900,23396,23448,22908,23742,23983,22893,23021,23272,23550,24005,23345,23581,22937,23846,23610];
        
        Log::where('loggable_type', 'App\Document')->whereIn('loggable_id', $ids)->delete();
        
        Document::whereIn('id', $ids)->update([
            'sync_status' => 'synchronized',
            'date_of_sync' => now()->toDateTimeString(),
        ]);
        
        // $logs = DB::table('logs')->join('documents', 'documents.id', 'logs.loggable_id')
        //     ->where('logs.loggable_type', 'App\Document')
        //     ->whereNull('logs.deleted_at')
        //     ->where(function($query) {
        //         $query->where('documents.sync_status', 'synchronized');
        //         // $query->orWhere('documents.created_at', '2021-09-02 00:00:00');
        //     })
        //     ->get(['logs.id']);
        //
        // Log::whereIn('id', $logs->pluck('id')->toArray())->delete();
        
        return $ids;
    }
    
    /**
     * @return array
     */
    public function forSync()
    {
        $ids = [20310,20960,22450,19051,19333,21091,21503,21697,21864,22079,22083,22528,22529,22549,22382,22390,22392,22394,21019,22155,22441,22335,20373,19814,19850,19821];
        
        Document::whereIn('id', $ids)->update([
            'sync_status' => null,
            'date_of_sync' => null,
        ]);
        
        return $ids;
    }
}
