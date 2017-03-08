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
            $id=$update->getMessage()->getContact()->getUserId();
            $text=$message->getText();
            if($text=='/start'){
                $conversation=Conversation::where('chat_id',$id)->first();
                if(is_null($conversation)){
                    $text='no data available';
                    $con=new Conversation();
                    $con->chat_id=$id;
                    $con->state='0';
                    $con->save();
                    $text=[
                        'سلام به بات جاب یار خوش آمدید.',
                        'براش شروع از دستور /begin استفاده نمایید.',
                    ];

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
        }

        return 'ok';
        }
}
