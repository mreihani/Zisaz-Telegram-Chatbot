<?php

namespace App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\Concreting;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\Concreting\Concreting;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\Concreting\ConcretingValidation;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\Concreting\ConcretingBotResponse;

class ConcretingCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات مصالح مورد نیاز برای بتن ریزی انجام می شود. 
            
اطلاعات مورد نیاز:
1- حجم بتن
2- عیار سیمان

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/concretingsendpamametervtext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new Concreting());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $concreting = $latestAction->concreting->first();

            $concretingBotResponse = new ConcretingBotResponse($this->telegram);
            $concretingValidation = new ConcretingValidation($this->telegram);

            if(empty($concreting->v)) {
                
                // user input validation for numeric values
                $concretingValidation->isNumericValidation($text, 'v');
                // user input validation for positive integer values
                $concretingValidation->isPositiveInteger($text, 'v');
                // user input validation for not zero entries
                $concretingValidation->isNotZero($text, 'v');

                $concretingObj = $latestAction->concreting()->create([
                    'v' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $concretingObj->id,
                ]);

                $concretingBotResponse->sendPamameterCText();
            } elseif(empty($concreting->c)) {
                
                // user input validation for numeric values
                $concretingValidation->isNumericValidation($text, 'c');
                // user input validation for positive integer values
                $concretingValidation->isPositiveInteger($text, 'c');
                // user input validation for not zero entries
                $concretingValidation->isNotZero($text, 'c');

                $latestAction->concreting()->update([
                    'c' => !empty($text) ? $text : null,
                ]);

                $concretingBotResponse->displayFinalResults();
            }

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $concreting = $this->user->actions->flatMap->concreting->first();
        
        if(!is_null($concreting)) {
            $concreting->delete();
        }

        return $this->displayItem();
    }
} 

