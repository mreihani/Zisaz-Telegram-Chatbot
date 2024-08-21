<?php

namespace App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;

class RampAndExpansionJointCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات رمپ و درز انقطاع انجام می شود. 
            
برای ادامه یکی از موارد زیر را انتخاب نمایید:  
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('محاسبه شیب رمپ', '', '/rampsteep')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('محاسبه طول رمپ', '', '/ramplength')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('درز انقطاع', '', '/expansionjoint')), 
            // Fourth row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }
} 

