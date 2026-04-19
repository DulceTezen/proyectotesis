<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function add(Request $request){

        $request->validate([
            'quantity' => 'nullable|integer|min:1'
        ]);

        $cart = session('cart', []);

        $id = $request->id;
        $product = Product::find($id);
        $quantity = $request->quantity ? $request->quantity : 1;

        if(isset($cart[$id])){
            $cart[$id]['quantity'] += $quantity;
        }else{
            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity
            ];
        }

        session(['cart' => $cart]);

        return redirect()->route('cart');
    }

    //public function update(Request $request){

      //  $request->validate([
       //     'quantity' => 'required|integer|min:1'
        //]);

        //$cart = session('cart', []);

       // $id = $request->id;
       // $quantity = $request->quantity;

       // if(isset($cart[$id])){
            //if($quantity > 0){
               // $cart[$id]['quantity'] = $quantity;
           // }else{
               // unset($cart[$id]);
            //}
        //}

        //session(['cart' => $cart]);

       // return redirect()->route('cart');
    //}

    public function update(Request $request)
        {
            $id = $request->input('id');
            $quantity = (int) $request->input('quantity');

            $product = Product::find($id);

            if ($quantity > $product->stock) {
                return redirect()->back()->with('error', 'La cantidad solicitada excede el stock disponible.');
            }

            // Actualizar carrito
            $cart = session()->get('cart');
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);

            return redirect()->back()->with('success', 'Cantidad actualizada correctamente.');
        }

    public function remove(Request $request){
        $cart = session('cart', []);
        $id = $request->id;

        if(isset($cart[$id])){
            unset($cart[$id]);
        }

        session(['cart' => $cart]);

        return redirect()->route('cart');
    }

    public function clear(){
        session()->forget('cart');

        return redirect()->route('cart');
    }
}
