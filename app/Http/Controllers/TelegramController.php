<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function run()
    {
        $update = \Telegram::getWebhookUpdates();
        $message = $update->getMessage();
        $keyboard = [
            [
                ['text'=>'google','url'=>'http://google.com']
            ],
            [
                ['text'=>'google','url'=>'http://google.com']
            ]
        ];

        $reply_markup = \Telegram::replyKeyboardMarkup([
            'inline_keyboard' => $keyboard,
            'one_time_keyboard'=>true
        ]);
        if ($message !== null && $message->has('text')) {
            $chat_id=$message->getChat()->getId();
            $text=$message->getText();
            \Telegram::sendMessage(
                [
                    'chat_id'=>$chat_id,
                    'text'=>$text,
                    'reply_markup' => $reply_markup
                ]);
        }

        return 'ok';
        }
}
