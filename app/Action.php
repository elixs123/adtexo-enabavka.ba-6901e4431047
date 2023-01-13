<?php

namespace App;

use App\Support\Model\Search;
use App\Support\Model\Status;
use App\Support\Model\Stock;
use App\Support\Model\Type;
use Illuminate\Support\Facades\DB;

/**
 * Class Action
 *
 * @package App
 */
class Action extends BaseModel
{
    use Type, Search, Status, Stock;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'actions';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type_id',
        'started_at',
        'finished_at',
        'stock_type',
        'qty',
        'bought',
        'reserved',
        'stock_id',
        'subtotal',
        'subtotal_discount',
        'subtotal_discounted',
        'total',
        'total_discount',
        'total_discounted',
        'photo',
        'presentation',
        'technical_sheet',
        'free_delivery',
        'status',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'qty' => 'integer',
        'bought' => 'integer',
        'reserved' => 'integer',
        'stock_id' => 'integer',
        'subtotal' => 'float',
        'subtotal_discounted' => 'float',
        'total' => 'float',
        'total_discounted' => 'float',
        'free_delivery' => 'boolean',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'uid',
        'available_qty',
    ];
    
    /**
     * @var bool
     */
    public $published = false;
    
    /**
     * @var null|string
     */
    public $startDate = null;
    
    /**
     * @var null|string
     */
    public $endDate = null;
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rProducts()
    {
        return $this->hasMany(ActionProduct::class, 'action_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rActionProducts()
    {
        return $this->hasMany(ActionProduct::class, 'action_id', 'id')->where('type', 'action');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rGratisProducts()
    {
        return $this->hasMany(ActionProduct::class, 'action_id', 'id')->where('type', 'gratis');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rRoles()
    {
        return $this->belongsToMany(Role::class, 'action_roles', 'action_id', 'role_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rClients()
    {
        return $this->belongsToMany(Client::class, 'client_actions', 'action_id', 'client_id');
    }
    
    /**
     * Scope: Stock.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param bool $published
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopePublished($query, $published)
    {
        if ($published === true) {
            $query->where('started_at', '<', now()->toDateTimeString());
            
            $query->where('finished_at', '>', now()->toDateTimeString());
        }
        
        return $query;
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
            ->sStock()
            ->published($this->published)
            ->sDates()
            ->sSearch()
            ->orderBy('id', 'desc')
            ->sPaginate();
    }
    
    /**
     * @param int $id
     * @return self
     */
    public function getOne($id)
    {
        return $this->sStatus()
            ->sType()
            ->sStock()
            ->published($this->published)
            ->where($this->table.'.id', $id)
            ->first();
    }
    
    /**
     * Scope: Dates.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesDates($query)
    {
        if (!is_null($this->startDate) && !is_null($this->endDate)) {
            $query->where(function($query) {
                $query->where('started_at', '>=', $this->startDate)->where('started_at', '<=', $this->endDate);
            });
            
            $query->orWhere(function($query) {
                $query->where('finished_at', '>=', $this->startDate)->where('finished_at', '<=', $this->endDate);
            });
        }
        
        return $query;
    }
    
    /**
     * @return void
     */
    public function updateTotals()
    {
        if ($this->exists) {
            $item = $this->fresh('rActionProducts');
            
            $mpc = $vpc = $mpc_discounted = $vpc_discounted = 0;
            foreach ($item->rActionProducts as $product) {
                $vpc += $product->qty * $product->prices['vpc'];
                $vpc_discounted += calculateDiscount($product->qty * $product->prices['vpc'], $product->vpc_discount);
    
                $mpc += $product->qty * $product->prices['mpc'];
                $mpc_discounted += calculateDiscount($product->qty * $product->prices['mpc'], $product->mpc_discount);
            }
            
            if (($this->isGratis())) {
                $item = $this->fresh('rGratisProducts');
    
                foreach ($item->rGratisProducts as $product) {
                    $vpc_discounted -= $product->qty * $product->prices['vpc'];
    
                    $mpc_discounted -= $product->qty * $product->prices['mpc'];
                }
            }
            
            $item->update([
                'subtotal' => $vpc,
                'subtotal_discount' => calculateDiscountPercent($vpc_discounted, $vpc),
                'subtotal_discounted' => $vpc_discounted,
                'total' => $mpc,
                'total_discount' => calculateDiscountPercent($mpc_discounted, $mpc),
                'total_discounted' => $mpc_discounted,
            ]);
        }
    }
    
    /**
     * @return int
     */
    public function getAvailableQtyAttribute()
    {
        if ($this->exists) {
            return $this->qty - $this->bought - $this->reserved;
        }
        
        return 0;
    }
    
    /**
     * @param int $qty
     */
    public function incrementReservedQty($qty)
    {
        if ($this->exists) {
            DB::table($this->getTable())->where('id', $this->id)->increment('reserved', $qty);
        }
    }
    
    /**
     * @param int $qty
     */
    public function decrementReservedQty($qty)
    {
        if ($this->exists) {
            $item = DB::table($this->getTable())->where('id', $this->id)->first();
            
            $reserved = $item->reserved - $qty;
            
            DB::table($this->getTable())->where('id', $this->id)->update([
                'reserved' => ($reserved < 0) ? 0 : $reserved,
            ]);
        }
    }
    
    /**
     * @param int $qty
     */
    public function incrementBoughtQty($qty)
    {
        if ($this->exists) {
            DB::table($this->getTable())->where('id', $this->id)->increment('bought', $qty);
        }
    }
    
    /**
     * @param int $qty
     */
    public function decrementBoughtQty($qty)
    {
        if ($this->exists) {
            DB::table($this->getTable())->where('id', $this->id)->decrement('bought', $qty);
        }
    }
    
    /**
     * @return bool
     */
    public function isGratis()
    {
        if ($this->exists) {
            return $this->type_id == 'gratis';
        }
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function isDiscount()
    {
        if ($this->exists) {
            return $this->type_id == 'discount';
        }
        
        return false;
    }
}
