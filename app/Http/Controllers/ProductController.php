<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\Product\StoreProductTranslationRequest;
use App\Product;
use App\ProductQuantity;
use App\ProductTranslation;
use App\Brand;
use App\Category;
use App\PhotoHelper;
use App\ProductPrice;
use App\ProductStock;
use App\Stock;
use App\Photo;

/**
 * Class ProductController
 *
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    use PhotoHelper;

    /**
     * @var \App\Product
     */
    private $product;

    /**
     * @var \App\ProductTranslation
     */
    private $productTranslation;

    /**
     * @var \App\Category
     */
    private $category;

    /**
     * @var \App\Brand
     */
    private $brand;
	
    /**
     * @var \App\Stock
     */
    private $stock;
	
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
        ProductTranslation $productTranslation,
        Brand $brand,
        Stock $stock,
        Category $category,
		Photo $photo
    ) {
        $this->product = $product;
        $this->productTranslation = $productTranslation;
        $this->category = $category;
        $this->brand = $brand;
        $this->stock = $stock;
        $this->photo = $photo;

        $this->middleware('auth');
        $this->middleware('emptystringstonull');
        $this->middleware('acl:view-product', ['only' => ['index']]);
        $this->middleware('acl:create-product', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-product', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $items = Product::all();

        return view('product.index', array(
            'items' => $items
        ));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $items
	 * @param \Illuminate\Database\Eloquent\Collection $stocks
     * @return \Illuminate\Http\Response
     */
    private function exportToPDF($items, $stocks)
    {	/*
         return view('product.export_pdf', array(
             'items' => $items,
             'stocks' => $stocks,
         ));
		*/
        return \PDF::loadView('product.export_pdf', ['items' => $items, 'stocks' => $stocks])->download('products.pdf');
    }
    
    /**
     * @param \Illuminate\Database\Eloquent\Collection $items
     * @return \Illuminate\Http\Response
     */
    private function exportToExcel($items, $stockId)
    {
        $stocks = Stock::where('status', 'active')->get(['id', 'name'])->filter(function ($stock) use ($stockId) {
            if (is_null($stockId)) {
                return true;
            }
            
            return $stock->id == $stockId;
        })->pluck('name', 'id')->toArray();
        
        // return view('product.export_xls', array(
        //     'items' => $items,
        //     'stocks' => $stocks,
        // ));
        
        return \Excel::create('products', function($excel) use ($items, $stocks) {
            $excel->sheet('Sheet 1', function($sheet) use ($items, $stocks) {
                $sheet->loadView('product.export_xls')->with('items', $items)->with('stocks', $stocks);
            });
        })->download('xls');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->product->langId = 'bs';
        $categories = $this->category->getCategoryTree();

        $brands = $this->brand->getAll();

        return view('product.form')
            ->with('item', $this->product)
            ->with('categories', $categories)
            ->with('brands', $brands)
            ->with('method', 'post')
            ->with('form_url', route('product.store'))
            ->with('form_title', trans('product.actions.create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Product\StoreProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreProductRequest $request, ProductPrice $productPrice)
    {
        $item = request('item');
        $translation = request('translation');

        // Save photo and variations
        $photo = $this->upload('photo', $translation['name'] . '-' . $item['code'], config('picture.product_path'), $request);

        if($photo != null)
        {
            $item['photo'] = basename($photo);
            
            $this->makePhotoThumbs(config('picture.product_path'), $photo, config('picture.product_thumbs'), 0);
        }

        // Save Product
        $product = $this->product->add($item);

        // Save Translation
        $product->translations()->save(new ProductTranslation($translation));

        // Save prices - Removed 07.10.2022.
        // $productPrice->syncItems($product->id, request('prices'));
		
		// Save link and search
        $this->productTranslation->updateLink($product->id, $translation['lang_id']);
		      
        return $this->getStoreJsonResponse($this->product->getOne($product->id), 'product._row', trans('product.notifications.created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id, ProductStock $productStock)
    {
        $item = $this->product->getOne($id);

        $this->category->langId = 'bs';
        $categories = $this->category->getCategoryTree();

        $brands = $this->brand->getAll();

        // $qtyPerStock = $productStock->getProductQtyPerStock($id);
        
        $product_quantities = ProductQuantity::where('product_id', $id)->with(['rStock'])->get();
		
		if(is_array($item->related))
		{
			$this->product->productIds = $item->related;
			$this->product->limit = 20;
			$related = $this->product->getAll()->pluck('name', 'id');
		}
		else
		{
			$related = collect([]);
		}
		
        $this->photo->module = 'gallery';
        $gallery = $this->photo->getPhotos($id);
        
        $promo_products = [];
        if ($item->is_promo_product) {
            $promo_products = $item->rPromoItems()->with(['translation'])->get();
        }

        return view('product.form')
            ->with('method', 'put')
            ->with('form_url', route('product.update', [$id]))
            ->with('form_title', trans('product.actions.edit'))
            ->with('categories', $categories)
            ->with('brands', $brands)
            // ->with('qty_per_stock', $qtyPerStock)
            ->with('related', $related)
            ->with('gallery', $gallery)
            ->with('product_quantities', $product_quantities)
            ->with('promo_products', $promo_products)
            ->with('item', $item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Product\UpdateProductRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateProductRequest $request, ProductPrice $productPrice, $id)
    {
        $item = request('item');
        $translation = request('translation');

        // Save photo and variations
        $photo = $this->upload('photo', $translation['name'] . '-' . $item['code'], config('picture.product_path'), $request);
	
        if($photo != null)
        {
            $item['photo'] = basename($photo);
            
            $this->makePhotoThumbs(config('picture.product_path'), $item['photo'], config('picture.product_thumbs'), 0);
        }

        $product = $this->product->edit($id, $item);

        $this->productTranslation->editTranslation($translation);

        // Save prices - Removed 07.10.2022.
        // $productPrice->syncItems($product->id, request('prices'));

		// Save link and search
        $this->productTranslation->updateLink($product->id, $translation['lang_id']);
		
        return $this->getUpdateJsonResponse($this->product->getOne($product->id), 'product._row', trans('product.notifications.updated'));
    }

    /**
     * Display translate form
     * @param int $productId
     * @param string $langId
     * @return Response
     */
    public function getTranslate($productId, $langId)
    {
        $this->product->langId = $langId;
        $item = $this->product->getOne($productId);

        return view('product.form_translate')
            ->with('method', 'post')
            ->with('form_url', '/product/translate/' . $productId)
            ->with('form_title', trans('product.actions.translate'))
            ->with('item', $item);
    }

    /**
     * Handle a POST request to translate.
     *
     * @param \App\Http\Request\Product\StoreProductTranslationRequest $request
     * @param int $productId
     *
     * @return Response
     */
    public function postTranslate(StoreProductTranslationRequest $request, $productId)
    {
        $product = $this->product->getOne($productId);

        $input = $request->get('translation');

        $data = [
            'product_id' => $productId,
            'lang_id' => $input['lang_id'],
            'name' => $input['name'],
            'text' =>  $input['text']
        ];

        $this->productTranslation->add($data);
		
		// Save link and search
        $this->productTranslation->updateLink($product->id, $input['lang_id']);

        return $this->getUpdateJsonResponse($product, null, trans('product.notifications.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $translation = $this->productTranslation->getOne($id);
        $product = $this->product->getOne($translation->product_id);

        if (is_object($translation))
        {
            // Remove translation
            $this->productTranslation->remove($id);

            // Count remaining translations
            $count = $this->productTranslation->count($translation->product_id);

            // If there is no more translations, remove item
            if ($count == 0)
            {
                $this->product->remove($translation->product_id);
            }

            return $this->getDestroyJsonResponse($product);
        }

        return $this->getDestroyJsonResponse($product, null, 'Proizvod nije obrisan.');
    }
    
    /**
     * Search.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $exclude = explode('.', request('e', ''));
        $country_id = request('c');
        $stock_id = request('s');
        if (userIsEditor()) {
            $stock_id = $this->getUser()->rPerson->stock_id;
        }
        
        $this->product->keywords = request('q');
        $this->product->stockId = $stock_id;
        $items = $this->product->relation(['rProductQuantities', 'rProductPrices'], true)->getAll()->reject(function ($item, $key) use ($exclude) {
            return in_array($item->id, $exclude);
        })->map(function($item) use ($stock_id, $country_id) {
            $quantities = $item->getProductQuantities($stock_id);
            
            if ($country_id) {
                $price = $item->rProductPrices->where('country_id', $country_id)->first();
                
                if (!is_null($price)) {
                    $vpc = $price->vpc;
                    $mpc = $price->mpc;
                }
            }
            
            return [
                'id' => $item->id,
                'text' => $item->name,
                'disabled' => false,
                'qty' => $quantities['qty'],
                'available' => $quantities['available'],
                'reserved' => $quantities['reserved'],
                'prices' => [
                    'vpc' => format_price(isset($vpc) ? $vpc : 0, 2),
                    'mpc' => format_price(isset($mpc) ? $mpc : 0, 2),
                ],
            ];
        })->values()->toArray();
    
        return response()->json([
            'items' => $items,
            'total_count' => count($items),
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
