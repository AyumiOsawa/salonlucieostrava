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
Route::get('/',function(){
    return redirect('/react/home');
});

// ######  Admin Side  #####
Route::get('/home', 'HomeController@index')->name('home');

// Routes Calendar
Route::get('/home/calendar/{month?}', 'CalendarViewController@show')->name('calendar');

// Routes Booking
Route::get('/home/timeslot/show/{year}/{month}/{day}', 'BookingViewController@index');

Route::post('/home/timeslot/block', 'BookingViewController@block');
Route::post('/home/timeslot/create', 'BookingViewController@store');

Route::get('/home/timeslot/create-date/{timeslot?}', 'BookingViewController@create');
Route::post('/home/timeslot/create-date/{timeslot?}', 'BookingViewController@postCreate');
Route::get('/home/timeslot/create-time', 'BookingViewController@createTime');
Route::post('/home/timeslot/create-time', 'BookingViewController@postCreateTime');
Route::get('/home/timeslot/confirm', 'BookingViewController@createBooking');
Route::post('/home/timeslot/confirm', 'BookingViewController@postCreateBooking');


Route::get('/home/timeslot/edit-date/{id}', 'BookingViewController@edit');
Route::post('/home/timeslot/edit-date', 'BookingViewController@postEdit');
Route::get('/home/timeslot/edit-time', 'BookingViewController@editTime');
Route::post('/home/timeslot/edit-time', 'BookingViewController@postEditTime');
Route::get('/home/timeslot/edit-confirm', 'BookingViewController@editBooking');
Route::put('/home/timeslot/edit-confirm', 'BookingViewController@postEditBooking');
// Route::put('/home/timeslot/edit/{id}', 'BookingViewController@update');
Route::get('/home/timeslot/delete_confirm/{id}', 'BookingViewController@deleteConfirmation');
Route::delete('/home/timeslot/delete_confirm/{id}', 'BookingViewController@destroy');
Route::get('/home/timeslot/{id}', 'BookingViewController@show')->name('booking.details');

//Routes Treatment
Route::get('/treatment', 'TreatmentController@index')->name('treatmentindex');
Route::post('/treatment', 'TreatmentController@store');
Route::get('/treatment/edit/{id}','TreatmentController@edit')->middleware('stylist');
Route::put('/treatment/update','TreatmentController@update');
Route::delete('/treatment', 'TreatmentController@remove' );

//Routes Stylist
Route::get('/stylist','StylistController@index')->middleware('can:admin');
Route::get('/stylist/create', 'StylistController@create')->middleware('can:admin');
route::post('/stylist', 'StylistController@store')->middleware('can:admin');
Route::get('/stylist/edit/{id}', 'StylistController@edit')->middleware('can:admin');
Route::put('/stylist/update','StylistController@update')->middleware('can:admin');
Route::delete('stylist', 'StylistController@remove')->middleware('can:admin');
