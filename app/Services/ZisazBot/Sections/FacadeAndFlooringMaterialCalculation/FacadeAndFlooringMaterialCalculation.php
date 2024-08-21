<?php

namespace App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;

class FacadeAndFlooringMaterialCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات مصالح نما و کف سازی انجام می شود. 
            
برای ادامه یکی از موارد زیر را انتخاب نمایید:  
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('برآورد مصالح مورد نیاز سنگ نما', '', '/decorativestone')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('برآورد مصالح مورد نیاز کاشی بدنه', '', '/bodytile')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('برآورد مصالح مورد نیاز سرامیک کف', '', '/ceramic')), 
            // Fourth row
            array($this->telegram->buildInlineKeyBoardButton('برآورد مصالح مورد نیاز موزائیک کف', '', '/mosaic')), 
            // Fifth row
            array($this->telegram->buildInlineKeyBoardButton('برآورد مصالح مورد نیاز سیمانکاری زبره (آستر)', '', '/cementing')), 
            // Sixth row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }
} 

