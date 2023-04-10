<?php

use App\Http\Controllers\FormBuilder;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/form-create', [FormBuilder::class,'showBuilder'])->name('app.home');

Route::get('/show-form', [FormBuilder::class,'showForm'])->name('show.form');
Route::post('/save-form', [FormBuilder::class,'saveForm'])->name('save.form');
Route::post('/submit-form', [FormBuilder::class,'handleFormRequest'])->name('submit.form');
Route::delete('/field/{id}', [FormBuilder::class,'delete'])->name('field.destroy');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
});

require __DIR__.'/auth.php';
