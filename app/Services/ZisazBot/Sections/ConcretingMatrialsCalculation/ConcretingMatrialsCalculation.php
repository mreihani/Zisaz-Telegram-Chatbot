<?php

namespace App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;

class ConcretingMatrialsCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات مصالح مورد نیاز برای بتن ریزی انجام می شود. 
            
برای ادامه یکی از موارد زیر را انتخاب نمایید:  
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('محاسبه مصالح لازم برای بتن ریزی', '', '/concreting')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('محاسبه مصالح لازم برای بتن ریزی ستون ها', '', '/columnconcreting')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }
} 

