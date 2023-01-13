<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Person\StorePersonRequest;
use App\Http\Requests\Person\UpdatePersonRequest;
use App\Http\Resources\Person\PersonCollection;
use App\Http\Resources\Person\PersonResource;
use App\Person;
use Illuminate\Http\Request;

/**
 * Class PersonController
 *
 * @package App\Http\Controllers\Api
 */
class PersonController extends Controller
{
    /**
     * @var \App\Person
     */
    private $person;
    
    /**
     * PersonController constructor.
     *
     * @param \App\Person $person
     */
    public function __construct(Person $person)
    {
        $this->person = $person;
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\Person\PersonCollection
     */
    public function index(Request $request)
    {
        $this->person->paginate = true;
        $this->person->limit = $request->get('limit', 100);
        $this->person->typeId = $request->get('type_id');
        $this->person->keywords = $request->get('keywords');
        $this->person->withUser = $request->filled('with_user');
        $items = $this->person->relation(['rUser'])->getAll();
        
        return new PersonCollection($items);
    }
    
    /**
     * @param \App\Http\Requests\Person\StorePersonRequest $request
     * @return \App\Http\Resources\Person\PersonResource
     */
    public function store(StorePersonRequest $request)
    {
        $attributes = $request->only([
            'name',
            'type_id',
            'email',
            'phone',
            'note',
            'code',
            'stock_id',
            'status',
            'printer_type',
            'printer_receipt_url',
            'printer_access_token'
        ]);
    
        $person = $this->person->add($attributes);
        
        return new PersonResource($person);
    }
    
    /**
     * @param integer $id
     * @return \App\Http\Resources\Person\PersonResource
     */
    public function show($id)
    {
        $person = $this->person->getOne($id);
        abort_if(is_null($person), 404);
        
        return new PersonResource($person);
    }
    
    /**
     * @param \App\Http\Requests\Person\UpdatePersonRequest $request
     * @param integer $id
     * @return \App\Http\Resources\Person\PersonResource
     */
    public function update(UpdatePersonRequest $request, $id)
    {
        $attributes = $request->only([
            'name',
            'type_id',
            'email',
            'phone',
            'note',
            'code',
            'stock_id',
            'status',
            'printer_type',
            'printer_receipt_url',
            'printer_access_token'
        ]);
    
        $person = $this->person->edit($id, $attributes);
        
        return new PersonResource($person);
    }
}
