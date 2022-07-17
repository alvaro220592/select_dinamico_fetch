<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function allAuthors(){
        return response()->json(['dados' => Author::all()]);
    }

    public function store(Request $request){
        $author = new Author;
        $author->fill($request->all());
        
        if($author->save()){
            return response()->json(['status' => 'ok', 'response' => 'Cadastrado com sucesso']);
        }else{
            return response()->json(['status' => 'error', 'response' => 'Erro ao cadastrar']);
        }
    }
}
