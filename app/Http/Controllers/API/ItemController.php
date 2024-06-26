<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(){
        $items = Item::all();

        return response([
            'success' => true,
            'message' => 'Item Berhasil ditampilkan!',
            'data' => $items
        ]);

    }

    public function store(Request $request){
        $validation = $request->validate([
            'name' => 'required|max:100',
            'price' => 'required|integer',
            'image' => 'nullable|mimes:jpg,png',
        ]);

        if($request->file('image')){
            $file = $request->file('image');
            $nameFile = $file->getClientOriginalName();
            $newNameFile = Carbon::now()->timestamp.'-'.$nameFile;

            // return $newNameFile;

            Storage::disk('public')->putFileAs('items', $file, $newNameFile);

            $item = Item::create([
                'name' => $validation['name'],
                'price' => $validation['price'],
                'image' => $newNameFile,
            ]);
        }else{
            $item = Item::create([
                'name' => $validation['name'],
                'price' => $validation['price'],
                // 'image' => null,
            ]);
        }

        return response([
            'success' => true,
            'message' => 'Item Berhasil disimpan!',
            'data' => $item,
        ]);
    }

    public function update(Request $request, $id){
        $validation = $request->validate([
            'name' => 'required|max:100',
            'price' => 'required|integer',
            'image' => 'nullable|mimes:jpg,png',
        ]);

        // return $validation;

        $item = Item::findOrFail($id);
        $item->name = $validation['name'];
        $item->price = $validation['price'];

        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada
            if ($item->image) {
                Storage::disk('public')->delete('items/' . $item->image);
            }

            // Simpan foto baru
            $file = $request->file('image');
            $nameFile = $file->getClientOriginalName();
            $newNameFile = Carbon::now()->timestamp . '-' . $nameFile;

            Storage::disk('public')->putFileAs('items', $file, $newNameFile);
            $item->image = $newNameFile; // Simpan nama file baru di atribut image
        }

        // Simpan perubahan ke database
        $item->save();

        return response([
            'success' => true,
            'message' => 'Item Berhasil diupdate!',
            'data' => $item,
        ]);
    }
}
