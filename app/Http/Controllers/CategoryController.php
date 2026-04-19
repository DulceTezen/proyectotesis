<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request){
        $categories = Category::active()->when($request->name, function($query, $name){
            return $query->where('name', 'LIKE', '%'.$name.'%');
        })->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(){
        return view('admin.categories.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('message', 'Registro creado');
    }

    public function edit(Request $request, Category $category){
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category){
        $request->validate([
            'name' => 'required'
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('message', 'Registro actualizado');;

    }

    public function destroy(Request $request, Category $category){
        $category->update(['deleted' => 1]);

        return redirect()->route('categories.index')->with('message', 'Registro eliminado');;
    }
}
