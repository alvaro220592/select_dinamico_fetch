<?php

namespace App\Http\Controllers;

use App\Models\Book;
use DOMDocument;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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

    public function sendFile(Request $request){

        $xml = simplexml_load_file($request->arquivo);

        return response()->json(['resposta' => $xml->evtMovOpFin->ideDeclarado->tpDeclarado]);

    }

    public function store(Request $request){
        $book = new Book;
        $book->fill($request->all());

        if($book->save()){
            return response()->json(['status' => 'ok', 'response' => 'Cadastrado com sucesso']);
        }else{
            return response()->json(['status' => 'error', 'response' => 'Erro ao cadastrar']);
        }
    }
}
