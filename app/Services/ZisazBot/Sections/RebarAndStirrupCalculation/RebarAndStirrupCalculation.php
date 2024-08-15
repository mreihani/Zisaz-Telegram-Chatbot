<?php

namespace App\Services\ZisazBot\Sections\RebarAndStirrupCalculation;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;

class RebarAndStirrupCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات وزن میلگرد و خاموت انجام می شود. 
            
برای ادامه یکی از موارد زیر را انتخاب نمایید:  
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('محاسبه وزن یک شاخه میلگرد', '', '/rebarweight')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('محاسبه وزن خاموت', '', '/stirrupweight')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('معادل سازی میلگرد', '', '/rebarconversion')), 
            // Fourth row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }
} 

