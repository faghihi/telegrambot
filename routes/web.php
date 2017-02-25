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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/testbot','telegramtestcontroller@index');

Route::get('/set', function () {
    $res = Telegram::setWebhook([
        'url' => 'https://hamyad.herokuapp.com/376682828:AAE79WT571kMAmUk77iD3KJIni0ml7xixEs/webhook'
    ]);
    dd($res);

});

Route::post('376682828:AAE79WT571kMAmUk77iD3KJIni0ml7xixEs/webhook', function () {

    /** @var \Telegram\Bot\Objects\Update $update */
    $update = Telegram::commandsHandler(true);

    return 'ok';
});
