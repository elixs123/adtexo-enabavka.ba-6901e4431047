<?php namespace App;

use App\Support\Model\Search;
use App\Support\Model\Status;

class Brand extends BaseModel
{
    use Status, Search;

    protected $guarded = [];

    public function products()
    {
        return $this->hasMany('App\Product');
    }
    
    /**
     * Get data about specific brand
     * @param mixed $id Brand Id/slug
     * @return Collection
     */
    public function getOne($id)
    {
        if(is_numeric($id))
        {
            return self::find($id);
        }
        else
        {
            return self::whereSlug($id)->first();
        }
    }

    /**
     * Get brands
     * @return Collection
     */
    public function getAll()
    {
        return $this->sStatus()->sSearch()->orderBy('priority', 'asc')->sPaginate();
    }
}
