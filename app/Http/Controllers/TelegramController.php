<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TelegramBotService;

class TelegramController extends Controller
{
    public function inbound(Request $request) {

        // send welcome message if first time message
        $chat_id = $request->message['from']['id'];
        $reply_to_message_id = $request->message['message_id'];
       
        if(!cache()->has("chat_id_{$chat_id}")) {
            $text = '
                به ربات  محاسبات کارگاهی ساخت و ساز  خوش آمدید .
                این ربات برای محاسبات سریع  کارگاهی طراحی  شده است
                دقت این ابزار ها به دقت اطلاعات ورودی  شما وابسته است
                برا ی کسب نتایج دقیق می توانید اطلاعات واقعی را  وارد کنید 
                و از نتایج فوق العاده دقیق آن  لذت ببرید .
                در صورت استفاده از اعداد پیش فرض  ، نتایج محاسبات تا 85 الی 90 درصد دقت دارد .
                با توجه به تنوع  شرایط اجرای پروژه ها  و تلورانس های اجرایی در پروژه هاا، مسئولیت محاسبات دقیق بر عهده مجری پروژه می باشد.
                کلیه حقوق مادی و معنوی این ربات متعلق به شرکت مهندسی و املاک جابان با برند ( زی  ساز ) می باشد .
                برای دریافت خدمات بیشتر، در سامانه ساخت و ساز  عضو شوید
            ';

            cache()->put("chat_id_{$chat_id}", true, now()->addMinute(60));
        } else {
            $text = '
                لطفا نوع درخواست خود را انتخاب نمایید
            ';
        }

        $telegramBot = new TelegramBotService();
        $result = $telegramBot->sendMessage($text, $chat_id, $reply_to_message_id);

        return response()->json($result, 200);
    }
}
