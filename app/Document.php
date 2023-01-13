<?php

namespace App;

use App\Notifications\Document\TrackingCodeNotification;
use App\Support\Model\Action;
use App\Support\Model\Client;
use App\Support\Model\Country;
use App\Support\Model\Currency;
use App\Support\Model\IncludeExclude;
use App\Support\Model\PaymentType;
use App\Support\Model\PublicHashHelper;
use App\Support\Model\Search;
use App\Support\Model\Status;
use App\Support\Model\Stock;
use App\Support\Model\Type;
use App\Support\Model\Log;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

/**
 * Class Document
 *
 * @package App
 */
class Document extends BaseModel
{
	use IncludeExclude, Status, Type, Client, Stock, Currency, Country, Action, PublicHashHelper, Log, Notifiable, Search, PaymentType;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'documents';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'created_by',
        'client_id',
        'stock_id',
        'action_id',
        'action_qty',
        'buyer_data',
        'shipping_data',
        'type_id',
        'status',
        'internal_status',
        'payment_type',
        'payment_period',
        'delivery_type',
        'delivery_cost',
        'delivery_cost_fixed',
        'delivery_date',
        'subtotal',
        'total',
        'parent_subtotal',
        'currency',
        'date_of_order',
        'date_of_warehouse',
        'date_of_processing',
        'date_of_delivery',
        'date_of_payment',
        'subtotal_discounted',
        'total_discounted',
        'payment_discount',
        'discount_value1',
        'discount_value2',
        'note',
        'tax_rate',
        'package_number',
        'weight',
        'fiscal_receipt_no',
        'fiscal_receipt_datetime',
        'fiscal_receipt_amount',
        'fiscal_receipt_void_no',
        'fiscal_receipt_void_datetime',
        'fiscal_receipt_void_amount',
		'date_of_sync',
		'sync_status',
		'is_payed',
		'order_id',
		'payed_at',
		'luceed_uid',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'parent_id' => 'integer',
        'created_by' => 'integer',
        'client_id' => 'integer',
        'stock_id' => 'integer',
        'action_id' => 'integer',
        'action_qty' => 'integer',
        'buyer_data' => 'array',
        'shipping_data' => 'array',
        'type_id' => 'string',
        'status' => 'string',
        'sync_status' => 'string',
        'internal_status' => 'string',
        'payment_type' => 'string',
        'payment_period' => 'string',
        'delivery_type' => 'string',
        'delivery_cost' => 'float',
        'delivery_cost_fixed' => 'boolean',
        'delivery_date' => 'date',
        'subtotal' => 'float',
        'total' => 'float',
        'parent_subtotal' => 'float',
        'currency' => 'string',
        'date_of_order' => 'date',
        'date_of_warehouse' => 'date',
        'date_of_processing' => 'date',
        'date_of_delivery' => 'date',
        'date_of_payment' => 'date',
        'date_of_sync' => 'datetime',
        'subtotal_discounted' => 'float',
        'total_discounted' => 'float',
        'payment_discount' => 'float',
        'discount_value1' => 'float',
        'discount_value2' => 'float',
        'note' => 'string',
        'package_number' => 'string',
        'weight' => 'string',
        'fiscal_receipt_no' => 'string',
        'fiscal_receipt_datetime' => 'datetime',
        'fiscal_receipt_amount' => 'decimal(18,2)',
        'fiscal_receipt_void_no' => 'string',
        'fiscal_receipt_void_datetime' => 'datetime',
        'fiscal_receipt_void_amount' => 'decimal(18,2)',
        'is_payed' => 'boolean',
        'order_id' => 'integer',
        'payed_at' => 'datetime',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = [
        'uid',
        'full_name',
        'payment_period_in_days',
        'discount1',
        'discount2',
        'total_value',
        'total_discounted_value',
        'dashboard_total_value',
    ];
    
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $relationWith = [
        'rClient.rSalesmanPerson',
        'rDocumentProduct',
    ];
    
    /**
     * @var null|int|array
     */
    public $createdBy = null;
    
    /**
     * @var null|int|array
     */
    public $parentId = null;
    
    /**
     * @var null|string|array
     */
    public $dateOfOrder = null;
    
    /**
     * @var null|string
     */
    public $startDate = null;
    
    /**
     * @var null|string
     */
    public $endDate = null;
    
    /**
     * @var null
     */
    public $fiscalReceiptFrom = null;
    public $fiscalReceiptTo = null;
    
    /**
     * @var null|string|array
     */
    public $deliveryType = null;
    
    /**
     * @var bool
     */
    public $onlyActionDocuments = false;
	
    /**
     * @var null|string
     */
    public $syncStatus = null;
    
    /**
     * @var null|string
     */
    public $createdAt = null;
    
    /**
     * @var null|string
     */
    public $deliveryDate = null;
    
    /**
     * @var null|boolean
     */
    public $isPayed = null;
    
    /**
     * @var null|integer
     */
    public $searchDocumentId = null;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rParent()
    {
        return $this->belongsTo(Document::class, 'parent_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rChild()
    {
        return $this->hasOne(Document::class, 'parent_id', 'id');
    }

    /**
     * Relation: rDocumentProduct.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rDocumentProduct()
    {
        return $this->hasMany(DocumentProduct::class)->where('type', 'regular');
    }
    
    /**
     * Relation: rDocumentGratisProducts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rDocumentGratisProducts()
    {
        return $this->hasMany(DocumentProduct::class)->where('type', 'gratis');
    }
    
    /**
     * Relation: rDocumentProductAll.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rDocumentProductAll()
    {
        return $this->hasMany(DocumentProduct::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rCreatedBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    
    /**
     * Relation: rDocumentChanges.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rDocumentChanges()
    {
        return $this->hasMany(DocumentChange::class, 'document_id', 'id');
    }

    /**
     * Relation: Sync status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rSyncStatus()
    {
        return $this->belongsTo(\App\CodeBook::class, 'sync_status', 'code')->withDefault([
            'code' => 'for_sync',
            'name' => 'Za sinhronizaciju',
            'background_color' => '#ddd',
            'color' => '#000'
        ]);
    }
    
    /**
     * Relation: Delivery.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rDeliveryType()
    {
        return $this->belongsTo(\App\CodeBook::class, 'delivery_type', 'code');
    }
	
    /**
     * Relation: rDocumentLog.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rDocumentLog()
    {
        return $this->hasMany(DocumentLog::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rExpressPost()
    {
        return $this->hasOne(DocumentExpressPost::class, 'document_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rTakeover()
    {
        return $this->hasOne(DocumentTakeover::class, 'document_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rExpressPostEvents()
    {
        return $this->hasMany(ExpressPostEvent::class, 'document_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rPaymentItem()
    {
        return $this->hasOne(PaymentItem::class, 'document_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rOrder()
    {
        return $this->hasOne(Order::class, 'document_id', 'id');
    }
    
    /**
     * Remove item from table.
     *
     * @param int $id
     * @return boolean
     */
    public function remove($id)
    {
        // $this->rDocumentProduct()->delete();
        
        return parent::destroy($id);
    }
    
    /**
     * Return list of documents.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sStatus()
            ->sType()
            ->sClient()
            ->sCreatedBy()
            ->sParent()
            ->sDates()
            ->sStock()
            ->sStockCountry()
            ->sIncludeIds()
            ->sAction()
            ->sActionDocument()
            ->sDeliveryType()
			->sSyncStatus()
			->sIsPayed()
			->sSearch()
            ->orderBy('date_of_order', 'desc')
            ->orderBy('id', 'desc')
            ->sPaginate();
    }
    
    /**
     * @return self
     */
    public function getScoped()
    {
        $this->limit = null;
        $this->statusId = 'draft';
        
        return $this->getAll()->first();
    }
    
    /**
     * @param int $id
     * @return self
     */
    public function getOne($id)
    {
        return $this->sStatus()
            ->sType()
            ->sClient()
            ->sCreatedBy()
            ->sParent()
            ->sStock()
            ->where($this->table.'.id', $id)
            ->first();
    }
    
    /**
     * @return self
     */
    public function updateTotals()
    {
        $query = DB::table('document_products')
            ->selectRaw('SUM(subtotal) AS sum_subtotal, SUM(subtotal_discounted) AS sum_subtotal_discounted, SUM(total) AS sum_total, SUM(total_discounted) AS sum_total_discounted')
            ->where('type', 'regular')
            ->where('document_id', $this->id)
            ->first();
        
        // $subtotal = (new DocumentProduct())->productSubTotal($this->id);
        // $total = (new DocumentProduct())->productTotal($this->id);

        $this->update([
            'subtotal' => $query->sum_subtotal,
            // 'subtotal_discounted' => calculateDiscount($subtotal, $this->payment_discount, $this->discount_value1, $this->discount_value2),
            'subtotal_discounted' => $query->sum_subtotal_discounted,
            'total' => $query->sum_total,
            // 'total_discounted' => calculateDiscount($total, $this->payment_discount, $this->discount_value1, $this->discount_value2),
            'total_discounted' => $query->sum_total_discounted,
        ]);
        
        return $this;
    }
    
    /**
     * Scope: Created by.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesCreatedBy($query)
    {
        if (is_array($this->createdBy)) {
            $query->whereIn($this->table.'.created_by', empty($this->createdBy) ? [''] : $this->createdBy);
        } else if (is_numeric($this->createdBy) && $this->createdBy) {
            $query->where($this->table.'.created_by', $this->createdBy);
        }
        
        return $query;
    }
    
    /**
     * Scope: Parent.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesParent($query)
    {
        if (is_array($this->parentId)) {
            $query->whereIn($this->table.'.parent_id', empty($this->parentId) ? [''] : $this->parentId);
        } else if (is_numeric($this->parentId) && $this->parentId) {
            $query->where($this->table.'.parent_id', $this->parentId);
        }
        
        return $query;
    }
    
    /**
     * Scope: Dates.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesDates($query)
    {
        if (is_array($this->dateOfOrder)) {
            $query->whereIn($this->table.'.date_of_order', empty($this->dateOfOrder) ? [''] : $this->dateOfOrder);
        } else if (is_string($this->dateOfOrder) && $this->dateOfOrder) {
            $query->where($this->table.'.date_of_order', $this->dateOfOrder);
        }
        
        if (!is_null($this->startDate) && !is_null($this->endDate)) {
            $query->where($this->table.'.date_of_order', '>=', $this->startDate)->where($this->table.'.date_of_order', '<=', $this->endDate);
        }

        if (!is_null($this->fiscalReceiptFrom) && !is_null($this->fiscalReceiptTo)) {
            $query->where($this->table.'.fiscal_receipt_datetime', '>=', $this->fiscalReceiptFrom)->where($this->table.'.fiscal_receipt_datetime', '<', $this->fiscalReceiptTo);
        }
        
        if (!is_null($this->createdAt)) {
            $query->where($this->table.'.created_at', '>=', $this->createdAt);
        }
        
        if (!is_null($this->deliveryDate)) {
            $query->where(function($query) {
                $query->whereNull($this->table.'.delivery_date');
                
                $query->orWhere(function ($query) {
                    $query->whereNotNull($this->table.'.delivery_date')->where($this->table.'.delivery_date', '<=', $this->deliveryDate);
                });
            });
        }
        
        return $query;
    }
    
    /**
     * Scope: Action documents.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesActionDocument($query)
    {
        if ($this->onlyActionDocuments === true) {
            $query->whereNotNull($this->table.'.action_id');
        }
        
        return $query;
    }
	
	
    /**
     * Scope: syncStatus
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesSyncStatus($query)
    {
        if ($this->syncStatus == 'for_sync') {
           $query->where(function ($query) {
				$query->whereNull($this->table.'.sync_status')
				->orWhere($this->table.'.sync_status', 'failed');
           })->whereNotNull('fiscal_receipt_no');
        }
		elseif ($this->syncStatus != null) {
            $query->where($this->table.'.sync_status', $this->syncStatus);
		}
        
        return $query;
    }
    
    /**
     * Scope: Type.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesDeliveryType($query)
    {
        if (is_array($this->deliveryType) && isset($this->deliveryType[0])) {
            $query->whereIn('delivery_type', $this->deliveryType);
        } else if (!is_null($this->deliveryType) && $this->deliveryType) {
            $query->where('delivery_type', $this->deliveryType);
        }
        
        return $query;
    }
    
    /**
     * Scope: Search.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesSearch($query)
    {
        if($this->isKeywordsValid()) {
            return $query->where(function ($query) {
                $query->where($this->searchBy(), 'like', '%'.$this->keywords.'%');
                
                foreach(explode(' ', $this->keywords) as $keyword){
                    $query->orWhere($this->searchBy(), 'like', '%'.$keyword.'%');
                }
            });
        }
        
        if (is_numeric($this->searchDocumentId)) {
            $query->where('id', $this->searchDocumentId);
        }
    }
    
    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesIsPayed($query)
    {
        if ($this->isPayed === true) {
            $query->where('is_payed', 1);
        } else if ($this->isPayed === false) {
            $query->where('is_payed', 0);
        }
        
        return $query;
    }
    
    /**
     * Search by.
     *
     * @return string
     */
    protected function searchBy()
    {
        return 'shipping_data';
    }
    
    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        if ($this->exists && $this->relationLoaded('rClient')) {
            return $this->rType->name.' #'.$this->id.' '.$this->rClient->full_name;
        }
        
        return '';
    }
    
    /**
     * @return int
     * @deprecated
     */
    public function calcDeliveryCost()
    {
        if ($this->delivery_type == 'paid_delivery') {
            if (!is_null($this->rStock)) {
                return ($this->rStock->country_id == 'bih') ? 9 : 500;
            }
        }
    
        return 0;
    }
    
    /**
     * @return bool
     */
    public function isOrder()
    {
        if ($this->exists && ($this->type_id == 'order')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function isPreOrder()
    {
        if ($this->exists && ($this->type_id == 'preorder')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function isOffer()
    {
        if ($this->exists && ($this->type_id == 'offer')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function isReturn()
    {
        if ($this->exists && ($this->type_id == 'return')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function isReversal()
    {
        if ($this->exists && ($this->type_id == 'reversal')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function isCash()
    {
        if ($this->exists && ($this->type_id == 'cash')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @param array $data
     * @param array $append
     * @return mixed
     */
    public static function prepareForCopy(array $data, array $append = [])
    {
        $fillable = (new static())->getFillable();
        
        foreach ($data as $key => $value) {
            if (!in_array($key, $fillable)) {
                unset($data[$key]);
            }
        }
        
        return array_merge($data, $append);
    }
    
    /**
     * @return float|int
     */
    public function getDifferenceFromParent()
    {
        if (is_null($this->rParent)) {
            return 0;
        }
        
        if ($this->rParent->total_value > 0) {
            return $this->total_value / $this->rParent->total_value;
        }
        
        return 0;
    }
    
    /**
     * @return string
     */
    public function getDifferenceIcon()
    {
        $diff = $this->getDifferenceFromParent();
        
        if ($diff < 1) {
            return 'icon-arrow-down-right';
        } elseif ($diff > 1) {
            return 'icon-arrow-up-right';
        } else {
            return 'icon-minus-square';
        }
    }
    
    /**
     * @return string
     */
    public function getDifferenceColor()
    {
        $diff = $this->getDifferenceFromParent();
    
        if ($diff < 1) {
            return 'danger';
        } elseif ($diff > 1) {
            return 'success';
        } else {
            return 'success';
        }
    }
    
    /**
     * @return int
     */
    public function getPaymentPeriodInDaysAttribute()
    {
        if ($this->exists) {
            return (int) substr($this->payment_period, 0, 2);
        }
        
        return 0;
    }
    
    /**
     * @return int|float
     */
    public function getDiscount1Attribute()
    {
        if ($this->exists) {
            return $this->payment_discount;
        }
        
        return 0;
    }
    
    /**
     * @return int|float
     */
    public function getDiscount2Attribute()
    {
        if ($this->exists) {
            return $this->discount_value1;
        }
        
        return 0;
    }
    
    /**
     * @return int|float
     */
    public function getDiscount3Attribute()
    {
        if ($this->exists) {
            return $this->discount_value2;
        }
        
        return 0;
    }
    
    /**
     * @return bool
     */
    public function getHasDiscountAttribute()
    {
        return ($this->discount1 > 0) || ($this->discount2 > 0) || ($this->discount3 > 0);
    }
    
    /**
     * @return bool
     */
    public function isCashPayment()
    {
        return $this->payment_type == 'cash_payment';
    }
    
    /**
     * @return float|int
     */
    public function getTaxRateValueAttribute()
    {
        if ($this->exists) {
            return 1 + ($this->tax_rate / 100);
        }
        
        return 1;
    }
    
    /**
     * @param string $type
     * @return float
     */
    private function sumDocumentProductPrices($type)
    {
        return $this->rDocumentProduct->sum(function($product) use ($type) {
            $price = $product->{$type};
    
            if ($this->isCashPayment() || ($this->type_id == 'cash')) {
                $price = $price * $this->tax_rate_value;
            }
            
            return round($price, 2) * $product->qty;
        });
    }
    
    /**
     * @return int|float
     */
    public function getTotalValueAttribute()
    {
        if ($this->exists) {
            return $this->useMpcPrice() ? $this->total : $this->subtotal;
        }
        
        return 0;
    }
    
    /**
     * @return int|float
     */
    public function getTotalDiscountedValueAttribute()
    {
        if ($this->exists) {
            return $this->useMpcPrice() ? $this->total_discounted : $this->subtotal_discounted;
        }
        
        return 0;
    }
    
    /**
     * @return int|float
     */
    public function getDashboardTotalValueAttribute()
    {
        if ($this->exists) {
            if ($this->useMpcPrice()) {
                $tax = 1 + round($this->tax_rate / 100, 2);
                
                return round($this->total_discounted / $tax, 3);
            }
            
            return $this->subtotal_discounted;
        }
        
        return 0;
    }
    
    /**
     * @return bool
     */
    public function useMpcPrice()
    {
        if ($this->exists) {
            return $this->isCashPayment() || $this->isCash();
        }
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function isAction()
    {
        if ($this->exists) {
            return !is_null($this->rAction);
        }
        
        return false;
    }
    
    /**
     * @return float|int|mixed
     */
    public function getFiscalNetPriceAttribute()
    {
        if ($this->useMpcPrice()) {
            return round(getPriceWithoutVat($this->total, $this->tax_rate), 2);
        }
        
        return round($this->subtotal, 2);
    }
    
    /**
     * @return float|int|mixed
     */
    public function getFiscalGrossPriceAttribute()
    {
        return round(getPriceWithVat($this->fiscal_net_price, $this->tax_rate), 2);
    }
    
    /**
     * @return float|int|mixed
     */
    public function getFiscalDiscountedPriceAttribute()
    {
        if ($this->useMpcPrice()) {
            return round($this->total_discounted, 2);
        }
        
        return round(getPriceWithVat(round($this->subtotal_discounted, 2), $this->tax_rate), 2);
    }
    
    /**
     * @return float
     */
    public function getFiscalVatAttribute()
    {
        $price = $this->subtotal_discounted;
        if ($this->useMpcPrice()) {
            $price = getPriceWithoutVat($this->total_discounted, $this->tax_rate);
        }
        
        return round(getVatFromPrice($price, $this->tax_rate), 2);
    }
    
    /**
     * @return float|int|mixed
     */
    public function getFiscalDiscountPercentAttribute()
    {
        if ($this->useMpcPrice()) {
            return calculateDiscountPercent($this->total_discounted, $this->total, 2);
        }
        
        return calculateDiscountPercent($this->subtotal_discounted, $this->subtotal, 2);
    }
    
    /**
     * @return float|int|mixed
     */
    public function getFiscalDeliveryNetPriceAttribute()
    {
        // if ($this->useMpcPrice()) {
        //     return round(getPriceWithoutVat($this->delivery_cost, $this->tax_rate), 2);
        // }
        
        return round($this->delivery_cost, 2);
    }
    
    /**
     * @return float|int|mixed
     */
    public function getFiscalDeliveryGrossPriceAttribute()
    {
        // if ($this->useMpcPrice()) {
        //     return round($this->delivery_cost, 2);
        // }
        
        return round(getPriceWithVat($this->delivery_cost, $this->tax_rate), 2);
    }
    
    /**
     * @return float|int|mixed
     */
    public function getFiscalDeliveryPriceAttribute()
    {
        if ($this->useMpcPrice()) {
            $delivery_price = $this->delivery_cost;
        } else {
            $delivery_price = getPriceWithVat($this->delivery_cost, $this->tax_rate);
        }
        
        // $delivery_price = clientTypeDeliveryCost($this->delivery_cost, $this->rClient->type_id, $this->tax_rate);
        
        return round($delivery_price, 2);
    }
    
    /**
     * @return string
     */
    public function getPublicUrlAttribute()
    {
        if ($this->exists) {
            return route('track.document.show', ['hash' => $this->public_hash, 'id' => $this->id]);
        }
        
        return url('/');
    }
    
    /**
     * @return false
     */
    public function canBeReversed()
    {
        if ($this->exists) {
            if (userIsAdmin()) {
                if (in_array($this->status, ['invoiced', 'express_post', 'delivered', 'returned'])) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function canBeCanceled()
    {
        if ($this->exists) {
            if ($this->isOrder()) {
                if (userIsAdmin() || userIsEditor()) {
                    if (in_array($this->status, ['for_invoicing', 'in_warehouse'])) {
                        return true;
                    }
                }
    
                if (userIsSalesman() || userIsWarehouse()) {
                    if (in_array($this->status, ['in_warehouse'])) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    /**
     * @return void
     */
    public function sendTrackingCodeNotification()
    {
        $this->notify(new TrackingCodeNotification());
    }
}
