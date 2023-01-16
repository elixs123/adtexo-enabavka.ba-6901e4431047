<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'tHE_SetSubj';
    protected $primaryKey = 'anQid';
    public $timestamps = false;
    
    protected $guarded = array();

    protected $fillable = [
        //'acName'
    ];
}
