<?php

namespace App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampSteep;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\Ramp\RampSteep;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampSteep\RampSteepValidation;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampSteep\RampSteepBotResponse;

class RampSteepCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات شیب رمپ انجام می شود. 
            
اطلاعات مورد نیاز:
1- ارتفاع رمپ
2- طول رمپ
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/rampsteepsendpamameterhtext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new RampSteep());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $rampSteep = $latestAction->rampSteep->first();

            $rampSteepBotResponse = new RampSteepBotResponse($this->telegram);
            $rampSteepValidation = new RampSteepValidation($this->telegram);

            if(empty($rampSteep->h)) {
                
                // user input validation for numeric values
                $rampSteepValidation->isNumericValidation($text, 'h');
                // user input validation for positive integer values
                $rampSteepValidation->isPositiveInteger($text, 'h');
                // user input validation for not zero entries
                $rampSteepValidation->isNotZero($text, 'h');

                $rampSteepObj = $latestAction->rampSteep()->create([
                    'h' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $rampSteepObj->id,
                ]);

                $rampSteepBotResponse->sendPamameterLText();

            } elseif(empty($rampSteep->l)) {
                
                // user input validation for numeric values
                $rampSteepValidation->isNumericValidation($text, 'l');
                // user input validation for positive integer values
                $rampSteepValidation->isPositiveInteger($text, 'l');
                // user input validation for not zero entries
                $rampSteepValidation->isNotZero($text, 'l');

                $latestAction->rampSteep()->update([
                    'l' => !empty($text) ? $text : null,
                ]);

                $rampSteepBotResponse->displayFinalResults();
            } 

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $rampSteep = $this->user->actions->flatMap->rampSteep->first();
        
        if(!is_null($rampSteep)) {
            $rampSteep->delete();
        }

        return $this->displayItem();
    }
} 

