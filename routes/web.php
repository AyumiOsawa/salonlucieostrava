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

// Routes Booking
Route::post('/home/timeslot/block', 'BookingViewController@block');
Route::get('/home/timeslot/create', 'BookingViewController@create');
Route::post('/home/timeslot/create', 'BookingViewController@store');
Route::get('/home/timeslot/{id}', 'BookingViewController@show')->name('booking.details');
Route::get('/home/timeslot/edit/{id}', 'BookingViewController@edit');
Route::put('/home/timeslot/edit/{id}', 'BookingViewController@update');
Route::get('/home/timeslot/delete_confirm/{id}', 'BookingViewController@deleteConfirmation');
Route::delete('/home/timeslot/delete_confirm/{id}', 'BookingViewController@destroy');


//Routes Treatment
Route::get('/treatment', 'TreatmentController@index')->name('treatmentindex');
Route::post('/treatment', 'TreatmentController@store');
Route::get('/treatment/edit/{id}','TreatmentController@edit')->middleware('auth');
Route::put('/treatment/update','TreatmentController@update');
Route::delete('/treatment', 'TreatmentController@remove' );

//Routes Stylist
Route::get('/stylist','StylistController@index')->middleware('can:admin');
