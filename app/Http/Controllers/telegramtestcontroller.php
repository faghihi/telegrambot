<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;
use Illuminate\Http\Request;

class telegramtestcontroller extends Controller
{
    public function index()
    {
        $telegram = new Api();
        $response = $telegram->getMe();

        $botId = $response->getId();
        $firstName = $response->getFirstName();
        $username = $response->getUsername();

        return $firstName;
    }

    public function sendtext()
    {
        $telegram = new Api();

        $response = $telegram->sendMessage([
            'chat_id' => 'CHAT_ID',
            'text' => 'Hello World'
        ]);

        $messageId = $response->getMessageId();
        return $messageId;
    }
}
