<?php

namespace App\Http\Controllers\Api;

use App\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;


/**
 * Class BrandController
 *
 * @package App\Http\Controllers\Api
 */
class BrandController extends Controller
{
    public function getAll(){
        $brands = Brand::all();
        
        return $brands;
    }
    
    public function getOne($lang, $id){
        try
        {
            $brand = Brand::find($id);
        }
        // catch(Exception $e) catch any exception
        catch(ModelNotFoundException $e)
        {
           return $e;
        }
        
        return $brand;
    }
    
    public function insertBrand(Request $request){
        $request->validate([
            'name' => 'required|max:100'
            ]);
        
        Brand::create($request->all());
        
        return response()->json(
            ['message' => 'Uspje分no ste kreirali brand ' . $request->input('name'), 'status' => 'T']
        );
    }
    
    public function updateBrand(Request $request){
        $request->validate([
            'id' => 'required',
            'name' => 'required'
            ]);
        
        $brand = Brand::find($request->input('id'));
        
        
        $brand->name = $request->input('name');
        
        $brand->save();
        
        return response()->json(
            ['message' => 'Uspje分no ste azurirali brand id ' . $request->input('id'), 'status' => 'T']
        );
    }
}