<?php

namespace App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\ExpansionJoint;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\Ramp\ExpansionJoint;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\ExpansionJoint\ExpansionJointValidation;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\ExpansionJoint\ExpansionJointBotResponse;

class ExpansionJointCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات محاسبه درز انقطاع ( ژوئن) می شود. 
            
اطلاعات مورد نیاز:
1- ارتفاع ساختمان

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/expansionjointsendpamameterhtext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new ExpansionJoint());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $expansionJoint = $latestAction->expansionJoint->first();

            $expansionJointBotResponse = new ExpansionJointBotResponse($this->telegram);
            $expansionJointValidation = new ExpansionJointValidation($this->telegram);

            if(empty($expansionJoint->h)) {
                
                // user input validation for numeric values
                $expansionJointValidation->isNumericValidation($text, 'h');
                // user input validation for positive integer values
                $expansionJointValidation->isPositiveInteger($text, 'h');
                // user input validation for not zero entries
                $expansionJointValidation->isNotZero($text, 'h');

                $expansionJointObj = $latestAction->expansionJoint()->create([
                    'h' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $expansionJointObj->id,
                ]);

                $expansionJointBotResponse->displayFinalResults();
            } 

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $expansionJoint = $this->user->actions->flatMap->expansionJoint->first();
        
        if(!is_null($expansionJoint)) {
            $expansionJoint->delete();
        }

        return $this->displayItem();
    }
} 

