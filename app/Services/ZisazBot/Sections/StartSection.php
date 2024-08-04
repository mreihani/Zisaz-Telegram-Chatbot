<?php

namespace App\Services\ZisazBot\Sections;

use App\Services\ZisazBot\ZisazBot;

class StartSection extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $chat_id = $this->telegram->ChatID();

        $text = '
به ربات کمک مهندسی زی ساز خوش آمدید!

✔ این ربات برای محاسبات سریع کارگاهی طراحی شده است.

✔ دقت این ابزارها به دقت اطلاعات ورودی شما وابسته است. 

✔ برای کسب نتایج دقیق می‌توانید اطلاعات واقعی را وارد کنید و از نتایج فوق‌العاده دقیق آن لذت ببرید.
 
✔ در صورت استفاده از اعداد پیش‌فرض، نتایج محاسبات تا 85 الی 90 درصد دقت دارد. 

✔ با توجه به تنوع شرایط اجرای پروژه‌ها و تلورانس‌های اجرایی در پروژه‌ها، مسئولیت محاسبات دقیق بر عهده مجری پروژه می‌باشد. 

⚠ کلیه حقوق مادی و معنوی این ربات متعلق به شرکت مهندسی و املاک جابان با برند (زی ساز) می‌باشد. 
⤵
            ';


        $this->sendMessageWithKeyBoard($this->telegram, $text);
    }
} 