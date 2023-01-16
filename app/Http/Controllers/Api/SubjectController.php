<?php

namespace App\Http\Controllers\Api;

use App\Subject;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
/**
 * Class CategoryController
 *
 * @package App\Http\Controllers\Api
 */
class SubjectController extends Controller
{
    public function index()
    {
        try{
            $categorys = Subject::all();
        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Greška prilikom preuzimanja subjekata',
                'status' => 'F' 
            ]);
        }
        return $categorys;
    }

    public function saveSubject(Request $request)
    {
        $request->validate([
            'acSubject' => 'required:max:50',
            'acName2' => 'required:max:50',
            'acAddress' => 'required:max:3',
        ],[
            'acSubject.required' => 'Polje Sujekat je obavezno',
            'acName2.required' => 'Polje naziv je obavezno',
            'acAddress.required' => 'Polje adresa je obavezno'
        ]);

        try{

        $category = new Subject;
        $category->acSubject = $request->input('acSubject');
        $category->acName2 = $request->input('acName2');
        $category->acAddress = $request->input('acAddress');

        $category->save();

        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Greška prilikom dodavanja subjekta',
                'status' => 'F' 
            ]);
        }

        return response()->json([
            'message' => 'Uspjesno ste dodali subjekta ' . $request->input('acName'),
            'status' => 'T' 
        ]);
    }

    public function updateSubject($id, Request $request){
        try{
            $product = Subject::findOrFail($id);

            $product->fill($request->all())->save();

        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Greška prilikom updejtovanja subjekta',
                'status' => 'F' 
            ]);
        }

        return response()->json([
            'message' => 'Uspješno ste izmjenili subjekta',
            'status' => 'T' 
        ]);
    }
}
