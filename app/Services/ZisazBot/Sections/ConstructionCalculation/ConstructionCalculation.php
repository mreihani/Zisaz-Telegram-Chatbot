<?php

namespace App\Services\ZisazBot\Sections\ConstructionCalculation;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;

class ConstructionCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
    }

    public function displayItem() {
       
        $text = '
            محاسبات زیربنا، هزینه و  مشارکت در ساخت  
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('محاسبه زیربنا', '', '/getconstcalcarea')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('محاسبه هزینه ساخت', '', '/getconstcalcexpenses')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('نسبت منصفانه مشارکت در ساخت', '', '/getconstcalccollaborative')), 
            // Fourth row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/getmenu')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        // $this->initializeAction(new ());
    }
} 