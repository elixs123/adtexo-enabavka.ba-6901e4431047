<?php

namespace App;

use App\Support\Model\Client;
use App\Support\Model\Status;
use App\Support\Model\User;

/**
 * Class Contract
 *
 * @package App
 */
class Contract extends BaseModel
{
    use Client, Status;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contracts';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'total_qty',
        'total_bought',
        'note',
        'status',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'client_id' => 'integer',
        'total_qty' => 'integer',
        'total_bought' => 'integer',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = [
        'uid',
        'in_stock',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rContractProducts()
    {
        return $this->hasMany(ContractProduct::class, 'contract_id', 'id');
    }
    
    /**
     * Return list of documents.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sStatus()
            ->sClient()
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
            ->sClient()
            ->where($this->table.'.id', $id)
            ->first();
    }
    
    /**
     * @return int|mixed
     */
    public function getInStockAttribute()
    {
        if ($this->exists) {
            return $this->total_qty - $this->total_bought;
        }
        
        return 0;
    }
    
    /**
     * @return self
     */
    public function updateTotals()
    {
        $total_qty = ContractProduct::where('contract_id', $this->id)->sum('qty');
        $total_bought = ContractProduct::where('contract_id', $this->id)->sum('bought');
        
        $this->update([
            'total_qty' => $total_qty,
            'total_bought' => $total_bought,
        ]);
        
        return $this;
    }
}
