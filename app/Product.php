<?php namespace App;

use App\Support\Model\Search;
use App\Support\Model\Status;
use Illuminate\Support\Facades\DB;
use App\Support\Scoped\ScopedContractFacade as ScopedContract;
use App\Support\Scoped\ScopedDocumentFacade as ScopedDocument;

class Product extends BaseModel
{
   
    protected $table = 'tHE_SetItem';
    protected $primaryKey = 'anQid';
    public $timestamps = false;
    
    protected $guarded = array();

    protected $fillable = [
        //'acName'
    ];
}
