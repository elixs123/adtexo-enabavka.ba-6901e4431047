<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\The_OrderItem;
use App\The_Order;
use App\Product;
use App\Subject;
use Carbon\Carbon;
use DB;

class PantheonController extends Controller
{
    public function index($id){

        $order = The_Order::where('id', $id)->firstOrFail();

        $orderItems = The_OrderItem::where('orderNumber', $order->orderNumber)->orderBy('anNo', 'desc')->get();

        $subject = Subject::where('acSubject', $order->acSubject)->first();

        $acPayer = Subject::where('acSubject', $order->acPayer)->first();

        return view('documents.index', ['order' => $order, 'orderItems' => $orderItems, 'subject' => $subject, 'acPayer' => $acPayer]);
    }

    public function createOrder(Request $request){
        $request->validate([
            'acSubject' => 'required:max:20'
        ]);

        $order = The_Order::where('acSubject', $request->input('acSubject'))
                        ->where('acStatus', 'N')        
                        ->get();

        if($order->isEmpty()){

            $subject = Subject::where('acSubject', $request->input('acSubject'))->first();
    
            $acPayer = Subject::where('acSubject', $subject->acPayer)->first();


            $order = new The_Order;
            $order->acSubject = $subject->acSubject;
            $order->acStatus = 'N';
            $order->anDaysForValid = Carbon::now()->addDay(5);
            $order->acPayer = $acPayer->acSubject;
            $order->acPayerName = $acPayer->acName2;
            $order->save();

            $order->orderNumber = '#'.sprintf("%06d", $order->id).'/'.date('y');
            $order->save();
            

            return redirect()->route('createorder', $order->id); 
        }

        return redirect()->route('createorder', $order->id); 
        
    }

    public function update(Request $request){
        if($request->has('acStatus')){
            $request->validate([
                'orderNumber' => 'required:string:max:100'
            ]);

            The_Order::where('orderNumber', $request->input('orderNumber'))
                            ->update([
                                'acStatus' => 'R'
                            ]);
                            
            return redirect()->route('orders');
        }

        $request->validate([
            'orderNumber' => 'required:string:max:100',
            'anNo' => 'required:int',
        ]);

        if($request->has('btn_delete')){
            $orderItem = The_OrderItem::where('orderNumber', $request->input('orderNumber'))
            ->where('anNo', $request->input('anNo'))->delete();

            $sumAnForPay = The_OrderItem::where('orderNumber', $request->input('orderNumber'))->sum(DB::raw('anForPay * anQty'));


            The_Order::where('orderNumber', $request->input('orderNumber'))->update([
                'anForPay' => $sumAnForPay
            ]);

            return redirect()->back();
        }

        $rebate1 = ((float) $request->input('anPrice') * $request->input('anQty')) - ((float) $request->input('anPrice') * $request->input('anQty') * (float) $request->input('anRebate1') / 100);
        
        $rebate2 = $rebate1 - ($rebate1 * ((float) $request->input('anRebate2') / 100));

        $anForPay = $rebate2 - ($rebate2 * ((float) $request->input('anRebate3') / 100));

        
        The_OrderItem::where('orderNumber', $request->input('orderNumber'))
                            ->where('anNo', $request->input('anNo'))
                            ->update([
                                'anQty' => $request->input('anQty'),
                                'anRebate2' => $request->input('anRebate2'),
                                'anForPay' => $anForPay
                            ]);

       

        $sumAnForPay = The_OrderItem::where('orderNumber', $request->input('orderNumber'))->sum(DB::raw('anForPay * anQty'));


        The_Order::where('orderNumber', $request->input('orderNumber'))->update([
            'anForPay' => $sumAnForPay
        ]);
        
        return redirect()->back();
    }

    public function insert(Request $request){
        $order = new The_OrderItem;
        $order->acIdent = $request->input('acIdent');

        if($request->has('acWayOfSale') == 'Z'){
            $order->anPrice = $request->input('anWSPrice2') * 1.17;
            $rebate1 = ((float) $request->input('anWSPrice2') * $request->input('anQty')) - ((float) $request->input('anWSPrice2') * $request->input('anQty') * (float) $request->input('anRebate1') / 100);
        }else{
            $order->anPrice = $request->input('anRTPrice') * 1.17;
            $rebate1 = ((float) $request->input('anRTPrice') * $request->input('anQty'))  - ((float) $request->input('anRTPrice') * $request->input('anQty') *((float) $request->input('anRebate1') / 100));
        }

        $rebate2 = $rebate1 - ($rebate1 * ((float) $request->input('anRebate2') / 100));
        $rebate3 = $rebate2 - ($rebate2 * ((float) $request->input('anRebate3') / 100));

        $anForPay = $rebate3;
        
        $order->anQty = $request->input('anQty');
        $order->anRebate1 = $request->input('anRebate1');
        $order->anRebate2 = $request->input('anRebate2');
        $order->anRebate3 = $request->input('anRebate3');
        $order->orderNumber = $request->input('orderNumber');
        $order->anForPay = $anForPay;
        $order->anNo = $request->input('anNo');

        $order->save();

        $sumAnForPay = The_OrderItem::where('orderNumber', $request->input('orderNumber'))->sum(DB::raw('anForPay * anQty'));

        The_Order::where('orderNumber', $request->input('orderNumber'))->update([
            'anForPay' => $sumAnForPay
        ]);

        return redirect()->back();
    }
}
