<?php

namespace App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampLength;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\Ramp\RampLength;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampLength\RampLengthValidation;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampLength\RampLengthBotResponse;

class RampLengthCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات طول رمپ انجام می شود. 
            
اطلاعات مورد نیاز:
1- ارتفاع رمپ
2- شیب رمپ
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/ramplengthsendpamameterhtext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new RampLength());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $rampLength = $latestAction->rampLength->first();

            $rampLengthBotResponse = new RampLengthBotResponse($this->telegram);
            $rampLengthValidation = new RampLengthValidation($this->telegram);

            if(empty($rampLength->h)) {
                
                // user input validation for numeric values
                $rampLengthValidation->isNumericValidation($text, 'h');
                // user input validation for positive integer values
                $rampLengthValidation->isPositiveInteger($text, 'h');
                // user input validation for not zero entries
                $rampLengthValidation->isNotZero($text, 'h');

                $rampLengthObj = $latestAction->rampLength()->create([
                    'h' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $rampLengthObj->id,
                ]);

                $rampLengthBotResponse->sendPamameterSText();

            } elseif(empty($rampLength->s)) {
                
                // user input validation for numeric values
                $rampLengthValidation->isNumericValidation($text, 's');
                // user input validation for specific number span
                $rampLengthValidation->isBetween($text, 's', [0, 100]);

                $latestAction->rampLength()->update([
                    's' => !empty($text) ? $text : null,
                ]);

                $rampLengthBotResponse->displayFinalResults();
            } 

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $rampLength = $this->user->actions->flatMap->rampLength->first();
        
        if(!is_null($rampLength)) {
            $rampLength->delete();
        }

        return $this->displayItem();
    }
} 

