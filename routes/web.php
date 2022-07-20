<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// ====== Views ======
Route::get('/', [HomeController::class, 'index']);
// ======================================================================================================

// ====== Autores ======
Route::get('allAuthors', [AuthorController::class, 'allAuthors']);
Route::resource('authors', AuthorController::class);
// ======================================================================================================

// ====== Livros 
// Traz todos os livros
Route::get('allBooks', [BookController::class, 'allBooks']);

// Traz todos os livros de um autor escolhido
Route::get('authorsBooks/{author_id}', [BookController::class, 'authorsBooks'])->name('authorsBooks');

// Traz informações do livro escolhido
Route::get('bookInfo/{book_id}', [BookController::class, 'bookInfo'])->name('bookInfo');
// ======================================================================================================

// Testes de envio de arquivo via fetch
Route::post('sendFile', [BookController::class, 'sendFile']);