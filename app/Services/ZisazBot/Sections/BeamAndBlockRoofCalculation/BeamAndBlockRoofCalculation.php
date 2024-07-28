<?php

namespace App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;

class BeamAndBlockRoofCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = !empty($telegram->ChatID()) ? User::where('chat_id', $telegram->ChatID())->first() : null;
    }

    public function displayItem() {
       
        $text = '
            مساحت کل سقف را به متر مربع وارد نمایید
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/getmenu')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }
} 