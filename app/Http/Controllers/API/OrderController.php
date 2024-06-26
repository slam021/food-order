<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function store(Request $request){
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
                $data['total'] = '10000';
                $data['waitress_id'] = auth()->user()->id;

                $order = Order::create($data);

            DB::commit();

        }catch(\Throwable $th){
            DB::rollback();

        }



        return $order;


    }
}
