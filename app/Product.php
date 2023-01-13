<?php namespace App;

use App\Support\Model\Search;
use App\Support\Model\Status;
use Illuminate\Support\Facades\DB;
use App\Support\Scoped\ScopedContractFacade as ScopedContract;
use App\Support\Scoped\ScopedDocumentFacade as ScopedDocument;

class Product extends BaseModel
{
    use Status, Search;
    
    /**
     * Languange
     *
     * @var string
     */
    public $langId;
    /**
     * Used in Exlude scope
     *
     * @var array
     */
    public $exclude = array();
    /**
     * Used in ProductsIds scope
     *
     * @var array
     */
    public $productIds = null;
    /**
     * Used for filtering products per category
     *
     * @var int
     */
    public $f_category_id = 0;
    /**
     * Used for filtering products per brand_id
     *
     * @var int
     */
    public $f_brand_id = 0;
    /**
     * Used for filtering products per f_badge_id
     *
     * @var int
     */
    public $f_badge_id = null;
    /**
     * Used for filtering products per stocks
     *
     * @var int
     */
    public $stockId = null;
    /**
     * Used for filtering products per price
     *
     * @var int
     */
    public $availableProduct = null;
    /**
     * Sort columns
     *
     * @var string
     */
    public $sort = null;
    /**
     * Sort order modes
     *
     * @var string
     */
    public $sortOrder = null;
    public $limit = 16;
    public $priceCountryId = 'bih';
    public $priceStockId = 2;
    public $clientId = null;
    
    /**
     * @var int
     */
    public $clientPaymentDiscount = 0;
    
    /**
     * @var int
     */
    public $clientDiscount1 = 0;
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = [];
    /**
     * List of fields used for item display
     *
     * @var array
     */
    public $itemFields = array(
        'product_translations.*',
        'products.*',
        'products.id as id',
        'product_translations.id as translation_id',
        'categories.father_id as category_father_id',
        'category_translations.name as category_name',
        'category_translations.path as category_path',
        'category_translations.slug as category_slug',
        'categories.priority as category_priority',
        'brands.name as brand_name',
        'brands.slug as brand_slug',
        'brands.logo as brand_logo',
    );
    /**
     * List of fields used for item display
     *
     * @var array
     */
    public $listFields = array(
        'products.*',
        'product_translations.*',
        'products.id as id',
        'product_translations.id as translation_id',
        'category_translations.name as category_name',
        'category_translations.path as category_path',
        'category_translations.slug as category_slug',
        'categories.priority as category_priority',
        'categories.photo as category_photo',
        'categories.father_id as father_id',
        'brands.name as brand_name',
        'brands.slug as brand_slug',
    );
    protected $guarded = [];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';
    
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'rProductPrices',
        'rProductPrices.rBadge',
        //'rProductStocks',
        'rUnit'
    ];
    
    /**
     * @var integer
     */
    const PROMO_CATEGORY_ID = 125;
    
    /**
     * Class constructor
     *
     * @retrun void
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
        $this->langId = config('app.locale');
    }
    
    /**
     * Return number of products per category
     *
     * @return array Array of product objects
     */
    public static function countProducts()
    {
        $result = array();
        $counts = self::sStatus()->sHighlighted()->sExportToSocial()->sBadge()->groupBy('category_id')
            ->get(array('category_id', DB::raw('count(*) as count')));
        
        foreach ($counts as $id => $count) {
            $result[$count->category_id] = $count->count;
        }
        
        return $result;
    }
    
    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }
    
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
	
    public function rCategory()
    {
        return $this->belongsTo('App\CategoryTranslation', 'category_id', 'category_id')->where('lang_id', $this->langId);
    }
	
    public function rFatherCategory()
    {
        return $this->belongsTo('App\CategoryTranslation', 'father_id', 'category_id')->where('lang_id', $this->langId);
    }
    
    public function translation()
    {
        return $this->hasOne('App\ProductTranslation')->where('lang_id', config('app.locale'));
    }
    
    public function translations()
    {
        return $this->hasMany('App\ProductTranslation');
    }
    
    public function rProductPrices()
    {
        return $this->hasMany('App\ProductPrice');
    }
    
    public function rProductStocks()
    {
        return $this->hasMany('App\ProductStock')->orderBy('created_at', 'desc');
    }
    
    public function rProductDocuments()
    {
        return $this->hasMany('App\rProductDocument');
    }
    
    public function rProductQuantities()
    {
        return $this->hasMany(ProductQuantity::class, 'product_id', 'id')->latest();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rPromoItems()
    {
        return $this->belongsToMany(Product::class, 'promo_products', 'product_id', 'product_code', 'id', 'code')->withPivot(['promo_qty']);
    }
    
    /**
     * Relation: Unit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rUnit()
    {
        return $this->belongsTo(\App\CodeBook::class, 'unit_id', 'code');
    }
    
    /**
     * Filter by Lang
     *
     * @retrun Resource
     */
    public function scopeLang($query)
    {
        return $query->where('product_translations.lang_id', '=', $this->langId)
            ->where('category_translations.lang_id', '=', $this->langId);
    }
    
    /**
     * ProductIds scope - Get products by specific ids
     *
     * @retrun Resource
     */
    public function scopesProductIds($query)
    {
        if (is_array($this->productIds) && isset($this->productIds[0])) {
            return $query->whereIn('products.id', $this->productIds);
        }
    }
    
    /**
     * Exclude scope - exlucde some products based on ids and category ids
     *
     * @retrun Resource
     */
    public function scopesExclude($query)
    {
        if (isset($this->exclude['productIds'])) {
            $query->whereNotIn('products.id', $this->exclude['productIds']);
        }
        
        if (isset($this->exclude['category_ids'])) {
            $query->whereNotIn('products.category_id', $this->exclude['category_ids']);
        }
    }
    
    /**
     * Products with badge
     *
     * @retrun Resource
     */
    public function scopesBadge($query)
    {
        if ($this->f_badge_id != '' && $this->f_badge_id != null && !is_array($this->f_badge_id)) {
            $query->whereHas('rProductPrices', function ($query) {
                $query->where('badge_id', '=', $this->f_badge_id);
            });
        } elseif (is_array($this->f_badge_id) && $this->f_badge_id[0] != '') {
            $query->whereHas('rProductPrices', function ($query) {
                $query->whereIn('badge_id', $this->f_badge_id);
            });
        }
    }
    
    /**
     * Sorting scope
     *
     * @retrun Resource
     */
    public function scopesSort($query)
    {
        $sortOrder = array('asc', 'desc');
        
        if ($this->sortOrder != null && !in_array($this->sortOrder, $sortOrder)) {
            return $query;
        }
        
        if ($this->sort == 'name') {
            return $query->orderBy('product_translations.name', $this->sortOrder);
        } elseif ($this->sort == 'new') {
            return $query->orderBy('products.id', 'desc');
        } elseif ($this->sort == 'relevance') {
            return $query->orderBy('products.relevance', $this->sortOrder);
        } elseif ($this->sort == 'rang') {
            return $query->orderBy('products.rang', 'desc');
        } elseif ($this->sort == 'qty') {
            return $query->sStockQty();
        } elseif ($this->sort == 'most_ordered') {
            return $query->sMostOrdered();
        } elseif ($this->sort == 'never_ordered') {
            return $query->sNeverOrdered();
        } elseif (($this->sort == 'contract') && ScopedContract::hasContract()) {
            return $query->whereIn('products.id', ScopedContract::getProducts()->pluck('product_id')->toArray());
        } else if (is_array($this->productIds) && isset($this->productIds[0])) {
            return $query->orderByRaw(DB::raw("FIND_IN_SET(products.id,'".implode(',', $this->productIds)."')"));
        } else {
            return $query->orderBy('products.rang', 'desc')->orderBy('products.id', 'desc');
        }
    }
    
    /**
     * Client documents scope
     *
     * @retrun Resource
     */
    public function scopesStockId($query)
    {
        if ($this->stockId > 0) {
            $query->whereHas('rProductQuantities', function ($query) {
                $query->where('stock_id', $this->stockId);
            });
        }
        
        return $query;
    }
    
    /**
     * Client documents scope
     *
     * @retrun Resource
     */
    public function scopesClientId($query)
    {
        if ($this->clientId > 0) {
            $query->where('documents.client_id', $this->clientId);
        }
        
        return $query;
    }
    
    /**
     * Order by stock qty scope
     *
     * @retrun Resource
     */
    public function scopesStockQty($query)
    {
        array_push($this->listFields, DB::raw('SUM(product_stocks.qty) as stock'));
        
        $query->leftJoin('product_stocks', 'products.id', '=', 'product_stocks.product_id');
        $query->where('product_stocks.stock_id', $this->priceStockId);
        $query->groupBy('products.id');
        $query->orderBy('stock', $this->sortOrder);
        
        return $query;
    }
    
    /**
     * Most ordered scope
     *
     * @retrun Resource
     */
    public function scopesMostOrdered($query)
    {
        array_push($this->listFields, DB::raw('SUM(document_products.qty) as ordered'));
        
        $query->leftJoin('document_products', 'products.id', '=', 'document_products.product_id');
        $query->leftJoin('documents', 'documents.id', '=', 'document_products.document_id');
        $query->sClientId();
        $query->where('documents.type_id', 'order');
        $query->groupBy('products.id');
        $query->orderBy('ordered', $this->sortOrder);
        
        return $query;
    }
    
    /**
     * Never ordered scope
     *
     * @retrun Resource
     */
    public function scopesNeverOrdered($query)
    {
        $query->whereNotIn('products.id', function ($query) {
            
            $query->select('document_products.product_id')
                ->from('document_products')
                ->join('documents', 'documents.id', '=', 'document_products.document_id')
                ->where('documents.type_id', 'order')
                ->distinct();
            
            if ($this->clientId > 0) {
                $query->where('documents.client_id', $this->clientId);
            }
        });
        
        return $query;
    }
    
    /**
     * Category scope - filter products based on category
     *
     * @retrun Resource
     */
    public function scopesCategory($query)
    {
        if ($this->f_category_id != '' && $this->f_category_id != null && !is_array($this->f_category_id)) {
            return $query->where(function ($query) {
                $query->where('products.category_id', $this->f_category_id)->orWhere('categories.father_id', $this->f_category_id);
            });
        } elseif (is_array($this->f_category_id) && $this->f_category_id[0] != '') {
            return $query->where(function ($query) {
                $query->whereIn('products.category_id', $this->f_category_id)->orWhereIn('categories.father_id', $this->f_category_id);
            });
        }
    }
    
    /**
     * Brand scope - filter products based on brand
     *
     * @retrun Resource
     */
    public function scopesBrand($query)
    {
        if ($this->f_brand_id != '' && $this->f_brand_id != null && !is_array($this->f_brand_id)) {
            return $query->where('products.brand_id', '=', $this->f_brand_id);
        } elseif (is_array($this->f_brand_id) && $this->f_brand_id[0] != '') {
            return $query->whereIn('products.brand_id', (array)$this->f_brand_id);
        }
    }
    
    /**
     * Price scope - Filter products by min and/or max price
     *
     * @retrun Resource
     */
    public function scopesPrice($query)
    {
        if ($this->availableProduct == true) {
            $query->whereHas('rProductPrices', function ($query) {
                $query->where('product_prices.vpc', '>', 0)
                    ->where('product_prices.country_id', $this->priceCountryId);
            });
        }
        
        return $query;
    }
    
    /**
     * Filter by keywords
     *
     * @retrun Resource
     */
    public function scopesSearch($query)
    {
		$this->keywords = e(trim(str_replace(['-', '(', ')'], [' ', ' ', ' ',], $this->keywords)));
		
        if ($this->keywords != null) {
            $query->whereRaw("MATCH(product_translations.name, product_translations.text, product_translations.search) AGAINST(? IN BOOLEAN MODE)", [urldecode($this->keywords) . '*'])->orWhere('products.barcode', 'like', '%' . urldecode($this->keywords) . '%');
        }
        
        return $query;
    }
    
    /**
     * Get data about specific product
     *
     * @param int $id Product Code
     * @return self
     */
    public function getOne($id)
    {
        return self::join('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->lang()
            ->where('products.id', $id)
            ->first($this->itemFields);
    }
    
    /**
     * Return list of products
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return self::relation()->join('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->lang()
            ->sCategory()
            ->sBrand()
            ->sPrice()
            ->sSort()
            ->sExclude()
            ->sProductIds()
            ->sSearch()
            ->sStatus()
            ->sBadge()
            ->sStockId()
            ->sPaginate();
    }
    
    /**
     * Return minimal product price per category or/and brand
     *
     * @return float
     */
    public function getMinPrice()
    {
        return self::sCategory()
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->groupBy('products.id')
            ->sBrand()
            ->sPrice()
            ->sExclude()
            ->sProductIds()
            ->sStatus()
            ->sBadge()
            ->min('price');
    }
    
    /**
     * Return max product price per category or/and brand
     *
     * @return float
     */
    public function getMaxPrice()
    {
        return self::sCategory()
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->groupBy('products.id')
            ->sBrand()
            ->sExclude()
            ->sProductIds()
            ->sBadge()
            ->max('price');
    }
    
    /**
     * Return number of products per brand
     *
     * @return array Array of brand objects
     */
    public function getBrandsPerCategory()
    {
        return self::join('brands', 'brands.id', '=', 'products.brand_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->sCategory()
            ->sPrice()
            ->sExclude()
            ->sProductIds()
            ->sBadge()
            ->groupBy('brand_id')
            ->orderBy('name', 'asc')
            ->get(array('brands.*', DB::raw('count(products.brand_id) as count')));
    }
    
    /**
     * Return number of products per brand
     *
     * @return array Array of brand objects
     */
    public function getSubcategoriesPerCategory()
    {
        return self::join('categories', 'categories.id', '=', 'products.category_id')
            ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->sBrand()
            ->sCategory()
            ->sPrice()
            ->sExclude()
            ->sProductIds()
            ->sBadge()
            ->groupBy('products.category_id')
            ->orderBy('categories.priority', 'asc')
            ->get(array('categories.*', 'category_translations.*', 'categories.id as id', DB::raw('count(products.category_id) as count')));
    }
    
    /**
     * Return list of products
     *
     * @return array Array of product objects
     */
    public function getSitemap()
    {
        return self::join('product_translations', 'products.id', '=', 'product_translations.product_id')->sSort()->get(['link', 'picture', 'name']);
    }
    
    /**
     * Get product price
     *
     * @return float|integer
     */
    public function getPriceAttribute()
    {
        if (ScopedContract::hasContract() && ScopedContract::hasProduct($this->id)) {
            return ScopedContract::getProduct($this->id)->prices;
        }
        
        return $this->rProductPrices->where('country_id', \ScopedStock::priceCountryId())->first();
    }
    
    /**
     * Get product price
     *
     * @return float|int
     */
    public function getPriceDiscountedAttribute()
    {
        $price = (float) ScopedDocument::useMpcPrice() ? $this->price->mpc : $this->price->vpc;
        $discount3 = (float) ScopedDocument::useMpcPrice() ? $this->price->mpc_discount : $this->price->vpc_discount;
        
        if (ScopedContract::hasContract() && ScopedContract::hasProduct($this->id)) {
            $price = (($price > 0)) ? calculateDiscount($price, ScopedContract::getProduct($this->id)->discount) : $price;
        }
        
        if (ScopedDocument::exist()) {
            $discount1 = ScopedDocument::discount1();
            $discount2 = ScopedDocument::discount2();
        } else {
            $discount1 = $this->clientPaymentDiscount;
            $discount2 = $this->clientDiscount1;
        }
        
        $price = ($price > 0) ? calculateDiscount($price, $discount1, $discount2, $discount3) : $price;
        
        // $price = ScopedDocument::withTax() ? $price * ScopedDocument::taxRateValue() : $price;
        
        return $price;
    }
	
    /**
     * Get product price
     *
     * @return float|int
     */
    public function getPriceOldAttribute()
    {
        $price = ScopedDocument::useMpcPrice() ? $this->price->mpc : $this->price->vpc;
    
        // $price = ScopedDocument::useMpcPrice() ? $this->price->mpc_old : $this->price->vpc_old;
        // $price = ScopedDocument::withTax() ? $price * ScopedDocument::taxRateValue() : $price;
        
        return $price;
    }
    
    /**
     * @return bool
     */
    public function getHasDiscountAttribute()
    {
        if ($this->exists) {
            return $this->price_old > $this->price_discounted;
        }
        
        return false;
    }
    
    /**
     * @return float|int
     */
    public function getCascadeDiscountAttribute()
    {
        if ($this->exists) {
            if ($this->has_discount) {
                if (($this->price_old > 0) && ($this->price_discounted > 0)) {
                    return round(100 - (($this->price_discounted * 100) / $this->price_old));
                }
            }
        }
        
        return 0;
    }
    
    /**
     * Get product qty
     *
     * @return int
     */
    public function getQtyAttribute()
    {
        if (ScopedContract::hasContract() && ScopedContract::hasProduct($this->id)) {
            return ScopedContract::getProduct($this->id)->in_stock;
        }
        
        return $this->rProductQuantities->where('stock_id', \ScopedStock::priceStockId())->sum('qty');
    }
    
    /**
     * Get product photo
     *
     * @param string $value
     * @return string
     */
    public function getPhotoAttribute($value)
    {
        return empty($value) ? 'no_photo.jpg' : $value;
    }
    
    /**
     * Set Related value.
     *
     * @param string $value
     * @return string
     */
    public function setRelatedAttribute($value)
    {
        $this->attributes['related'] = is_array($value) && count($value) > 0 ? json_encode($value) : null;
    }
    
    /**
     * get Related value.
     *
     * @param string $value
     * @return string
     */
    public function getRelatedAttribute($value)
    {
        return is_json($value) ? json_decode($value) : null;
    }
    
    /**
     * @return string
     */
    public function getPhotoSmallAttribute()
    {
        if (is_file(public_path(config('picture.product_path').'/small_' . $this->photo))) {
            return asset(config('picture.product_path').'/small_' . $this->photo);
        }
        
        return asset('assets/pictures/product/small_no_photo.jpg');
    }
    
    /**
     * @return string
     */
    public function getPhotoMediumAttribute()
    {
        if (is_file(public_path(config('picture.product_path').'/medium_' . $this->photo))) {
            return asset(config('picture.product_path').'/medium_' . $this->photo);
        }
        
        return asset('assets/pictures/product/medium_no_photo.jpg');
    }
    
    /**
     * @return string
     */
    public function getPhotoBigAttribute()
    {
        if (is_file(public_path(config('picture.product_path').'/big_' . $this->photo))) {
            return asset(config('picture.product_path').'/big_' . $this->photo);
        }
        
        return asset('assets/pictures/product/big_no_photo.jpg');
    }
    
    /**
     * @param null|integer $stockId
     * @return array
     */
    public function getProductQuantities($stockId = null)
    {
        $available_qty = 0;
        $reserved_qty = 0;
        
        if ($this->exists) {
            if ($this->relationLoaded('rProductQuantities')) {
                if (is_null($stockId)) {
                    $available_qty = $this->rProductQuantities->sum('available_qty');
                    $reserved_qty = $this->rProductQuantities->sum('reserved_qty');
                } else {
                    $stock = $this->rProductQuantities->where('stock_id', $stockId)->first();
        
                    if (!is_null($stock)) {
                        $available_qty = $stock->available_qty;
                        $reserved_qty = $stock->reserved_qty;
                    }
                }
            }
        }
    
        return [
            'qty' => $available_qty - $reserved_qty,
            'available' => $available_qty,
            'reserved' => $reserved_qty,
        ];
    }
    
    /**
     * @return bool
     */
    public function getIsPromoProductAttribute()
    {
        if ($this->exists) {
            return $this->category_id == self::PROMO_CATEGORY_ID;
        }
        
        return false;
    }
}
