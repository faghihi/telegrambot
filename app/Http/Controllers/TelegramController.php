<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Data;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    /**
     * @return string
     */
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
            $command=$message->getText();
            if($command=='/start'){
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
                    \Telegram::sendMessage(
                        [
                            'chat_id'=>$chat_id,
                            'text'=>$text,
//                    'reply_markup' => $reply_markup
                        ]);

                }
                else{
                    $text='شما هم اکنون در میانه راه پر کردن رزومه می باشید با این دستور رزومه شما از ابتدا آغاز خواهد شد آیا موافقید ؟!';
                    $check=2;

                }
            }
            elseif($command=='/restart'){
                    $id=$message->getFrom()->getId();
                    $conversation=Conversation::where('chat_id',$id)->first();
                    if(is_null($conversation)){
                        $con=new Conversation();
                        $con->chat_id=$id;
                        $con->state='0';
                        $con->save();
                        $text=
                            'سلام به بات جاب یار خوش آمدید.';
                        $check=1;
                        \Telegram::sendMessage(
                            [
                                'chat_id'=>$chat_id,
                                'text'=>$text,
                            ]);

                    }
                    else{
                        $conversation=Conversation::find($conversation->id);
                        $conversation->delete();
                        $datas=Data::where('chat_id',$id)->get();
                        foreach ($datas as $data){
                            $data=Data::find($data->id);
                            $data->delete();
                        }
                        \Telegram::sendMessage(
                            [
                                'chat_id'=>$chat_id,
                                'text'=>'براش شروع مجدد از /start استفاده نمایید.',
                            ]);

                    }
                }
            else{
                    $id=$message->getFrom()->getId();
                    $conversation=Conversation::where('chat_id',$id)->first();
                    if(is_null($conversation)){
                        $con=new Conversation();
                        $con->chat_id=$id;
                        $con->state='0';
                        $con->save();
                        $text=
                            'سلام به بات جاب یار خوش آمدید.';
                        $check=1;
                        \Telegram::sendMessage(
                            [
                                'chat_id'=>$chat_id,
                                'text'=>$text,
                            ]);

                    }
                    else{
                        $conversation=Conversation::find($conversation->id);
                        $state=$conversation->state;
                        switch ($state){
                            case 0:
                                $text='لطفا نام خود را وارد نمایید.';
                                $conversation->state=1;
                                $conversation->save();
                                \Telegram::sendMessage(
                                    [
                                        'chat_id'=>$chat_id,
                                        'text'=>$text,
                                    ]);
                                break;
                            case 1:
                                $data=new Data();
                                $data->chat_id=$id;
                                $data->state=1;
                                $data->data=$command;
                                $data->save();
                                $text='اگر مایل هستید سن خود را وارد نمایید.';
                                $conversation->state=2;
                                $conversation->save();
                                $keyboard = [
                                    ['مایل نیستم'],
                                ];

                                $reply_markup =  \Telegram::replyKeyboardMarkup([
                                    'keyboard' => $keyboard,
                                    'resize_keyboard' => true,
                                    'one_time_keyboard' => true
                                ]);
                                \Telegram::sendMessage(
                                    [
                                        'chat_id'=>$chat_id,
                                        'text'=>$text,
                                        'reply_markup'=>$reply_markup
                                    ]);


                                break;
                            case 2:
                                if($command=='مایل نیستم'){
                                    $data1='نامعلوم';
                                }
                                else{
                                    $data1=$command;
                                }
                                $data=new Data();
                                $data->chat_id=$id;
                                $data->state=2;
                                $data->data=$data1;
                                $data->save();
                                $conversation->state=3;
                                $conversation->save();
                                $text='لطفا محل زندگی خود را وارد نمایید.';
                                $reply_markup =  \Telegram::replyKeyboardMarkup([
                                    'hide_keyboard' => true
                                ]);
                                \Telegram::sendMessage(
                                    [
                                        'chat_id'=>$chat_id,
                                        'text'=>$text,
                                        'reply_markup'=>$reply_markup
                                    ]);
                                break;
                            case 3:
                                $data=new Data();
                                $data->chat_id=$id;
                                $data->state=3;
                                $data->data=$command;
                                $data->save();
                                $conversation->state=4;
                                $conversation->save();
                                $text='لطفا جنسیت خود را انتخاب نمایید.';
                                $keyboard = [
                                    ['زن','مرد'],
                                ];

                                $reply_markup =  \Telegram::replyKeyboardMarkup([
                                    'keyboard' => $keyboard,
                                    'resize_keyboard' => true,
                                    'one_time_keyboard' => true
                                ]);
                                \Telegram::sendMessage(
                                    [
                                        'chat_id'=>$chat_id,
                                        'text'=>$text,
                                        'reply_markup'=>$reply_markup
                                    ]);
                                break;
                            case 4:
                                if($command!='مرد' && $command!='زن')
                                {
                                    $text='لطفا جنسیت خود را انتخاب نمایید.';
                                    $keyboard = [
                                        ['زن','مرد'],
                                    ];

                                    $reply_markup =  \Telegram::replyKeyboardMarkup([
                                        'keyboard' => $keyboard,
                                        'resize_keyboard' => true,
                                        'one_time_keyboard' => true
                                    ]);
                                    \Telegram::sendMessage(
                                        [
                                            'chat_id'=>$chat_id,
                                            'text'=>$text,
                                            'reply_markup'=>$reply_markup
                                        ]);
                                }
                                else{
                                    $data=new Data();
                                    $data->chat_id=$id;
                                    $data->state=4;
                                    $data->data=$command;
                                    $data->save();
                                    $conversation->state=5;
                                    $conversation->save();
                                    $text='لطفا رشته تحصیلی  خود را انتخاب نمایید.';
                                    $dummy=\Config::get('majors.majors');
                                    $keyboard=[];
                                    foreach ($dummy as $key=>$value){
                                        $keyboard[]=$key;
                                    }
                                    $reply_markup =  \Telegram::replyKeyboardMarkup([
                                        'keyboard' => $keyboard,
                                        'resize_keyboard' => true,
                                        'one_time_keyboard' => true
                                    ]);
                                    \Telegram::sendMessage(
                                        [
                                            'chat_id'=>$chat_id,
                                            'text'=>$text,
                                            'reply_markup'=>$reply_markup
                                        ]);
                                }

                                break;
                            default:
                                $text='nothing';
                                \Telegram::sendMessage(
                                    [
                                        'chat_id'=>$chat_id,
                                        'text'=>$text,
                                    ]);
                        }

                    }
                \Telegram::sendMessage(
                            [
                                'chat_id'=>$chat_id,
                                'text'=>$command,
                            ]);
            }
        }
            if($check==1){
                \Telegram::sendMessage(
                    [
                        'chat_id'=>$chat_id,
                        'text'=>'برای شروع از دستور /begin  استفاده نمایید.',
//                    'reply_markup' => $reply_markup
                    ]);
            }
            if($check==2){
                \Telegram::sendMessage(
                    [
                        'chat_id'=>$chat_id,
                        'text'=>' برای شروع جدید پر کردن رزومه  دستور /restart  استفاده نمایید. و برای ادامه از دستور /begin',
//                    'reply_markup' => $reply_markup
                    ]);
            }

        return 'ok';
        }
}
