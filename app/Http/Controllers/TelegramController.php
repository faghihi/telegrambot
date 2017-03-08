<?php

namespace App\Http\Controllers;

use App\Conversation;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function run()
    {
        $update = \Telegram::getWebhookUpdates();
        $message = $update->getMessage();
//        $keyboard = [
//            [
//                ['text'=>'google','url'=>'http://google.com']
//            ],
//            [
//                ['text'=>'google','url'=>'http://google.com']
//            ]
//        ];
//
//        $reply_markup = \Telegram::replyKeyboardMarkup([
//            'inline_keyboard' => $keyboard,
//            'one_time_keyboard'=>true
//        ]);
        if ($message !== null && $message->has('text')) {
            $chat_id=$message->getChat()->getId();
            $check=0;
            $text=$message->getText();
            if($text=='/start'){
                $id=$message->getFrom()->getId();
                $conversation=Conversation::where('chat_id',$id)->first();
                if(is_null($conversation)){
                    $text='no data available';
                    $con=new Conversation();
                    $con->chat_id=$id;
                    $con->state='0';
                    $con->save();
                    $text=
                        'سلام به بات جاب یار خوش آمدید.';
                    $check=1;

                }
                else{
                    $text=$conversation->state;
                }
            }
            \Telegram::sendMessage(
                [
                    'chat_id'=>$chat_id,
                    'text'=>$text,
//                    'reply_markup' => $reply_markup
                ]);
            if($check){
                \Telegram::sendMessage(
                    [
                        'chat_id'=>$chat_id,
                        'text'=>'برای شروع از دستور /begin  استفاده نمایید.',
//                    'reply_markup' => $reply_markup
                    ]);
            }
        }

        return 'ok';
        }
}
