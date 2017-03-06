<?php

namespace App\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;


class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "start";

    /**
     * @var string Command Description
     */
    protected $description = "برای شروع";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.

        // This will update the chat status to typing...
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        // This will prepare a list of available commands and send the user.
        // First, Get an array of all registered commands
        // They'll be in 'command-name' => 'Command Handler Class' format.
        $commands = $this->getTelegram()->getCommands();

        // Build the list
        $response = '';
        foreach ($commands as $name => $command) {
            $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
        }

        // Reply with the commands list
        $this->replyWithMessage(['text' => $response]);
//        $keyboard = [
//            ['7', '8', '9'],
//            ['4', '5', '6'],
//            ['1', '2', '3'],
//            ['0']
//        ];
//
//        $reply_markup =  $this->getTelegram()->replyKeyboardMarkup([
//            'keyboard' => $keyboard,
//            'resize_keyboard' => true,
//            'one_time_keyboard' => true
//        ]);
        $keyboard = [
            [
                ['text'=>'google','url'=>'http://google.com']
            ],
            [
                ['text'=>'google','url'=>'http://google.com']
            ]
        ];

        $reply_markup =  $this->getTelegram()->replyKeyboardMarkup([
            'inline_keyboard' => $keyboard,
            'one_time_keyboard'=>true
        ]);

        $this->replyWithMessage(
            [
                'text'=>'hello ',
                'reply_markup' => $reply_markup
            ]);

        sleep(20);
        $update=$this->getTelegram()->getWebhookUpdates();
        $chat_id=$update->getMessage()->getChat()->getId();
        $text2=$update->recentMessage()->getText();
        $text=$update->getMessage()->getText();
        $update->getMessage()->getMessageId();

        $this->replyWithMessage(
            [
                'text'=>$text2,

            ]);

//        $this->getTelegram()->replyKeyboardMarkup(['keyboard'=>['test','test2']]);


        // Trigger another command dynamically from within this command
        // When you want to chain multiple commands within one or process the request further.
        // The method supports second parameter arguments which you can optionally pass, By default
        // it'll pass the same arguments that are received for this command originally.
//        $this->triggerCommand('subscribe');
    }
}