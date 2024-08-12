<?php

namespace App\Services\ZisazBot\Sections\BrickWallMasonryCalculation;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\BrickWallMasonry\BrickWallMasonry;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryApartmentBlock\BrickWallMasonryApartmentBlockService;

class BrickWallMasonryCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات دیوار چینی بلوکی و آجری انجام می شود. 
            
برای ادامه یکی از موارد زیر را انتخاب نمایید:  
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('دیوار پارتیشن بلوکی آپارتمان', '', '/brickwallmasonryapartmentblock')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('دیوار بلوکی حصار باغ یا حیاط', '', '/brickwallmasonrygarden')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('دیوار چینی آجر فشاری و سه گل', '', '/brickwallmasonrypressedbrick')), 
            // Fourth row
            array($this->telegram->buildInlineKeyBoardButton('دیوار چینی آجر پارتیشن', '', '/brickwallmasonrypartition')), 
            // Fifth row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }
} 

