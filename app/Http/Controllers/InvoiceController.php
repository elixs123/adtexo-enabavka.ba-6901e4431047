<?php

namespace App\Http\Controllers;

use App\Document;
use App\FiscalModels\FiscalResponse;
use App\Http\Fiscal\Constants;
use App\Libraries\Api\PantheonApi;
use App\Libraries\PantheonHelper;
use App\Support\Controller\Dashboard\DashboardAdminHelper;
use Illuminate\Http\Request;
use App\Route;
use App\Support\Controller\DashboardHelper;
use App\Support\Controller\DatesHelper;
use App\Support\Controller\RouteHelper;
use App\InvoiceHelper;
use DateTime;
use App\Http\Resources\Document\DocumentResource as ModelResource;
use JsonMapper;
use App\Support\Controller\DocumentStatusHelper;

class InvoiceController extends Controller
{
    use DashboardHelper, RouteHelper, DatesHelper, DashboardAdminHelper, DocumentStatusHelper;

    private $document;

    private $client;

    private $user;

    private $route;

    public function __construct(Route $route, Document $document)
    {
        $this->route = $route;
        $this->document = $document;

        $this->middleware('auth');
        $this->middleware('acl:view-invoicing', ['only' => ['index']]);
    }

    public function index()
    {
        return view('invoice.index')->with($this->getViewData());
    }

    public function getFiscalRequest($id)
    {
        $printRequest = $this->getInvoicePrintRequest($id);

        return json_encode($printRequest);
    }

    public function saveFiscalData($id, Request $request)
    {
        $mapper = new JsonMapper();
        $fiscalResponse = $mapper->map(
            json_decode($request->fiscalResponse),
            new FiscalResponse()
        );

        $this->document = $this->document->getOne($id);

        if (($this->document->receiptNo == null)
            && ($fiscalResponse->receiptNo != null
                && $fiscalResponse->receiptNo != '')
        ) {
            $this->document->fiscal_receipt_no = $fiscalResponse->receiptNo;
            $this->document->fiscal_receipt_amount = $fiscalResponse->receiptAmount;
            $this->document->date_of_invoice = date("Y-m-d H:i:s");

            if ($fiscalResponse->receiptDate == null) {
                $this->document->fiscal_receipt_datetime = date("Y-m-d H:i:s");
            } else {
                $this->document->fiscal_receipt_datetime = DateTime::createFromFormat(Constants::DATE_TIME_FORMAT, $fiscalResponse->receiptDate);
            }

            $this->document->status = "invoiced";
            $this->document->save();
            $this->updateStock($this->document);
        }

        return json_encode($this->document);
    }

    public function saveFiscalVoidData($id, Request $request)
    {
        $mapper = new JsonMapper();
        $fiscalResponse = $mapper->map(
            json_decode($request->fiscalResponse),
            new FiscalResponse()
        );

        $this->document = $this->document->getOne($id);

        if (
            $fiscalResponse->receiptNo != null
            && $fiscalResponse->receiptNo != ""
        ) {
            $this->document->fiscal_receipt_void_no = $fiscalResponse->receiptNo;
            $this->document->fiscal_receipt_void_amount = $fiscalResponse->receiptAmount;


            if ($fiscalResponse->receiptDate == null) {
                $receiptDate = date("Y-m-d H:i:s");
            } else {
                $receiptDate = DateTime::createFromFormat(Constants::DATE_TIME_FORMAT, $fiscalResponse->receiptDate);
            }

            $this->document->fiscal_receipt_void_datetime = $receiptDate;
            $this->document->status = "reversed";
            $this->document->sync_status = null;
            $this->document->date_of_sync = null;
            $this->document->save();
            $this->updateStock($this->document);
        }

        return json_encode($this->document->getOne($id));
    }


    private function getInvoicePrintRequest($id): \App\FiscalModels\FiscalRequest
    {
        $this->document = $this->document->getOne($id);
        $invoiceHelper = new InvoiceHelper($this->document);
        return $invoiceHelper->getFiscalRequest($id);
    }

    public function printInvoice($id)
    {
        $item = $this->document->getOne($id);
        $item->r_document_product = $item->rDocumentProduct()->with(['rDocument', 'rUnit'])->get();

        $items = $item->rDocumentProduct()->with(['rDocument', 'rUnit'])->get();

        return view('invoice.document.show')
            ->with('document', $item)
            ->with('products', $item->rDocumentProduct()->with(['rDocument', 'rUnit'])->get())
            ->with('changes', $item->rDocumentChanges()->with(['rChangedBy.rPerson'])->get()->keyBy('product_id'))->with('document', $item);
    }

    public function getDocument($id)
    {
        $this->document->relation(['rStatus', 'rType', 'rDeliveryType', 'rDocumentProduct.rDocument', 'rDocumentProduct.rUnit'], true);
        $doc = $this->document->GetOne($id);

        return json_encode(new ModelResource($doc));
    }
    
    /**
     * @param \App\Document|mixed $document
     * @param string|integer $fiscalNumber
     * @return string|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function updateOrderFiscalNumber($document, $fiscalNumber)
    {
        $order = $document->rOrder;
        
        if (!is_null($order)) {
            $pantheon_api = new PantheonApi();
    
            $pantheon_api->updateFiscalNumber($order->name, $fiscalNumber);
    
            if (!$pantheon_api->hasErrors()) {
                return 'FISCAL_NUMBER_UPDATED';
            } else {
                return 'FISCAL_NUMBER_UPDATE_ERROR';
            }
        }
        
        return 'ORDER_NOT_FOUND';
    }

    /**
     * @return array
     */
    private function getViewData()
    {
        $data = [];

        $this->getInvoicingData($data);

        $this->getAdminData($data, 'documents');

        return $data;
    }
}
