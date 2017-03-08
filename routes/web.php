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
Route::get('/testbot1','telegramtestcontroller@sendtext');

Route::get('/set', function () {
    $res = Telegram::setWebhook([
        'url' => 'https://hamyad.herokuapp.com/376682828:AAE79WT571kMAmUk77iD3KJIni0ml7xixEs/webhook'
    ]);
    dd($res);

});
//
//Route::post('/376682828:AAE79WT571kMAmUk77iD3KJIni0ml7xixEs/webhook', function () {
//
//    /** @var \Telegram\Bot\Objects\Update $update */
////    $update = Telegram::commandsHandler(true);
////    $chat_id=$update->getChat()->getId();
////    $text=$update->getText();
////    $this->replyWithMessage(
////        [
////            'text'=>$text,
////
////        ]);
//    $update = Telegram::getWebhookUpdates();
//    $message = $update->getMessage();
//    $keyboard = [
//        [
//            ['text'=>'google','url'=>'http://google.com']
//        ],
//        [
//            ['text'=>'google','url'=>'http://google.com']
//        ]
//    ];
//
//    $reply_markup = Telegram::replyKeyboardMarkup([
//        'inline_keyboard' => $keyboard,
//        'one_time_keyboard'=>true
//    ]);
//    if ($message !== null && $message->has('text')) {
////            $this->getCommandBus()->handler($message->getText(), $update);
//        $chat_id=$message->getChat()->getId();
//
//        $text=$message->getText();
//        Telegram::sendMessage(
//            [
//                'chat_id'=>$chat_id,
//                'text'=>$text,
//                'reply_markup' => $reply_markup
//            ]);
//    }
//
//    return 'ok';
//});

Route::post('/376682828:AAE79WT571kMAmUk77iD3KJIni0ml7xixEs/webhook','TelegramController@run');

Route::get('testdatabase',function (){
    return \App\Conversation::all();
});
Route::get('testdatabase1',function (){
    return \App\Data::all();
});
Route::get('test',function (){
    $dummy=\Config::get('majors.majors');
    $keyboard=[];
    foreach ($dummy as $key=>$value) {
        $keyboard[] = $key;
    }
    return $keyboard;
});

Route::get('test2',function (){
    $data1='111';
    return \Config::get("majors.$data1");
});
