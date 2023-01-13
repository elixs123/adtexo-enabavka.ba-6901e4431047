<?php

namespace App\Http\Controllers;

use App\Product;
use App\Brand;
use App\Category;
use App\Photo;
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

    public function index()
    {
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
        
        $brands = $this->product->getBrandsPerCategory();

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
        
        $items = $this->product->relation(['rProductQuantities'])->getAll();
		
        if($export == 'pdf') {
            return $this->exportToPDF($items);
        }
        
        $view = isset($_SERVER["HTTP_X_PJAX"]) ? 'shop.list_fragment' : 'shop.list';

        return view($view, array(
            'body_class' => 'ecommerce-application',
            'categories' => $categories,
            'items' => $items,
            'brands' => $brands,
            'currency' => ScopedStock::currency(),
        ));
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
        
        $items = $this->product->getAll();

        foreach ($items as $v) {
		
            $return[] = [
                'value' => $v->code . ' ' .html_entity_decode($v->name),
                'data' => url('shop/' . str_slug($v->name) . '/' . $v->id )
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
		
		if(!isset($item->id))
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
