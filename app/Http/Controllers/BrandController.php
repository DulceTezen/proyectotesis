<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index(Request $request){
        $brands = Brand::active()->when($request->name, function($query, $name){
            return $query->where('name', 'LIKE', '%'.$name.'%');
        })->paginate(20);

        return view('admin.brands.index', compact('brands'));
    }

    public function create(){
        return view('admin.brands.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        Brand::create($request->all());

        return redirect()->route('brands.index')->with('message', 'Registro creado');
    }

    public function edit(Request $request, Brand $brand){
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand){
        $request->validate([
            'name' => 'required'
        ]);

        $brand->update($request->all());

        return redirect()->route('brands.index')->with('message', 'Registro actualizado');

    }

    public function destroy(Request $request, Brand $brand){
        $brand->update(['deleted' => 1]);

        return redirect()->route('brands.index')->with('message', 'Registro eliminado');
    }
}
