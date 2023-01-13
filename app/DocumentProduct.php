<?php

namespace App;

use App\Support\Model\Contract;
use App\Support\Model\IncludeExclude;
use App\Support\Model\Status;

/**
 * Class DocumentProduct
 *
 * @package App
 */
class DocumentProduct extends BaseModel
{
    use IncludeExclude, Contract, Status;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'document_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'contract_id',
        'document_id',
        'product_id',
        'contract_discount',
        'code',
        'luceed_uid',
        'barcode',
        'name',
        'unit_id',
        'mpc',
        'mpc_discount',
        'mpc_discounted',
        'vpc',
        'vpc_discount',
        'vpc_discounted',
        'loyalty_points',
        'qty',
        'total',
        'total_discounted',
        'subtotal',
        'subtotal_discounted',
        'discount1',
        'discount2',
        'discount3',
        'total_loyalty_points',
        'type',
        'promo_children',
        'processed_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'client_id' => 'integer',
        'contract_id' => 'integer',
        'document_id' => 'integer',
        'product_id' => 'integer',
        'contract_discount' => 'float',
        'code' => 'string',
        'barcode' => 'string',
        'name' => 'string',
        'unit_id' => 'string',
        'mpc' => 'float',
        'mpc_discount' => 'float',
        'mpc_discounted' => 'float',
        'vpc' => 'float',
        'vpc_discount' => 'float',
        'vpc_discounted' => 'float',
        'loyalty_points' => 'integer',
        'qty' => 'integer',
        'total' => 'float',
        'total_discounted' => 'float',
        'subtotal' => 'float',
        'subtotal_discounted' => 'float',
        'total_value' => 'float',
        'total_discounted_value' => 'float',
        'price' => 'float',
        'price_discounted' => 'float',
        'discount1' => 'float',
        'discount2' => 'float',
        'discount3' => 'float',
        'total_loyalty_points' => 'integer',
        'processed_at' => 'datetime',
        'promo_children' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = [
        'uid',
        'total_value',
        'total_discounted_value',
        'price',
        'price_discounted',
        'fiscal_net_price',
        'fiscal_net_discounted_price',
        'fiscal_gross_price',
        'fiscal_discounted_price',
        'fiscal_discount_percent',
    ];

    /**
     * Rows per page / query.
     *
     * @var int
     */
    public $limit = null;

    /**
     * @var null|string|array
     */
    public $typeId = 'regular';

    /**
     * @var null|boolean
     */
    public $gratisProcessed = null;

    /**
     * @var null|string|array
     */
    public $stockId = null;

    /**
     * @var null|string
     */
    public $startDate = null;

    /**
     * @var null|string
     */
    public $endDate = null;

    /**
     * DocumentProduct belongs to one document
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rDocument()
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }

    /**
     * DocumentProduct belongs to one client
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rClient()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    /**
     * DocumentProduct belongs to one product
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rProduct()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Relation: Unit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rUnit()
    {
        return $this->belongsTo(CodeBook::class, 'unit_id', 'code');
    }

    /**
     * Return list of ordered products.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sIncludeIds()
            ->sType()
            ->sGratisProcessed()
            ->sDocument()
            ->sOrder()
            ->sPaginate();
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesType($query)
    {
        if (is_array($this->typeId) && isset($this->typeId[0])) {
            $query->whereIn('type', $this->typeId);
        } else if (!is_null($this->typeId) && $this->typeId) {
            $query->where('type', $this->typeId);
        }

        return $query;
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesGratisProcessed($query)
    {
        if ($this->gratisProcessed === true) {
            $query->whereNotNull('processed_at');
        } else if ($this->gratisProcessed === false) {
            $query->whereNull('processed_at');
        }

        return $query;
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesDocument($query)
    {
        if (!is_null($this->stockId) || (!is_null($this->startDate) && !is_null($this->endDate)) || (!is_null($this->statusId))) {
            $query->whereHas('rDocument', function ($query) {
                if (!is_null($this->stockId)) {
                    $query->where('stock_id', $this->stockId);
                }

                if (!is_null($this->startDate) && !is_null($this->endDate)) {
                    $query->where('date_of_order', '>=', $this->startDate)->where('date_of_order', '<=', $this->endDate);
                }

                if (is_array($this->statusId) && isset($this->statusId[0])) {
                    $query->whereIn('status', $this->statusId);
                } else if (!is_null($this->statusId) && $this->statusId) {
                    $query->where('status', $this->statusId);
                }
            });
        }

        return $query;
    }

    /**
     * @param integer $id
     * @param string $type
     * @return float
     */
    public function productSubTotal($id, $type = 'regular')
    {
        return self::where('document_id', $id)->where('type', $type)->sum('subtotal');
    }

    /**
     * @param integer $id
     * @param string $type
     * @return float
     */
    public function productSubTotalDiscounted($id, $type = 'regular')
    {
        return self::where('document_id', $id)->where('type', $type)->sum('subtotal_discounted');
    }

    /**
     * @param integer $id
     * @param string $type
     * @return float
     */
    public function productTotal($id, $type = 'regular')
    {
        return self::where('document_id', $id)->where('type', $type)->sum('total');
    }

    /**
     * @param integer $id
     * @param string $type
     * @return float
     */
    public function productTotalDiscounted($id, $type = 'regular')
    {
        return self::where('document_id', $id)->where('type', $type)->sum('total_discounted');
    }

    /**
     * @param integer $productId
     * @param integer $documentId
     * @return self
     */
    public function getOneByProdcutIdAndDocumentId($productId, $documentId, $type = 'regular')
    {
        return self::where('product_id', $productId)->where('document_id', $documentId)->where('type', $type)->first();
    }

    /**
     * @param integer $id
     * @param integer $quantity
     * @return mixed
     */
    public function incrementQty($id, $quantity)
    {
        return $this->where('id', $id)->increment('qty',  $quantity);
    }

    /**
     * @param integer $id
     * @param integer $quantity
     * @param float $vpc
     * @return mixed
     */
    public function incrementTotal($id, $quantity, $vpc)
    {
        return $this->where('id', $id)->increment('total',  $quantity * $vpc);
    }

    /**
     * @param array $data
     * @param array $exclude
     * @param array $append
     * @return array
     */
    public static function prepareForCopy(array $data, array $exclude = [], array $append = [])
    {
        $fillable = (new static())->getFillable();

        foreach ($data as $key => $value) {
            if (in_array($key, $exclude) || !in_array($key, $fillable)) {
                unset($data[$key]);
            }
        }

        return array_merge($data, $append);
    }

    /**
     * @return float
     */
    public function getTotalValueAttribute()
    {
        $total = $this->rDocument->useMpcPrice() ? $this->total : $this->subtotal;

        // if ($this->rDocument->isCashPayment() || $this->rDocument->isCash()) {
        //     $total = $total * $this->rDocument->tax_rate_value;
        // }

        return $total;
    }

    /**
     * @return float
     */
    public function getTotalDiscountedValueAttribute()
    {
        $total = $this->rDocument->useMpcPrice() ? $this->total_discounted : $this->subtotal_discounted;

        // if ($this->rDocument->isCashPayment() || $this->rDocument->isCash()) {
        //     $total = $total * $this->rDocument->tax_rate_value;
        // }

        return $total;
    }

    /**
     * @return float
     */
    public function getPriceAttribute()
    {
        return $this->rDocument->useMpcPrice() ? $this->mpc : $this->vpc;
    }

    /**
     * @return float
     */
    public function getPriceDiscountedAttribute()
    {
        return $this->rDocument->useMpcPrice() ? $this->mpc_discounted : $this->vpc_discounted;
    }

    /**
     * @return float|int|mixed
     */
    public function getFiscalNetPriceAttribute()
    {
        if ($this->rDocument->useMpcPrice()) {
            return round(getPriceWithoutVat($this->mpc, $this->rDocument->tax_rate), 3);
        }

        return round($this->vpc, 3);
    }

    /**
     * @return float|int|mixed
     */
    public function getFiscalNetDiscountedPriceAttribute()
    {
        if ($this->rDocument->useMpcPrice()) {
            return round(getPriceWithoutVat($this->mpc_discounted, $this->rDocument->tax_rate), 3);
        }

        return round($this->vpc_discounted, 3);
    }

    /**
     * @return float|int|mixed
     */
    public function getFiscalGrossPriceAttribute()
    {
        return round(getPriceWithVat($this->fiscal_net_price, $this->rDocument->tax_rate), 3);
    }

    /**
     * @return float|int|mixed
     */
    public function getFiscalDiscountedPriceAttribute()
    {
        if ($this->rDocument->useMpcPrice()) {
            return round($this->mpc_discounted, 3);
        }
        
        switch ($this->id) {
            case 136271 :
            case 136557 :
            case 136475 :
            case 123839 :
            case 134151 :
            case 135061 :
            case 128768 :
                return 0.7605;
                break;
        }

        return round(getPriceWithVat($this->vpc_discounted, $this->rDocument->tax_rate), 3);
    }

    /**
     * @return float|int|mixed
     */
    public function getFiscalDiscountPercentAttribute()
    {
        if ($this->rDocument->useMpcPrice()) {
            return calculateDiscountPercent($this->mpc_discounted, $this->mpc, 3);
        }

        return calculateDiscountPercent($this->vpc_discounted, $this->vpc, 3);
    }

    /**
     * @return bool|null
     */
    public function getIsProcessedAttribute()
    {
        if ($this->exists) {
            return !is_null($this->processed_at);
        }

        return null;
    }
    
    /**
     * @return bool
     */
    public function getIsPromoProductAttribute()
    {
        if ($this->exists) {
            return isset($this->promo_children);
        }
        
        return false;
    }
}
