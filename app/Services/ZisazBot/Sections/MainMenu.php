<?php

namespace App\Services\ZisazBot\Sections;

use App\Services\ZisazBot\ZisazBot;

class MainMenu extends ZisazBot {

    public $telegram;

    public function __construct($telegram) {
        $this->telegram = $telegram;
    }

    public function displayItem() {
        $text = '
            لطفا نوع درخواست خود را انتخاب نمایید
        ';

        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('1- محاسبات زیربنا، هزینه، مشارکت در ساخت', '', '/getconstractioncalculation')), 
            // Second row 
            array($this->telegram->buildInlineKeyBoardButton('2- سقف تیرچه و بلوک', '', '/getbeamandblockroof')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('3- دیوار چینی', '', '/getbuildingwall')), 
            // Fourth row
            array($this->telegram->buildInlineKeyBoardButton('4- محاسبات  رمپ و درز انقطاع', '', '/getrampcutseamandjoin')), 
            // Fifth row
            array($this->telegram->buildInlineKeyBoardButton('5- وزن میلگرد و خاموت', '', '/getweightofrebarandstirrup')), 
            // Sixth row
            array($this->telegram->buildInlineKeyBoardButton('6- محاسبه مصالح بتون ریزی', '', '/getconcretematerials')), 
            // Seventh row
            array($this->telegram->buildInlineKeyBoardButton('7- مصالح نما و کف ساختمان', '', '/elevationandfloormaterials')), 
            // Eighth row
            array($this->telegram->buildInlineKeyBoardButton('پشتیبانی', '', '/getsupport'), $this->telegram->buildInlineKeyBoardButton('پیشنهادات', '', '/getsuggestion')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }
} 