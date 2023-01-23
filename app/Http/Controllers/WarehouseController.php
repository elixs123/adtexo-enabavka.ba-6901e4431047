<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\The_OrderItem;
use App\The_Order;
use App\Product;
use App\Subject;
use Carbon\Carbon;
use DB;

class WarehouseController extends Controller
{
    public function index(){
        $orders = The_Order::with('subject')
            ->where('acStatus', 'R')
        ->get();


        return view('warehouse.index', ['orders' => $orders]);
    }

    public function order($id){
        $order = The_Order::where('id', $id)->firstOrFail();
        $pantheonOrder = true;

        $orderItems = The_OrderItem::where('orderNumber', $order->orderNumber)->with('items')
        ->orderBy('anNo', 'desc')->get();

        $subject = Subject::where('acSubject', $order->acSubject)->first();

        $acPayer = Subject::where('acSubject', $order->acPayer)->first();

        return view('warehouse.document', ['pantheonOrder' => $pantheonOrder, 'order' => $order, 'orderItems' => $orderItems, 'subject' => $subject, 'acPayer' => $acPayer]);
    }

    public function orderSave($id, Request $request){
        if($request->has('updateAnQty')){
            The_OrderItem::where('orderNumber', $request->input('orderNumber'))
                            ->where('anNo', $request->input('anNo'))
                            ->update([
                                'anQty' => $request->input('anQty')
                            ]);

            $sumAnForPay = The_OrderItem::where('orderNumber', $request->input('orderNumber'))->sum(DB::raw('anForPay * anQty'));


            The_Order::where('orderNumber', $request->input('orderNumber'))->update([
                'anForPay' => $sumAnForPay
            ]);

            return redirect()->back();
        }


        if($request->has('acStatus')){
            $request->validate([
                'orderNumber' => 'required:string:max:100'
            ]);

            The_Order::where('id', $id)
                            ->update([
                                'acStatus' => 'O'
                            ]);
                            
            return redirect()->route('warehouse.index');
        }
    }
}
