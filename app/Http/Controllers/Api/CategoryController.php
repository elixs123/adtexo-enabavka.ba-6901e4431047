<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
/**
 * Class CategoryController
 *
 * @package App\Http\Controllers\Api
 */
class CategoryController extends Controller
{
    public function index()
    {
        $categorys = Category::all();

        return $categorys;
    }

    public function saveCategory(Request $request)
    {
        $request->validate([
            'acClassif' => 'required:max:50',
            'acName' => 'required:max:50',
            'acType' => 'required:max:3',
        ],[
            'acClassif.required' => 'Polje klasifikacija je obavezno',
            'acName.required' => 'Polje naziv je obavezno',
            'acType.required' => 'Polje tip je obavezno'
        ]);

        try{

        $category = new Category;
        $category->acClassif = $request->input('acClassif');
        $category->acName = $request->input('acName');
        $category->acType = $request->input('acType');

        $category->save();

        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Greška prilikom dodavanja kategorije',
                'status' => 'F' 
            ]);
        }

        return response()->json([
            'message' => 'Uspjesno ste dodali kategoriju ' . $request->input('acName'),
            'status' => 'T' 
        ]);
    }

    public function updateCategory($id, Request $request){
        $request->validate([
            'acName' => 'required:max:50',
        ],[
            'acName.required' => 'Polje naziv je obavezno',
        ]);

        try{
            $category = Category::find($id);
            $category->acName = $request->input('acName');

            $category->save();
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json([
                'message' => 'Greška prilikom izmjene kategorije ' . $id,
                'status' => 'F' 
            ]);
        }
        
        return response()->json([
            'message' => 'Uspjesno ste izmjenili kategoriju id ' . $id,
            'status' => 'T' 
        ]);
    }
}
