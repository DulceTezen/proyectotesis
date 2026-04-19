<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ProductController extends Controller
{
    public function index(Request $request){
        $products = Product::active()->when($request->name, function($query, $name){
            return $query->where('name', 'LIKE', '%'.$name.'%');
        })->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create(){
        $categories = Category::active()->orderBy('name', 'asc')->get();
        $brands = Brand::active()->orderBy('name', 'asc')->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp'
        ]);

        $image = 'products/default.png';

        if($request->hasFile('image')){
            $image = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $image
        ]);

        return redirect()->route('products.index')->with('message', 'Registro creado');
    }

    public function edit(Request $request, Product $product){
        $categories = Category::active()->orderBy('name', 'asc')->get();
        $brands = Brand::active()->orderBy('name', 'asc')->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product){
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp'
        ]);

        $image = $product->image;

        if($request->hasFile('image')){
            $image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $image
        ]);

        return redirect()->route('products.index')->with('message', 'Registro actualizado');
    }

    public function destroy(Request $request, Product $product){
        $product->update(['deleted' => 1]);

        return redirect()->route('products.index')->with('message', 'Registro eliminado');
    }
}
