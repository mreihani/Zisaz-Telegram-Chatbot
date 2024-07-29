<?php

namespace App\Services\ZisazBot\Sections\ConstructionCalculation\Sections;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;

class ConstCalcArea extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
    }

    public function displayItem() {
       
        $text = '
            محاسبات زیربنا 
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('محاسبه زیربنا', '', '/getconstcalcarea')), 

            // Fourth row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/getconstractioncalculation')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }
} 