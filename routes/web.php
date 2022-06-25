<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('my_layout/mainlayout');
})->middleware(['auth'])->name('dashboard');

Route::get('/demo', function () {
    return view('demo');
 });

//  Route::get('/company_page', function () {
//     return view('my_layout/company_page');
//  })->name('company_page');

 Route::resource('companies', CompanyController::class);
 Route::get('/companies', [CompanyController::class,'index'])->name('companies');
 Route::get('/company_list', [CompanyController::class,'show'])->name('company_list');
 Route::get('/company_edit', [CompanyController::class,'edit'])->name('company_edit');
 Route::get('/company_delete/{id}', [CompanyController::class,'destroy'])->name('company_delete');

 Route::get('/companies/create', [CompanyController::class,'create'])->name('create');




 

//  Route::resource('company','CompanyController')->name('company');


require __DIR__.'/auth.php';
