<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    //Doctors Routes
    Route::resource('doctors','App\Http\Controllers\DoctorController')->middleware('isAdmin');
    
    //Patients Routes
    Route::resource('patients','App\Http\Controllers\PatientController')->middleware('isAdmin');
    
    //Patients Routes
    Route::resource('appointments','App\Http\Controllers\AppointmentController');
    
    Route::post('appointments/changeStatus', 'App\Http\Controllers\AppointmentController@changeStatus')->name('appointments.changeStatus')->middleware('isAdminOrDoctor');
    
});

//Guest Routes
Route::get('guestRegistration', 'App\Http\Controllers\HomeController@create')->name('guest.create');
Route::post('guestReister', 'App\Http\Controllers\HomeController@store')->name('guest.store');




