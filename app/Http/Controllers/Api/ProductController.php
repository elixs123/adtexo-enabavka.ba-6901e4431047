<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Subject;
use App\The_SetSubjPriceItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ProductController
 *
 * @package App\Http\Controllers\Api
 */
class ProductController extends Controller
{
    public function index(){
        try{
            $products = Product::all();

        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Greška prilikom preuzimanja proizvoda',
                'status' => 'F' 
            ]);
        }

        return $products;
    }

    public function insertProduct(Request $request){
        $request->validate([
            'acIdent' => 'required',
            'acName' => 'required',
            'acClassif' => 'required',
            'acClassif2' => 'required',
            'acCode' => 'required',
            'acCurrency' => 'required',
            'anSalePrice' => 'required',
            'anRTPrice' => 'required',
            'anWSPrice' => 'required',
            'anBuyPrice' => 'required',
            'anPriceSupp' => 'required',
            'acPurchCurr' => 'required',
            'acUM' => 'required',
            'anVAT' => 'required',
            'acVATCode' => 'required',
            'acActive' => 'required',
        ]);

        try{
            $product = new Product;

            $product->create($request->all());

        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Greška prilikom dodavanja proizvoda',
                'status' => 'F' 
            ]);
        }

        return response()->json([
            'message' => 'Uspješno ste dodali proizvod ' . $request->input('acName'),
            'status' => 'T' 
        ]);
    }

    public function updateProduct($id, Request $request){

        try{
            $product = Product::findOrFail($id);

            $product->fill($request->all())->save();

        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Greška prilikom dodavanja proizvoda',
                'status' => 'F' 
            ]);
        }

        return response()->json([
            'message' => 'Uspješno ste izmjenili proizvod',
            'status' => 'T' 
        ]);

    }

    public function search(Request $request){
        $data['products'] = Product::where($request->input('searchName'), 'like', '%' . $request->input('search') . '%')->get();
        $data['acSubject'] = Subject::where('acSubject', $request->input('acSubject'))->get();
        return $data;
    }

    public function searchRabat(Request $request){
        $data['priceItem'] = The_SetSubjPriceItem::where('acSubject', $request->input('acSubject'))
                                                    ->where('acIdent', $request->input('acIdent'))
                                                    ->get();

        return $data;
    }
}
