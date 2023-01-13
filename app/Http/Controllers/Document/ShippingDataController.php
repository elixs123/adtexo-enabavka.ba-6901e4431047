<?php

namespace App\Http\Controllers\Document;

use App\City;
use App\Document;
use App\DocumentChange;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\UpdateShippingDataRequest;
use Illuminate\Support\Facades\DB;

/**
 * Class ShippingDataController
 *
 * @package App\Http\Controllers\Document
 */
class ShippingDataController extends Controller
{
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * @var \App\DocumentChange
     */
    private $documentChange;
    
    /**
     * @var \App\City
     */
    private $city;
    
    /**
     * ChangeController constructor.
     *
     * @param \App\Document $document
     * @param \App\DocumentChange $documentChange
     * @param \App\City $city
     */
    public function __construct(Document $document, DocumentChange $documentChange, City $city)
    {
        $this->document = $document;
        $this->documentChange = $documentChange;
        $this->city = $city;
    }
    
    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit($id)
    {
        $this->document->statusId = 'invoiced';
        $item = $this->document->relation(['rClient'])->getOne($id);
    
        if (is_null($item)) {
            abort(404, trans('document.errors.not_found', ['id' => $id]));
        }
    
        $this->city->countryId = $item->rClient->country_id;
        $this->city->limit = null;
        $cities = $this->city->getAll()->pluck('full_city', 'postal_code')->toArray();
        
        return view('document.shipping.form')->with([
            'form_title' => $item->full_name.' - Detalji isporuke',
            'document' => $item,
            'cities' => $cities,
        ]);
    }
    
    /**
     * @param \App\Http\Requests\Document\UpdateShippingDataRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateShippingDataRequest $request, $id)
    {
        $this->document->statusId = 'invoiced';
        $document = $this->document->relation(['rClient'])->getOne($id);
    
        if (!is_null($document)) {
            $shipping_data = $request->get('shipping_data', []);
    
            $document_changes = [];
            foreach ($shipping_data as $key => $value) {
                if (isset($document->shipping_data[$key])) {
                    $now = now()->toDateTimeString();
                    
                    if ($document->shipping_data[$key] != $value) {
                        $document_changes[] = [
                            'document_id' => $id,
                            'changed_by' => $this->getUserId(),
                            'type' => 'shipping_data',
                            'value' => $value,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }
    
            $document = $this->dbTransaction(function () use ($document, $shipping_data, $document_changes) {
                $document->update([
                    'shipping_data' => $shipping_data,
                ]);
                
                if (isset($document_changes[0])) {
                    DB::table($this->documentChange->getTable())->insert($document_changes);
                }
                
                return $document;
            });
        }
    
        return $this->getSuccessJsonResponse([
            'close_modal' => true,
            'notification' => [
                'type' => 'success',
                'message' => trans('document.notifications.changed'),
            ],
            'shipping' => [
                'id' => $id,
                'name' => $document->shipping_data['name'],
                'address' => $document->shipping_data['address'],
                'city' => $document->shipping_data['postal_code'].', '.$document->shipping_data['city'],
            ],
        ]);
    }
}
