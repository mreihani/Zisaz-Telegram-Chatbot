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
        $first_name = $this->telegram->FirstName();

        $text = '
سلام ' . $first_name . ' عزیز، به ربات کمک مهندسی زی ساز خوش آمدید 👋

📌 لطفا برای استفاده از ربات یکی از موارد زیر را انتخاب کنید

🆔 @zisazbot
            ';


        $this->sendMessageWithKeyBoard($this->telegram, $text);
    }
} 