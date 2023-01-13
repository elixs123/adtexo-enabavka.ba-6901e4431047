<?php

namespace App\Support\Controller;

use App\Client;
use App\Person;
use App\Stock;
use Illuminate\Support\Facades\DB;

/**
 * Trait ClientHelper
 *
 * @package App\Support\Controller
 */
trait ClientHelper
{
    /**
     * Client person types.
     *
     * @return array
     */
    protected function getClientPersonTypes()
    {
        $person_types = (new Person())->getTypes();
        
        $types = [
            'client_person_id' => [
                'value' => $person_types['client_person'],
                'relation' => 'rClientPerson',
                'type' => 'client_person',
            ],
            'responsible_person_id' => [
                'value' => $person_types['responsible_person'],
                'relation' => 'rResponsiblePerson',
                'type' => 'responsible_person',
            ],
            'payment_person_id' => [
                'value' => $person_types['payment_person'],
                'relation' => 'rPaymentPerson',
                'type' => 'payment_person',
            ],
            'supervisor_person_id' => [
                'value' => $person_types['supervisor_person'],
                'relation' => 'rSupervisorPerson',
                'type' => 'supervisor_person',
            ],
        ];
        
        if (!userIsSalesman()) {
            $types['salesman_person_id'] = [
                'value' => $person_types['salesman_person'],
                'relation' => 'rSalesmanPerson',
                'type' => 'salesman_person',
            ];
        }
        
        return $types;
    }
    
    /**
     * @param mixed $client
     * @return array
     */
    protected function getClientProducts($client)
    {
        return $client->rProducts()->with('translation')->get()->map(function($product, $key) {
            return [
                'id' => $product->id,
                'name' => $product->translation->name,
                'code' => $product->code,
            ];
        })->toArray();
    }
    
    /**
     * @param \App\Client|mixed $client
     * @return array
     */
    protected function getClientActions($client)
    {
        return $client->rActions()->get()->map(function($action, $key) {
            return [
                'id' => $action->id,
                'name' => $action->name,
            ];
        })->toArray();
    }
    
    /**
     * Get stocks list.
     *
     * @return array
     */
    protected function getStocks()
    {
        $stock = new Stock();
        $stock->limit = null;
        $stock->statusId = 'active';
        
        return $stock->getAll()->map(function ($stock, $key) {
            return [
                'id' => $stock->id,
                'name' => $stock->full_name,
            ];
        })->pluck('name', 'id')->toArray();
    }
    
    /**
     * Request data.
     *
     * @return array
     */
    protected function requestData()
    {
        return [
            'parent_id',
            'type_id',
            'jib',
            'pib',
            'code',
            'name',
            'location_code',
            'location_name',
            'location_type_id',
            'category_id',
            'phone',
            'status',
            'note',
            
            'address',
            'city',
            'postal_code',
            'country_id',
            'latitude',
            'longitude',
            
            'client_person_id',
            'responsible_person_id',
            'payment_person_id',
            'salesman_person_id',
            'supervisor_person_id',
            
            'payment_therms',
            'payment_period',
            'payment_type',
            'payment_discount',
            'discount_value1',
            'discount_value2',
            'allowed_limit_in',
            'allowed_limit_outside',
            
            'stock_id',
            'lang_id',
        ];
    }
    
    /**
     * Sync person client routes.
     *
     * @param int $person_id
     * @param int $client_id
     * @param array $routes
     * @param null|int $parent_id
     * @param null|int $old_person_id
     * @return void
     */
    protected function syncPersonClientRoutes($person_id, $client_id, $routes, $parent_id = null, $old_person_id = null)
    {
        if (is_null($person_id)) {
            return;
        }
        
        $data = [];
        foreach ($routes as $week_id => $week) {
            foreach ($week as $day_id => $rank) {
                if (!is_null($rank) && ((int)$rank != 0)) {
                    $data[] = [
                        'person_id' => $person_id,
                        'client_id' => $client_id,
                        'week' => $week_id,
                        'day' => $day_id,
                        'rank' => $rank,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        
        if (!is_null($parent_id)) {
            $data[] = [
                'person_id' => $person_id,
                'client_id' => $parent_id,
                'week' => 0,
                'day' => null,
                'rank' => 9999,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        if (!empty($data)) {
            $this->deleteRoutes($person_id, $client_id);
            if (!is_null($parent_id)) {
                $this->deleteRoutes($person_id, $parent_id);
            }
            if (!is_null($old_person_id)) {
                $this->deleteRoutes($old_person_id, $client_id);
                if (!is_null($parent_id)) {
                    $person = (new Person())->getOne($old_person_id);
                    
                    if ($person->rClients()->where('parent_id', $parent_id)->get()->unique('id')->count() == 0) {
                        $this->deleteRoutes($old_person_id, $parent_id);
                    }
                }
            }
            
            DB::table('routes')->insert($data);
        }
    }
    
    /**
     * @param \App\Client|mixed $location
     * @return void
     */
    protected function syncCategoriesProductsAndActionsWithHeadquarter($location)
    {
        $headquarter = $location->rHeadquarter;
        
        if (!is_null($headquarter)) {
            $categories = $headquarter->rCategories()->get(['id'])->pluck('id')->toArray();
            
            $products = $headquarter->rProducts()->get(['id'])->pluck('id')->toArray();
            
            $actions = $headquarter->rActions()->get(['id'])->pluck('id')->toArray();
            
            $location->rCategories()->sync($categories);
            $location->rProducts()->sync($products);
            $location->rActions()->sync($actions);
        }
    }
    
    /**
     * @param \App\Client|mixed $headquarter
     * @param array $categories
     * @param array $products
     * @param array $actions
     */
    protected function syncCategoriesProductsAndActionsWithLocations($headquarter, array $categories, array $products, array $actions)
    {
        foreach ($headquarter->rLocations as $location) {
            $location->rCategories()->sync($categories);
            $location->rProducts()->sync($products);
            $location->rActions()->sync($actions);
        }
    }
    
    /**
     * @param int $person_id
     * @param int $client_id
     */
    protected function deleteRoutes($person_id, $client_id)
    {
        DB::table('routes')->where([
            'person_id' => $person_id,
            'client_id' => $client_id,
        ])->delete();
    }
    
    /**
     * @param \App\Client|mixed $headquarter
     * @param array $categories
     * @param array $products
     * @param array $actions
     */
    protected function syncLocationsWithHeadquarter($headquarter, array $categories, array $products, array $actions)
    {
        foreach ($headquarter->rLocations as $location) {
            $location->update([
                'payment_period' => $headquarter->payment_period,
                'payment_type' => $headquarter->payment_type,
                'payment_discount' => $headquarter->payment_discount,
                'discount_value1' => $headquarter->discount_value1,
                'discount_value2' => $headquarter->discount_value2,
                'status' => $headquarter->status,
            ]);
            
            $location->rCategories()->sync($categories);
            $location->rProducts()->sync($products);
            $location->rActions()->sync($actions);
        }
    }
    
    /**
     * @param \App\Client $client
     * @param null|string $personType
     * @param null|int $personId
     */
    protected function scopeClientPersonId(&$client, $personType = null, $personId = null)
    {
        if (userIsSupervisor()) {
            $client->personType = 'supervisor_person';
            $client->personId = $this->getUser()->rPerson->id;
        } else {
            $client->personType = $personType;
            $client->personId = $personId;
        }
    }
}
