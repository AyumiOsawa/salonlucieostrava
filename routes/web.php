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

Route::get('/react/{path?}', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/',function(){
    return redirect('/react');
});

Route::get('/home/calendar', 'CalendarViewController@index');
Route::get('/home/calendar/{id}', 'CalendarViewController@show')->name('calendar');


Route::get('/home/timeslot/{id}', 'BookingViewController@show')->name('booking.details');
Route::get('/home/timeslot/edit/{id}', 'BookingViewController@edit');
Route::put('/home/timeslot/edit/{id}', 'BookingViewController@eupdate');

Route::get('/home/timeslot/isDelete/{id}', 'BookingViewController@deleteConfirmation');


//Routes Treatment
Route::get('/treatment', 'TreatmentController@index')->name('treatmentindex');
Route::post('/treatment', 'TreatmentController@store');
//Routes Stylist
