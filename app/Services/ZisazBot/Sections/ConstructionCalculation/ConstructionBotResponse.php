<?php

namespace App\Services\ZisazBot\Sections\ConstructionCalculation;

use PDF;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionCalculation;

class ConstructionBotResponse extends ConstructionCalculation {

    public $telegram;
    public $latestAction;
    public $construction;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->construction = $this->latestAction->construction->first();
    }

    public function processParameterSubmission() {
        if(empty($this->construction->c)) {
            return $this->sendPamameterCText();
        } elseif(empty($this->construction->m)) {
            // return $this->sendPamameterMText();
        } elseif(empty($this->construction->a)) {
            // return $this->sendPamameterAText();
        } else {
            // return $this->displayFinalResults();
        }
    }

    public function sendPamameterCText() {
        try {
            $text = 'نام شهر را بنویسید';
            $option = array( 
                // First row
                array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/getmenu')), 
            );
            $keyb = $this->telegram->buildInlineKeyBoard($option);
            $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        } catch (\Exception $e) {
            // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }
   
    public function sendPamameterMText() {
        try {
            $text = 'موقعیت قرارگیری ملک را انتخاب نمایید';
            $option = array( 
                // First row
                array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/getmenu')), 
            );
            $keyb = $this->telegram->buildInlineKeyBoard($option);
            $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        } catch (\Exception $e) {
            // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }
}