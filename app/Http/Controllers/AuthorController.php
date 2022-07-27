<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    public function allAuthors(){
        return response()->json(['dados' => Author::all()]);
    }

    public function store(Request $request){
        $author = new Author;
        $author->fill($request->all());

        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
        ], [
            'firstname.required' => 'O campo de nome é obrigatório',
            'lastname.required' => 'O campo de sobrenome é obrigatório',
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'Dados inválidos', 'response' => $validator->messages()]);
        }

        // try { 
        //     $author->save();
        //     return response()->json(['status' => 'ok', 'response' => 'Cadastrado com sucesso']);
        // } catch(\Illuminate\Database\QueryException $erro){ 
        //     return response()->json(['status' => 'erro', 'response' => 'json_encode($erro->getMessage())']);
        // }
        try {
            if($author->save())
                return response()->json(['status' => 'Sucesso', 'response' => 'Cadastrado com sucesso']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'Erro', 'response' => 'json_encode($erro->getMessage())']);
        }
    }
}
