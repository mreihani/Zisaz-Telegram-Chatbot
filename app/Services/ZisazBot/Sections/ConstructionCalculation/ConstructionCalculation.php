<?php

namespace App\Services\ZisazBot\Sections\ConstructionCalculation;

use App\Services\ZisazBot\ZisazBot;

class ConstructionCalculation extends ZisazBot {

    public $telegram;

    public function __construct($telegram) {
        $this->telegram = $telegram;
    }

    public function displayItem() {
        $text = '
            توضیحات مربوط به بخش محاسبه زیر بنا
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/getmenu')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }
} 