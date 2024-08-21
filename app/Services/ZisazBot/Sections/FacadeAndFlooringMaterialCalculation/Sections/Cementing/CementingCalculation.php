<?php

namespace App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\Cementing;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\FacadeAndFlooringMaterial\Cementing;
use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\Cementing\CementingValidation;
use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\Cementing\CementingBotResponse;

class CementingCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات مصالح مورد نیاز سیمانکاری زبره (آستر) انجام می شود. 
            
اطلاعات مورد نیاز:
1- ضخامت متوسط سیمان کاری
2- متراژ کل

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/cementingsendpamameterttext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new Cementing());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $cementing = $latestAction->cementing->first();

            $cementingBotResponse = new CementingBotResponse($this->telegram);
            $cementingValidation = new CementingValidation($this->telegram);

            if(empty($cementing->t)) {
                
                // user input validation for numeric values
                $cementingValidation->isNumericValidation($text, 't');
                // user input validation for positive integer values
                $cementingValidation->isPositiveInteger($text, 't');
                // user input validation for not zero entries
                $cementingValidation->isNotZero($text, 't');

                $cementingObj = $latestAction->cementing()->create([
                    't' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $cementingObj->id,
                ]);

                $cementingBotResponse->sendPamameterAText();
            } elseif(empty($cementing->a)) {
                
                // user input validation for numeric values
                $cementingValidation->isNumericValidation($text, 'a');
                // user input validation for positive integer values
                $cementingValidation->isPositiveInteger($text, 'a');
                // user input validation for not zero entries
                $cementingValidation->isNotZero($text, 'a');

                $latestAction->cementing()->update([
                    'a' => !empty($text) ? $text : null,
                ]);

                $cementingBotResponse->displayFinalResults();
            }

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $cementing = $this->user->actions->flatMap->cementing->first();
        
        if(!is_null($cementing)) {
            $cementing->delete();
        }

        return $this->displayItem();
    }
} 

