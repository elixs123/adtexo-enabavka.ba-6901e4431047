<?php

namespace App\Http\Controllers;

use App\Document;
use App\DocumentProduct;
use App\Product;
use App\ProductQuantity;
use App\ProductStock;
use App\City;
use App\Support\Controller\DocumentProductHelper;
use App\Support\Scoped\ScopedContractFacade as ScopedContract;
use App\Support\Scoped\ScopedDocumentFacade as ScopedDocument;
use App\Support\Scoped\ScopedStockFacade as ScopedStock;
use Illuminate\Support\Facades\DB;

/**
 * Class CartController
 *;
 * @package App\Http\Controllers
 */
class CartController extends Controller
{
    use DocumentProductHelper;
    
    /**
     * @var \App\Document
     */
    private $document;

    /**
     * @var \App\Product
     */
    private $product;
    
    /**
     * @var \App\DocumentProduct
     */
    private $documentProduct;

    /**
     * @var \App\ProductStock
     */
    private $productStock;

    /**
     * CartController constructor.
     *
     * @param \App\Document $document
     * @param \App\Product $product
     * @param \App\DocumentProduct $documentProduct
     * @param \App\ProductStock $productStock
     */
    public function __construct(Document $document, Product $product, DocumentProduct $documentProduct, ProductStock $productStock)
    {
        $this->document = $document;
        $this->documentProduct = $documentProduct;
        $this->product = $product;
        $this->productStock = $productStock;

        $this->middleware('xss');
        $this->middleware('auth');
        $this->middleware('emptystringstonull');
        $this->middleware('acl:view-shop');
    }

    public function index(City $city)
    {
        if (!ScopedDocument::exist()) {
            abort(404);
        }
        
        if (!ScopedDocument::isOrder() && !ScopedDocument::isCash()) {
            return redirect()->to(route('document.show', ['id' => ScopedDocument::id()]));
        }
    
        $document = ScopedDocument::getDocument();
    
        $summary_view = $this->summaryView($document);
        
		$city->countryId = $document->rClient->country_id;
        $city->limit = null;
		$cities = $city->getAll();
        
        return view('cart.index')
                ->with('document', $document)
                ->with('cities', $cities)
                ->with('products', ScopedDocument::products())
                ->with('summary_view', $summary_view)
                ->with('body_class', 'ecommerce-application');
    }
	
    public function quickEstimateOverview()
    {
        if (!ScopedDocument::exist()) {
            abort(404);
        }
    
        $document = ScopedDocument::getDocument();
    
        $summary_view = $this->summaryView($document);
        
        return view('cart.' . $summary_view)
                ->with('document', $document)
                ->with('products', ScopedDocument::products())
                ->with('btn_text', 'Nastavi dalje')
                ->with('btn_class', 'place-order');
    }
    
    /**
     * @param \App\Document|mixed $document
     * @return string
     */
    private function summaryView($document)
    {
        $summary_view = '_summary';
        if ($document->created_at->toDateTimeString() <= config('client.mpc_start_timestamp')) {
            $summary_view = '__summary';
        } else if ($document->id >= config('client.pantheon_document_id')) {
            $summary_view = 'summary';
        }
        
        return $summary_view;
    }

    /**
     * Add product in cart
     *
     * @return array
     */
    public function add($productId, $quantity)
    {
        if (!ScopedDocument::exist()) {
            abort(404, trans('document.errors.scoped.not_found'));
        }
        
        ScopedContract::setDocument(ScopedDocument::getDocument());
        
		$documentId = ScopedDocument::id();

		$documentProduct = $this->documentProduct->getOneByProdcutIdAndDocumentId($productId, $documentId);
    
        $product = null;
        if (is_null($documentProduct)) {
            $product = $this->product->relation(['rPromoItems' => function ($query) {
                $query->without(['rProductPrices', 'rProductPrices.rBadge','rUnit' ])
                    ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
                    ->select([
                        'products.*',
                        'product_translations.name',
                    ]);
            }], true)->getOne($productId);
        }
		
		$currency = is_null($documentProduct) ? ScopedStock::currency() : null;
        
        $product_min_qty = ScopedDocument::getProductMinQty($productId);
        if ($quantity < $product_min_qty) {
            $quantity = $product_min_qty;
        }
    
        $totals = DB::transaction(function () use ($productId, $product, $quantity, $documentProduct){
            if (is_null($documentProduct)) {
                $totals = $this->addDocumentProduct(ScopedDocument::getDocument(), $product, $quantity);
            } else {
                $totals = $this->updateDocumentProduct(ScopedDocument::getDocument(), $documentProduct, $quantity);
            }
            
            return $totals;
        });
		
		return [
			'subtotal' => format_price($totals->total_discounted_value).' '.ScopedDocument::currency(),
			'items' => $totals->rDocumentProduct()->count()
		];
    }
    
    /**
     * Remove product from cart
     *
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function remove($id)
    {
        if (!ScopedDocument::exist()) {
            abort(404, trans('document.errors.scoped.not_found'));
        }
        
        $documentProduct = $this->documentProduct->getOneByProdcutIdAndDocumentId($id, ScopedDocument::id());
  
        if (!is_null($documentProduct)) {
		    $totals = DB::transaction(function () use ($documentProduct){
                $documentProduct->delete();
			
			    return (ScopedDocument::getDocument())->updateTotals();
            });
        } else {
            $totals = ScopedDocument::getDocument();
        }
		
        return [
            'subtotal' => format_price($totals->total_discounted_value).' '.ScopedDocument::currency(),
            'items' => $totals->rDocumentProduct()->count()
        ];
    }
    
    /**
     * Update product in cart
     *
     * @return array
     */
    public function update($id, $quantity)
    {
        if (!ScopedDocument::exist()) {
            abort(404, trans('document.errors.scoped.not_found'));
        }
        
        $documentProduct = $this->documentProduct->getOneByProdcutIdAndDocumentId($id, ScopedDocument::id());
        
        $totals = $this->dbTransaction(function () use ($documentProduct, $quantity) {
            return $this->updateDocumentProduct(ScopedDocument::getDocument(), $documentProduct, $quantity);
        });
        
        return [
            'subtotal' => format_price($totals->total_discounted_value).' '.$totals->currency,
            'items' => $totals->rDocumentProduct->count()
        ];
    }

    public function finish()
    {
        if (!ScopedDocument::exist()) {
            abort(404);
        }
    
        $document = ScopedDocument::getDocument();
        
        if (ScopedDocument::deliveryType() == 'personal_takeover') {
            $shipping = null;
        } else {
            $shipping = [
                'name' => request('name'),
                'email' => request('email'),
                'address' => request('address'),
                'city' => request('city'),
                'postal_code' => request('postal_code'),
                'country' => request('country'),
                'phone' => request('phone'),
                'shipping_name' => '',
            ];
            
            if (auth()->user()->isSalesAgent()) {
                $shipping['shipping_name'] = $shipping['name'];
            } else {
                if ($document->rClient->type_id == 'business_client') {
                    $client = $document->rClient;
                    
                    $shipping = [
                        'name' => $client->name,
                        'email' => request('email'),
                        'address' => $client->address ?: request('address'),
                        'city' => $client->city ?: request('city'),
                        'postal_code' => $client->postal_code ?: request('postal_code'),
                        'country' => $client->country_id ?: request('country'),
                        'phone' => $client->phone ?: request('phone'),
                        'shipping_name' => $client->name,
                    ];
                } else {
                    $shipping['shipping_name'] = $shipping['name'];
                }
            }
        }
    
        $price = $document->useMpcPrice() ? $document->total_discounted : $document->subtotal_discounted;
        // $disc = ($document->has_discount) ? ($price - calculateDiscount($price, $document->discount1, $document->discount2)) : 0;
        $net_value = $document->useMpcPrice() ? getPriceWithoutVat($price, $document->tax_rate) : $price;
        // $vat_value = getVatFromPrice($net_value, $document->tax_rate);
        // $net_tax_value = $net_value + $vat_value;
        
        $delivery_cost = calcDeliveryCost($document->delivery_type, $document->rStock->country_id, $net_value, $document->delivery_cost);

        $data = [
            'delivery_cost' => $delivery_cost,
            'shipping_data' => $shipping,
            'note' => request('note'),
            'note_express_post' => request('note_express_post'),
            'payment_type' =>  request('payment_type'),
            // 'status' => 'in_warehouse',
            // 'date_of_order' => date('Y-m-d'),
            // 'in_warehouse' => now()->toDateTimeString(),
        ];
        
        if (($document->payment_type == 'advance_payment') ||
            auth()->user()->isClient() ||
            (($document->rClient->type_id == 'business_client') && is_null($document->rClient->code)) ||
            ($document->rClient->status == 'blocked')
        ) {
            $data['status'] = 'in_process';
        } else {
            $data['status'] = 'in_warehouse';
            $data['in_warehouse'] = now()->toDateTimeString();
        }
		
		$id = DB::transaction(function () use ($data, $document)
		{
			$id = ScopedDocument::id();
			
			$this->document->edit($id, $data);
			
			foreach ($document->rDocumentProduct as $product) {
			    ProductQuantity::incrementReservedQty($document->stock_id, $product->product_id, $product->qty);
            }
			
			if ($document->isAction()) {
			    $document->rAction->incrementReservedQty($document->action_qty);
                
                foreach ($document->rDocumentGratisProducts as $product) {
                    ProductQuantity::incrementReservedQty($document->stock_id, $product->product_id, $product->qty);
                }
            }
			
			//ScopedDocument::removeFromStock();

			ScopedDocument::close();
			
			return $id;
        });
        
        if (!is_null($shipping) && isset($shipping['email'])) {
            if (config('app.env') == 'production') {
                $document->fresh()->sendTrackingCodeNotification();
            }
        }

        return redirect()->to(route('document.show', ['id' => $id]))->with('success_msg', 'Narudžba je uspješno završena!');
    }
}
