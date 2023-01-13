<?php

namespace App;

use App\Support\Model\Action;
use App\Support\Scoped\ScopedDocumentFacade as ScopedDocument;

/**
 * Class ActionProduct
 *
 * @package App
 */
class ActionProduct extends BaseModel
{
    use Action;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'action_products';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'action_id',
        'product_id',
        'qty',
        'mpc_discount',
        'vpc_discount',
        'prices',
        'type',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'action_id' => 'integer',
        'product_id' => 'integer',
        'qty' => 'integer',
        'mpc_discount' => 'float',
        'vpc_discount' => 'float',
        'prices' => 'array',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'uid',
        'price',
        'price_discounted',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rProduct()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    
    /**
     * Return list of documents.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sAction()
            ->orderBy('id', 'desc')
            ->sPaginate();
    }
    
    /**
     * @param int $id
     * @return self
     */
    public function getOne($id)
    {
        return $this->sAction()
            ->where($this->table.'.id', $id)
            ->first();
    }
    
    /**
     * @return array
     */
    private function getPrices()
    {
        if ($this->exists) {
            $country_id = $this->prices['country_id'];
            
            if ($country_id == 'bih') {
                return [
                    'mpc' => $this->prices['mpc'],
                    'vpc' => $this->prices['vpc'],
                ];
            } else {
                return [
                    'mpc' => $this->prices['mpc_eur'],
                    'vpc' => $this->prices['vpc_eur'],
                ];
            }
        }
    
        return [
            'mpc' => 0,
            'vpc' => 0,
        ];
    }
    
    /**
     * @return float|int
     */
    public function getPriceAttribute()
    {
        return ScopedDocument::useMpcPrice() ? $this->prices['mpc'] : $this->prices['vpc'];
    }
    
    /**
     * @return float
     */
    public function getPriceDiscountedAttribute()
    {
        if ($this->relationLoaded('rAction')) {
            if ($this->rAction->isDiscount()) {
                $discount = ScopedDocument::useMpcPrice() ? $this->mpc_discount : $this->vpc_discount;
            } else {
                $discount = ScopedDocument::useMpcPrice() ? $this->rAction->total_discount : $this->rAction->subtotal_discount;
                // $discount = 0;
            }
        } else {
            $discount = ScopedDocument::useMpcPrice() ? $this->mpc_discount : $this->vpc_discount;
        }
        
        return calculateDiscount($this->price, ScopedDocument::discount1(), ScopedDocument::discount2(), $discount);
        // return calculateDiscount($this->price, 0, 0, $discount);
    }
}
