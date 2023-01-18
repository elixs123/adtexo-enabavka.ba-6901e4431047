<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\The_OrderItem;
use App\The_Order;
use App\Product;
use App\Subject;
use Carbon\Carbon;

class PantheonController extends Controller
{
    public function index(Request $request){

        $request->validate([
            'acSubject' => 'required'
        ]);

        $order = The_Order::where('acSubject', $request->input('acSubject'))
                        ->where('status', 'R')        
                        ->get();

        if($order->isEmpty()){
            $this->createOrder($request->input('acSubject'));

        $order = The_Order::where('acSubject', $request->input('acSubject'))
                    ->where('status', 'R')        
                    ->get();
        }

        $orderItems = The_OrderItem::where('orderNumber', $order[0]->id)->get();
        $subject = Subject::where('acSubject', $request->input('acSubject'))->first();

        return view('documents.index', ['order' => $order, 'orderItems' => $orderItems, 'subject' => $subject]);
    }

    private function createOrder($acSubject){
        $order = new The_Order;
        $order->acSubject = $acSubject;
        $order->status = 'R';
        $order->anDaysForValid = Carbon::now()->addDay(5);

        $order->save();
    }

    public function insert(Request $request){
        $pdv = (float) $request->input('anSalePrice') * 0.17;

        $rebate1 = ((float) $request->input('anSalePrice') + $request->input('anQty')) / 1.17 - ((float) $request->input('anSalePrice') / 1.17 * ((float) $request->input('anRebate1') / 100));

        $rebate2 = $rebate1 - ($rebate1 * ((float) $request->input('anRebate2') / 100));
        $rebate3 = $rebate2 - ($rebate2 * ((float) $request->input('anRebate3') / 100));

        $anForPay = $rebate3;

        $order = new The_OrderItem;
        $order->acIdent = $request->input('acIdent');

        if($request->has('acWayOfSale') == 'Z'){
            $order->anPrice = $request->input('anWSPrice');
        }else{
            $order->anPrice = $request->input('anSalePrice');
        }
        
        $order->anQty = $request->input('anQty');
        $order->anRebate1 = $request->input('anRebate1');
        $order->anRebate2 = $request->input('anRebate2');
        $order->anRebate3 = $request->input('anRebate3');
        $order->orderNumber = $request->input('orderNumber');
        $order->anForPay = $anForPay;

        $order->save();

        return redirect()->back();
    }
}
