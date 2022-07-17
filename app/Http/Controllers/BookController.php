<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function allBooks(){
        return Book::all();
    }

    public function authorsBooks($author_id){
        return response()->json(['dados' => Book::where('author_id', $author_id)->orderBy('title')->get()]);        
    }

    public function bookInfo($id){
        return response()->json(['dados' => Book::find($id)]);
    }
}
