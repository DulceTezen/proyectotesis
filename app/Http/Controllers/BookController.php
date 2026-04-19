<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index(Request $request){
        $books = Book::when($request->name, function($query, $name){
            return $query->where('name', 'LIKE', '%'.$name.'%');
        })->paginate(20);

        return view('admin.books.index', compact('books'));
    }

}
