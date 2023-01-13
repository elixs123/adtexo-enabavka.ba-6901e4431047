<?php

namespace App\Http\Controllers\Api;

use App\Document;
use App\Support\Controller\DocumentHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Document\DocumentCollection as ModelCollection;
use App\Http\Resources\Document\DocumentResource as ModelResource;
/**
 * Class DocumentController
 *
 * @package App\Http\Controllers\Api
 */
class DocumentController extends Controller
{
    use DocumentHelper;
    
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * DocumentController constructor.
     *
     * @param \App\Document $document
     */
    public function __construct(Document $document)
	{
        $this->document = $document;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Throwable
     */
    public function index()
    {
        $syncStatus = request()->get('sync_status');
        $statusId = request()->get('status');
        $clientId = request()->get('client_id');
        $typeId = request()->get('type_id');
        $keywords = request()->get('keywords');
        $startDate = request('start_date');
        $endDate = request('end_date');
        $createdBy = request('created_by');
        $limit = request('limit', 100);
		
		$this->document->syncStatus = $syncStatus;
		$this->document->clientId = $clientId;
		$this->document->statusId = $statusId;
        $this->document->typeId = $typeId;
		$this->document->startDate = $startDate;
		$this->document->endDate = $endDate;
        $this->document->keywords = $keywords;
        $this->document->createdBy = $createdBy;
        $this->document->limit = $limit;
        $this->document->paginate = true;
        $this->document->relation(['rStatus', 'rType', 'rDeliveryType', 'rDocumentProduct.rDocument', 'rDocumentProduct.rUnit', 'rClient.rHeadquarter'], true);
        
        $items = $this->document->getAll();
		
		return new ModelCollection($items);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Throwable
     */
    public function sync($status)
    {
        $ids = request()->get('ids', []);
		
		if(count($ids) > 0 && is_array($ids))
		{
			$dateOfSync = date('Y-m-d H:i:s');
			
			$this->document->whereIn('id', $ids)->update(['sync_status' => $status, 'date_of_sync' => $dateOfSync]);

			return response()->json([
				'status' => 'success',
				'msg' => 'Sync successful'
			], 200);
		}
		else
		{
			return response()->json([
				'status' => 'error',
				'msg' => 'Sync failed'
			], 422);
		}
		
    }
    
    /**
     * @param int $id
     * @return \App\Http\Resources\Document\DocumentResource
     */
    public function show($id)
    {
        $this->document->relation(['rStatus', 'rType', 'rDeliveryType', 'rDocumentProduct.rDocument', 'rDocumentProduct.rUnit', 'rClient.rHeadquarter'], true);
        $item = $this->document->getOne($id);
        
        return new ModelResource($item);
    }
}
