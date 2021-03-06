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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::group(['middleware' => 'language'], function () {
    /*------------ LANGUAGES ------------*/
    Route::get('language/{lang}', function ($lang) {
        Session::put('locale', $lang);

        return redirect()->back();
    });

    Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);

    Route::group(['prefix' => 'admin'], function () {
        //Route::auth();
        Auth::routes();
    });


    /*------------ GUEST ------------*/
    Route::get('/', function () {
        if (Session::has('guest_id')) {
            $services = \App\Services::where('is_active', 1)->get();

            return view('guest.dashboard', compact('services'));
        } else {
            return view('loginGuest');
        }
    });

    Route::get('changeStatus', 'GuestController@changeStatus');
    Route::get('seeStatus/{id?}', 'GuestController@seeStatusGuest');

    //Lista de órdenes de un guest
    Route::get('orderListRestaurant', 'RestaurantController@orderList');

    Route::get('logout', 'CodeController@logout');

    Route::get('services', 'TranslatorAppController@services');


    Route::get('trips', function () {
        $trips = \App\TripType::get();

        return $trips;
    });
    Route::get('events', function () {
        $events = \App\EventType::get();

        return $events;
    });
    Route::get('/logout', 'CodeController@logout');


    Route::post('/', 'CodeController@login');
    //Route::get('/login', 'CodeController@login');

    /*Route::get('dashboard', function () {
        $services = \App\Services::all();

        return view('guest.dashboard', compact('services'));
    });*/
    /*------------ END GUEST ------------*/


    /*------------ ADMIN ------------*/
    Route::get('admin', 'HomeController@index')->name('admin');
    Route::get('admin/dashboard', 'AdminDashboardController@index');
    Route::get('admin/checkin', 'AdminDashboardController@checkin');
    // STATISTICS
    Route::get('admin/statistics', 'AdminDashboardController@statistics');
    Route::get('admin/checkout', 'AdminDashboardController@checkout');

    /* llamadas a las vistas - luego irán en los controllers */
    Route::get('admin/taxi', function () {
        return view('admin.taxi');
    });

    Route::get('admin/spa', function () {
        return view('admin.spa');
    });

    Route::get('admin/restaurant', function () {
        return view('admin.restaurant');
    });

    Route::get('admin/housekeeping', function () {
        return view('admin.housekeeping');
    });

    Route::get('admin/petCare', function () {
        return view('admin.petCare');
    });

    Route::get('admin/snacks', function () {
        return view('admin.snacks');
    });

    Route::get('admin/events', function () {
        return view('admin.events');
    });

    Route::get('admin/trips', function () {
        return view('admin.trips');
    });

    /* end llamadas a las vistas */

    Route::resource('admin/guests', 'GuestController');
    //----Filtro de búsqueda para reservas ------
    Route::get('admin/guests/roomType/{id}/adapted/{disabled_adapted}/jacuzzi/{jacuzzi}', 'GuestController@getAvailableRooms');
    Route::get('admin/guests/roomType/{id}/adapted/{disabled_adapted}', 'GuestController@getAvailableRooms');
    Route::get('admin/guests/roomType/{id}/jacuzzi/{jacuzzi}', 'GuestController@getAvailableRooms');
    Route::get('admin/guests/roomType/{id}', 'GuestController@getAvailableRooms');
    //-------------------------------------------
    /*------------ END ADMIN ------------*/


    /*------------- SERVICES --------------*/

    Route::resource('admin/service/restaurant', 'RestaurantController');
    Route::resource('admin/service/taxi', 'TaxiController');
    Route::resource('admin/service/alarm', 'AlarmController');
    Route::resource('admin/service/housekeeping', 'HousekeepingController');
    Route::resource('admin/service/spa', 'SpaAppointmentController');
    Route::resource('admin/service/event', 'EventController');
    Route::resource('admin/service/trip', 'TripController');
    Route::resource('admin/service/petcare', 'PetCareController');
    Route::resource('admin/service/snackdrink', 'SnacksAndDrinkController');

    /* --->   ------------- STATUS SERVICES --------------   */
    Route::get('admin/service/statusRestaurant/{id}', 'RestaurantController@changeStatus');
    Route::get('admin/service/statusSnacksAndDrinks/{id}', 'SnacksAndDrinkController@changeStatus');
    Route::get('admin/service/statusTaxi/{id}', 'TaxiController@changeStatus');
    Route::get('admin/service/statusAlarm/{id}', 'AlarmController@changeStatus');
    Route::get('admin/service/statusSpa/{id}', 'SpaAppointmentController@changeStatus');
    Route::get('admin/service/statusEvent/{id}', 'EventController@changeStatus');
    Route::get('admin/service/statusTrip/{id}', 'TripController@changeStatus');
    Route::get('admin/service/statusHousekeeping/{id}', 'HousekeepingController@changeStatus');
    Route::get('admin/service/statusPetcare/{id}', 'PetCareController@changeStatus');

    Route::get('test', function () {
        event(new App\Events\NewOrderRequest(4, 153, 1));

        return "Order has been sent!";
    });

});