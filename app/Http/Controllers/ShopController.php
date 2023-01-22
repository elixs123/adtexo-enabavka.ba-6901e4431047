<?php

namespace App\Http\Controllers;

use App\Product;
use App\Brand;
use App\Category;
use App\Photo;
use App\The_OrderItem;
use App\The_Order;
use App\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Support\Scoped\ScopedContractFacade as ScopedContract;
use App\Support\Scoped\ScopedDocumentFacade as ScopedDocument;
use App\Support\Scoped\ScopedStockFacade as ScopedStock;

/**
 * Class ProductController
 *
 * @package App\Http\Controllers
 */
class ShopController extends Controller
{
    /**
     * @var \App\Product
     */
    private $product;

    /**
     * @var \App\Category
     */
    private $category;

    /**
     * @var \App\Brand
     */
    private $brand;
	
    /**
     * @var \App\Photo model
     */
    protected $photo;

    /**
     * ProductController constructor.
     *
     * @param \App\Product $product
     * @param \App\Product $productTranslation
     * @param \App\Brand $brand
     * @param \App\Category $category
     */
    public function __construct(
        Product $product,
        Brand $brand,
        Category $category,
		Photo $photo
    ) {
        $this->product = $product;
        $this->category = $category;
        $this->brand = $brand;
        $this->photo = $photo;

        $this->middleware('auth');
        $this->middleware('emptystringstonull');
        $this->middleware('acl:view-shop');
    }

    public function index(Request $request)
    {
        $order = [];
        $pantheonOrder == true;
        if($request->has('acSubject')){
           $orderId =  $this->createOrder($request->input('acSubject'));

           $order = The_Order::with('items')->where('id', $orderId)->firstOrFail();
           
        }


        ScopedContract::setDocument(ScopedDocument::getDocument());
        
        $export = request('export', false);
        $keywords = request('keywords');
        $sort_type = request('sort_type', 'rang');
        $sort_mode = request('sort_mode', 'desc');
    
        if (ScopedDocument::withScopedCategories()) {
            $this->category->includeIds = ScopedDocument::scopedCategories()->pluck('id')->toArray();
            $categories = $this->category->getAll()->keyBy('id');
        } else if (ScopedDocument::withScopedProducts()) {
            $this->category->includeIds = ScopedDocument::scopedProducts()->pluck('category_id')->toArray();
            $categories = $this->category->getAll()->keyBy('id');
        } else {
            $categories = $this->category->getCategoryTree(1);
        }
        
        $brands = [];

        $this->setPjaxParams();

        $this->product->clientId = ScopedDocument::clientId();
        $this->product->langId = ScopedStock::langId();
        $this->product->keywords = $keywords;
        $this->product->sort = $sort_type;
        $this->product->sortOrder = $sort_mode;
        $this->product->paginate = true;
        $this->product->limit = (config('app.env') == 'local') ? 10 : 200;
        $this->product->statusId = 'active';
		$this->product->availableProduct = true;
        $this->product->priceCountryId = ScopedStock::priceCountryId();
        $this->product->priceStockId = ScopedStock::priceStockId();
        $this->product->stockId = ScopedStock::priceStockId();
        
        if (userIsClient() && !is_null($client = $this->getUser()->client)) {
            $this->product->clientPaymentDiscount = $client->payment_discount;
            $this->product->clientDiscount1 = $client->discount_value1;
        }
        
        if (ScopedDocument::withScopedCategories()) {
            $this->product->f_category_id = ScopedDocument::scopedCategories()->pluck('id')->toArray();
        }
        if (ScopedDocument::withScopedProducts()) {
            $this->product->productIds =  ScopedDocument::scopedProducts()->pluck('id')->toArray();
        }
        
        $items = Product::with('stock')->paginate(10);
		
        if($export == 'pdf') {
            return $this->exportToPDF($items);
        }

        $categories = [];
        
        $view = isset($_SERVER["HTTP_X_PJAX"]) ? 'shop.list_fragment' : 'shop.list';

        
        return view($view, array(
            'pantheonOrder' => $pantheonOrder,
            'body_class' => 'ecommerce-application',
            'categories' => $categories,
            'items' => $items,
            'brands' => $brands,
            'order' => $order,
            'currency' => ScopedStock::currency(),
        ));
    }

    public function finishOrder($order){
        The_Order::where('id', $order)->update([
            'acStatus' => 'R'
        ]);

        return redirect()->route('shop.index');
    }

    public function createOrder($acSubject){
        $order = The_Order::where('acSubject', $acSubject)
                        ->where('acStatus', 'N')        
                        ->get();

        if($order->isEmpty()){
            $subject = Subject::where('acSubject', $acSubject)->first();
    
            $acPayer = Subject::where('acSubject', $subject->acPayer)->first();

            

            $order = new The_Order;
            $order->acSubject = $subject->acSubject;
            $order->acStatus = 'N';
            $order->anDaysForValid = Carbon::now()->addDay(5);
            $order->acPayer = $acPayer->acSubject;
            $order->acPayerName = $acPayer->acName2;
            $order->save();

            $order->orderNumber = '#'.sprintf("%06d", $order->id).'/'.date('y');
            $order->save();

            return $order->id;     
        }

        return $order[0]->id;

              
    }

    public function addProduct(Request $request){
       
        if($request->has('acWayOfSale') == 'Z'){
           
            $anPrice = $request->input('anWSPrice2');
            $rebate1 = ((float) $request->input('anWSPrice2')) - ((float) $request->input('anWSPrice2')  * (float) $request->input('anRebate1') / 100);
        }else{
            $anPrice  = $request->input('anRTPrice');
            $rebate1 = ((float) $request->input('anRTPrice') )  - ((float) $request->input('anRTPrice')  *((float) $request->input('anRebate1') / 100));
        }

       

        $rebate2 = $rebate1 - ($rebate1 * ((float) $request->input('anRebate2') / 100));
        $rebate3 = $rebate2 - ($rebate2 * ((float) $request->input('anRebate3') / 100));

        $anForPay = $rebate3;

        $orderItem = The_OrderItem::where('orderNumber', $request->input('orderNumber'))
                                  //  ->where('acIdent', $request->input('acIdent'))
                                    ->first();
       if(!isset($orderItem)){
        $anNo = 1;
       }else{
        $anNo = $orderItem->anNo+1;
       }

       $orderCheck = The_OrderItem::where('orderNumber', $request->input('orderNumber'))
          ->where('acIdent', $request->input('acIdent'))
          ->first();
       
       if($request->has('buttonPlus')){

        

          if(isset($orderCheck)){
            The_OrderItem::where('orderNumber', $request->input('orderNumber'))
                ->where('acIdent', $request->input('acIdent'))
                ->update([
                'anQty' =>  $orderCheck->anQty + (float )$request->input('anQty'),
            ]);
          }else{
            The_OrderItem::insert([
                'acIdent' => $request->input('acIdent'),
                'anPrice' => $anPrice,
                'anQty' => $request->input('anQty'),
                'anRebate1' => $request->input('anRebate1'),
                'orderNumber' => $request->input('orderNumber'),
                'anRebate2' => $request->input('anRebate2'),
                'anRebate3' => $request->input('anRebate3'),
                'anForPay' => $anForPay,
                'anNo' => $anNo,
           ]);
          }
            
        }

        if($request->has('buttonMinus')){
            if(isset($orderCheck)){

                if($orderCheck->anQty < 1){
                    return redirect()->back()->with('alert', 'Kolicina ne moze ici ispod 0.');
                }
                The_OrderItem::where('orderNumber', $request->input('orderNumber'))
                    ->where('acIdent', $request->input('acIdent'))
                    ->update([
                    'anQty' =>  $orderCheck->anQty - (float )$request->input('anQty'),
                ]);
            }

        }

        $sumAnForPay = The_OrderItem::where('orderNumber', $request->input('orderNumber'))->sum(DB::raw('anForPay * anQty'));

        The_Order::where('orderNumber', $request->input('orderNumber'))->update([
            'anForPay' => $sumAnForPay
        ]);
       

       return redirect()->back();
    }
	
    /**
     * Search for autocomplete keywords
     *
     * @return Json
     */
    public function autocomplete()
    {
		$return = $items = [];
		
        $this->product->clientId = ScopedDocument::clientId();
        $this->product->langId = ScopedStock::langId();
        $this->product->keywords = request()->get('query');
        $this->product->paginate = true;
        $this->product->limit = 20;
        $this->product->statusId = 'active';
		$this->product->availableProduct = true;
        $this->product->priceCountryId = ScopedStock::priceCountryId();
        $this->product->priceStockId = ScopedStock::priceStockId();
        $this->product->stockId = ScopedStock::priceStockId();
    
        if (ScopedDocument::withScopedCategories()) {
            $this->product->f_category_id = ScopedDocument::scopedCategories()->pluck('id')->toArray();
        }
        if (ScopedDocument::withScopedProducts()) {
            $this->product->productIds =  ScopedDocument::scopedProducts()->pluck('id')->toArray();
        }
        
       // $items = $this->product->getAll();
        $items = Product::all();
        foreach ($items as $v) {
		
            $return[] = [
                'value' => $v->acCode . ' ' .html_entity_decode($v->acName),
                'data' => url('shop/' . str_slug($v->acName) . '/' . $v->acIdent )
            ];
        }

        return ['suggestions' => $return];
    }
	
    /**
     * @param \Illuminate\Database\Eloquent\Collection $items
     * @return \Illuminate\Http\Response
     */
    private function exportToPDF($items)
    {
        return \PDF::loadView('product.export_pdf', ['items' => $items])->download('products.pdf');
    }

    public function getProductShow($title, $id)
    {
        $item = $this->product->getOne($id);
        $item = Product::find($id);
		
		if(!isset($item->acIdent))
		{
			return redirect('/shop');
		}
		
		if(is_array($item->related))
		{
			$this->product->productIds = $item->related;
			$this->product->limit = 20;
			$related = $this->product->getAll();
		}
		else
		{
			$related = collect([]);
		}
		
        $this->photo->module = 'gallery';
        $gallery = $this->photo->getPhotos($id);

        return view('shop.show', array(
            'body_class' => 'ecommerce-application',
			'related' => $related,
			'item' => $item,
			'gallery' => $gallery,
            'currency' => ScopedStock::currency(),
        ));
    }
    
    private function setPjaxParams()
    {
        $parse_url = parse_url(request()->fullUrl());

        if (isset($parse_url['query']))
        {
            $filters = explode('&', $parse_url['query']);

            foreach ($filters as $id => $filter)
            {
                $params = explode('=', $filter);
				
				if(count($params) == 2)
				{
					$params[1] = strpos($params[1], '.') !== false ? explode('.', $params[1]) : $params[1];

					if (property_exists('\App\Product', $params[0]))
					{
						$prop = (String) $params[0];
						$this->product->$prop = $params[1];
					}
				}
            }

            request()->session()->flash('url_query_' . basename(request()->url()), request()->fullUrl());
        }
    }
}
