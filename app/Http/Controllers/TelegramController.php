<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Data;
use Illuminate\Http\Request;
use App\Http\Controllers\Pdfcontroller;

class TelegramController extends Controller
{

    protected  $pdfcreator;

    public function _construct(Pdfcontroller $item)
    {
        $this->pdfcreator=$item;
    }

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
                                $dummy=\Config::get('majors.cities');
                                $keyboard=array();
                                foreach ($dummy as $key=>$value){
                                    $keyboard[][]=$key;
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
                                break;
                            case 3:
                                $array=array();
                                foreach (\Config::get('majors.cities') as $key=>$value){
                                    $array[]=$key;
                                }
                                if(!in_array($command,$array))
                                {
                                    $text='لطفا محل زندگی خود را وارد نمایید.';
                                    $dummy=\Config::get('majors.cities');
                                    $keyboard=array();
                                    foreach ($dummy as $key=>$value){
                                        $keyboard[][]=$key;
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
                                else{
                                    $data=new Data();
                                    $data->chat_id=$id;
                                    $data->state=3;
                                    $data1=\Config::get('majors.cities')[$command];
                                    $data->data=$data1;
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
                                }

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
                                    $keyboard=array();
                                    foreach ($dummy as $key=>$value){
                                        $keyboard[][]=$key;
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
                            case 5:
                                $array=array();
                                foreach (\Config::get('majors.majors') as $key=>$value){
                                    $array[]=$key;
                                }
                                if(!in_array($command,$array))
                                {
                                    $text='لطفا رشته تحصیلی  خود را انتخاب نمایید.';
                                    $dummy=\Config::get('majors.majors');
                                    $keyboard=array();
                                    foreach ($dummy as $key=>$value){
                                        $keyboard[][]=$key;
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
                                else{
                                    $data=new Data();
                                    $data->chat_id=$id;
                                    $data->state=5;
                                    $data1=\Config::get('majors.majors')[$command];
                                    $data->data=$data1;
                                    $data->save();
                                    $conversation->state=6;
                                    $conversation->save();
                                    $text='لطفا میزان تحصیلات خود را انتخاب نمایید.';
                                    $keyboard=[
                                      ['زیر دیپلم','دیپلم','کارشناسی'],['کارشناسی ارشد','دکتری','فوق دکتری']
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
                                break;
                            case 6:
                                $array=['زیر دیپلم','دیپلم','کارشناسی','کارشناسی ارشد','دکتری','فوق دکتری'];
                                if(!in_array($command,$array))
                                {
                                    $text='لطفا میزان تحصیلات خود را انتخاب نمایید.';
                                    $keyboard=[
                                        ['زیر دیپلم','دیپلم','کارشناسی'],['کارشناسی ارشد','دکتری','فوق دکتری']
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
                                    $data->state=6;
                                    $data->data=$command;
                                    $data->save();
                                    $conversation->state=7;
                                    $conversation->save();
                                    $text='لطفا فیلد اصلی کار  خود را انتخاب نمایید.';
                                    $dummy=\Config::get('majors.majors');
                                    $keyboard=array();
                                    foreach ($dummy as $key=>$value){
                                        $keyboard[][]=$key;
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
                            case 7:
                                $array=array();
                                foreach (\Config::get('majors.majors') as $key=>$value){
                                    $array[]=$key;
                                }
                                if(!in_array($command,$array))
                                {
                                    $text='لطفا فیلد اصلی کار  خود را انتخاب نمایید.';
                                    $dummy=\Config::get('majors.majors');
                                    $keyboard=array();
                                    foreach ($dummy as $key=>$value){
                                        $keyboard[][]=$key;
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
                                else{
                                    $data=new Data();
                                    $data->chat_id=$id;
                                    $data->state=7;
                                    $data1=\Config::get('majors.majors')[$command];
                                    $data->data=$data1;
                                    $data->save();
                                    $conversation->state=8;
                                    $conversation->save();
                                    $text='لطفا زمینه دقیق شغلی  خود را انتخاب نمایید.';
                                    $data1=(int)$data1;
                                    $submajor=\Config::get("majors.$data1");
                                    $keyboard=array();
                                    foreach ($submajor as $value){
                                        $keyboard[][]=$value;
                                    }
//                                    $keyboard=[['salam']];
                                    $reply_markup = \Telegram::replyKeyboardMarkup([
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
                            case 8:
                                $data1=Data::where(['chat_id'=>$id,'state'=>7])->first()->data;
                                $submajor=\Config::get('majors.'.$data1);
                                if(!in_array($command,$submajor))
                                {
                                    $text='لطفا زمینه دقیق شغلی  خود را انتخاب نمایید.';
                                    $submajor=\Config::get('majors.'.$data1);
                                    $keyboard=array();
                                    foreach ($submajor as $key=>$value){
                                        $keyboard[][]=$key;
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
                                else{
                                    $data=new Data();
                                    $data->chat_id=$id;
                                    $data->state=8;
                                    $data->data=$command;
                                    $data->save();
                                    $conversation->state=9;
                                    $conversation->save();
                                    $text='لطفا آخرین دانشگاه خود را وارد نمایید.';
                                    \Telegram::sendMessage(
                                        [
                                            'chat_id'=>$chat_id,
                                            'text'=>$text,
                                        ]);
                                }
                                break;
                            case 9:
                                $data=new Data();
                                $data->chat_id=$id;
                                $data->state=9;
                                $data->data=$command;
                                $data->save();
                                $conversation->state=10;
                                $conversation->save();
                                $text='در صورت تمایل تعداد سال هایی که مشغول به کار هستید را وارد نمایید.';
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
                            case 10:
                                if($command=='مایل نیستم'){
                                    $data1='نامعلوم';
                                }
                                else{
                                    $data1=$command;
                                }
                                $data=new Data();
                                $data->chat_id=$id;
                                $data->state=10;
                                $data->data=$data1;
                                $data->save();
                                $conversation->state=11;
                                $conversation->save();
                                $text='لطفا شماره تماس همراه خود را وارد نمایید.';
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
                            case 11:
                                $data=new Data();
                                $data->chat_id=$id;
                                $data->state=11;
                                $data->data=$command;
                                $data->save();
                                $conversation->state=12;
                                $conversation->save();
                                $text='از اینکه رزومه خود را در جاب یار کامل کرده اید متشکریم .';
                                $keyboard = [
                                    ['دریافت رزومه'],
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
                            case 12:
                                $keyboard = [
                                    [
                                        ['text'=>'دریافت رزومه','url'=>'http://hamyad.herokuapp.com/getresume/'.$id]
                                    ],
                                ];

                                $reply_markup =  \Telegram::replyKeyboardMarkup([
                                    'inline_keyboard' => $keyboard,
                                    'one_time_keyboard'=>true
                                ]);
                                \Telegram::sendMessage(
                                    [
                                        'chat_id'=>$chat_id,
                                        'text'=>'برای دریافت رزومه خود در قالب PDF به لینک زیر مراجعه نمایید.',
                                        'reply_markup' => $reply_markup
                                    ]);

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
