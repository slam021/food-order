<?php

namespace App\Http\Controllers\API;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        $order = Order::select('id', 'customer_name', 'table_numb', 'order_date', 'total_price', 'status')->get();

        return response([
            'success' => true,
            'message' => 'Data Order Berhasil diambil!',
            'data' => $order,
        ]);
    }

    public function store(Request $request)
    {
        $validation = $request->validate([
            'customer_name' => 'required|max:100',
            'table_numb' => 'required|max:5',
        ]);

        try{
            DB::beginTransaction();

                $data = $request->only(['customer_name', 'table_numb']);
                $data['order_date'] = date('Y-m-d H:s:i');
                // $data['order_time'] = date('H:s:i');
                $data['status'] = 'ordered';
                $data['total_price'] = 0;
                $data['waitress_id'] = auth()->user()->id;
                $data['items'] = $request->items;

                $order = Order::create($data);

                //menggunakan foreach================================>
                // foreach ($data['items'] as $item) {
                //     $itemOrder = Item::where('id', $item)->first();
                //     if ($itemOrder) {
                //         $orderDetail = OrderDetail::create([
                //             'order_id' => $order->id,
                //             'item_id' => $itemOrder->id,
                //             'price' => $itemOrder->price
                //         ]);
                //     }
                // }

                //menggunkan collect map
                collect($data['items'])->map(function($item) use($order) {
                    $itemOrder = Item::find($item);
                    $orderDetail = OrderDetail::create([
                        'order_id' => $order->id,
                        'item_id' => $itemOrder->id,
                        'price' => $itemOrder->price
                    ]);
                });

                //edit total price
                $order->total_price = $order->sumOrderPrice();
                $order->save();

            DB::commit();
        }catch(\Throwable $th){
            DB::rollback();
            return response($th);
        }

        return response([
            'success' => true,
            'message' => 'Order Berhasil disimpan!',
            'data' => $order,
        ]);
    }

    public function show($id)
    {
        $order = Order::select('id', 'customer_name', 'table_numb', 'order_date', 'total_price', 'status', 'waitress_id', 'chasier_id')
        ->where('id', $id)
        ->firstOrFail();

        $order->loadMissing(['waitress:id,name', 'chasier:id,name', 'orderDetail:id,order_id,item_id,price', 'orderDetail.item:id,name,price,image']);

        return response([
            'success' => true,
            'message' => 'Detail Order Berhasil diambil!',
            'data' => $order,
        ]);
    }

    public function onProgressOrder($id){
        $order = Order::findOrFail($id);

        if($order->status != 'ordered'){
            return response('Status order bukan ordered', 403);
        }

        $order->status = 'on progress';
        $order->save();

        return response([
            'success' => true,
            'message' => 'Status order berhasil diubah menjadi On Progress!',
            'data' => $order,
        ]);
    }

    public function finishOrder($id){
        $order = Order::findOrFail($id);

        if($order->status != 'on progress'){
            return response('Status order bukan on progress', 403);
        }

        $order->status = 'done';
        $order->save();

        return response([
            'success' => true,
            'message' => 'Status order berhasil diubah menjadi Done!',
            'data' => $order,
        ]);
    }

    public function payOrder($id){
        $order = Order::findOrFail($id);

        if($order->status != 'done'){
            return response('Status order bukan Done', 403);
        }

        $order->status = 'paid';
        $order->save();

        return response([
            'success' => true,
            'message' => 'Status order berhasil diubah menjadi Paid!',
            'data' => $order,
        ]);
    }


}
