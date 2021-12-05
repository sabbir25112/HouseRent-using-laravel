<?php

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

Route::get('/', 'HomeController@index')->name('welcome');

Route::get('/descending-order-houses-price', 'HomeController@highToLow')->name('highToLow');
Route::get('/ascending-order-houses-price', 'HomeController@lowToHigh')->name('lowToHigh');

Route::get('/search-result', 'HomeController@search')->name('search');
Route::get('/search-result-by-range', 'HomeController@searchByRange')->name('searchByRange');

Route::get('/houses/details/{id}', 'HomeController@details')->name('house.details');
Route::get('/sublet-houses/details/{id}', 'HomeController@subletHouseDetails')->name('sublet-house.details');
Route::get('/bachelor-houses/details/{id}', 'HomeController@bachelorHouseDetails')->name('bachelor-house.details');
Route::get('/all-available/houses', 'HomeController@allHouses')->name('house.all');
Route::get('/all-available/sublet-houses', 'HomeController@allSubletHouses')->name('sublet-house.all');
Route::get('/all-available/bachelor-houses', 'HomeController@allBachelorHouses')->name('bachelor-house.all');
Route::get('/available-houses/area/{id}', 'HomeController@areaWiseShow')->name('available.area.house');

Route::post('/house-booking/id/{id}', 'HomeController@booking')->name('booking');
Route::post('/bachelor-house-booking/id/{id}', 'HomeController@bachelorBooking')->name('bachelor-booking');
Route::post('/sublet-house-booking/id/{id}', 'HomeController@subletBooking')->name('sublet-booking');

Auth::routes(['verify' => false, 'reset' => false]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('auth/google', 'Auth\GoogleController@redirectToGoogle');

Route::get('auth/google/callback', 'Auth\GoogleController@handleGoogleCallback');

//admin

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']],
    function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::resource('area', 'AreaController');
        Route::resource('house', 'HouseController');
        Route::get('manage-landlord', 'HouseController@manageLandlord')->name('manage.landlord');
        Route::delete('manage-landlord/destroy/{id}', 'HouseController@removeLandlord')->name('remove.landlord');

        Route::get('manage-renter', 'HouseController@manageRenter')->name('manage.renter');
        Route::delete('manage-renter/destroy/{id}', 'HouseController@removeRenter')->name('remove.renter');

        Route::get('profile-info', 'SettingsController@showProfile')->name('profile.show');
        Route::get('profile-info/edit/{id}', 'SettingsController@editProfile')->name('profile.edit');
        Route::post('profile-info/update/', 'SettingsController@updateProfile')->name('profile.update');

        Route::get('booked-houses-list', 'BookingController@bookedList')->name('booked.list');
        Route::get('booked-houses-history', 'BookingController@historyList')->name('history.list');

    });

//landlord

Route::group(['as' => 'landlord.', 'prefix' => 'landlord', 'namespace' => 'Landlord', 'middleware' => ['auth', 'landlord']],
    function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::resource('area', 'AreaController');
        Route::resource('house', 'HouseController');
        Route::resource('sublet-house', 'SubletHouseController');
        Route::resource('bachelor-house', 'BachelorHouseController');

        Route::get('house/switch-status/{id}', 'HouseController@switch')->name('house.status');
        Route::get('sublet-house/switch-status/{id}', 'SubletHouseController@switch')->name('sublet-house.status');
        Route::get('bachelor-house/switch-status/{id}', 'BachelorHouseController@switch')->name('bachelor-house.status');

        Route::get('booking-request-list', 'BookingController@bookingRequestListForLandlord')->name('bookingRequestList');
        Route::post('booking-request/accept/{id}', 'BookingController@bookingRequestAccept')->name('request.accept');
        Route::post('booking-request/reject/{id}', 'BookingController@bookingRequestReject')->name('request.reject');
        Route::get('booking/history', 'BookingController@bookingHistory')->name('history');
        Route::get('booked/currently/renter', 'BookingController@currentlyStaying')->name('currently.staying');
        Route::post('renter/leave/{id}', 'BookingController@leaveRenter')->name('leave.renter');

        Route::get('profile-info', 'SettingsController@showProfile')->name('profile.show');
        Route::get('profile-info/edit/{id}', 'SettingsController@editProfile')->name('profile.edit');
        Route::post('profile-info/update/', 'SettingsController@updateProfile')->name('profile.update');
    });

//renter

Route::group(['as' => 'renter.', 'prefix' => 'renter', 'namespace' => 'Renter', 'middleware' => ['auth', 'renter']],
    function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        Route::get('areas', 'DashboardController@areas')->name('areas');

        Route::get('houses', 'DashboardController@allHouses')->name('allHouses');
        Route::get('bachelor-houses', 'DashboardController@allBachelorHouses')->name('allBachelorHouses');
        Route::get('sublet-houses', 'DashboardController@allSubletHouses')->name('allSubletHouses');
        Route::get('house/details/{id}', 'DashboardController@housesDetails')->name('houses.details');
        Route::get('bachelor-house/details/{id}', 'DashboardController@bachelorHousesDetails')->name('bachelor-houses.details');
        Route::get('sublet-house/details/{id}', 'DashboardController@subletHousesDetails')->name('sublet-houses.details');

        Route::get('profile-info', 'SettingsController@showProfile')->name('profile.show');
        Route::get('profile-info/edit/{id}', 'SettingsController@editProfile')->name('profile.edit');
        Route::post('profile-info/update/', 'SettingsController@updateProfile')->name('profile.update');

        Route::get('booking/history', 'DashboardController@bookingHistory')->name('booking.history');
        Route::get('pending/booking', 'DashboardController@bookingPending')->name('booking.pending');
        Route::post('pending/booking/cancel/{id}', 'DashboardController@cancelBookingRequest')->name('cancel.booking.request');

        Route::post('review', 'DashboardController@review')->name('review');
        Route::get('review-edit/{id}', 'DashboardController@reviewEdit')->name('review.edit');
        Route::post('review-update/{id}', 'DashboardController@reviewUpdate')->name('review.update');
    });
