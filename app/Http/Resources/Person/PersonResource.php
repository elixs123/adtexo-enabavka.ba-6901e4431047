<?php

namespace App\Http\Resources\Person;

use App\Http\Resources\BaseResource;

/**
 * Class PersonResource
 *
 * @package App\Http\Resources\Person
 */
class PersonResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = ['id' => $this->id,
            'name' => $this->name,
            'type_id' => $this->type_id,
            'email' => $this->email,
            'phone' => $this->phone,
            'code' => $this->code,
            'stock_id' => $this->stock_id,
        ];
        
        if ($this->whenLoaded('rUser')) {
            $user = $this->rUser;
            $role = $user->roles->first();
            
            $data['user'] = [
                'id' => $user->id,
                'email' => $user->email,
                'roles' => is_null($role) ? [] : [
                    [
                        'id' => $role->id,
                        'name' => $role->name,
                        'label' => $role->label,
                    ]
                ],
            ];
        }
        
        return $data;
    }
}
