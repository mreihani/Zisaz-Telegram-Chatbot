<?php

namespace App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarWeight;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\RebarAndStirrup\RebarWeight;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarWeight\RebarWeightValidation;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarWeight\RebarWeightBotResponse;

class RebarWeightCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات وزن میلگرد انجام می شود. 
            
اطلاعات مورد نیاز:
1- قطر میلگرد

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/rebarweightsendpamameterdtext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new RebarWeight());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $rebarWeight = $latestAction->rebarWeight->first();

            $rebarWeightBotResponse = new RebarWeightBotResponse($this->telegram);
            $rebarWeightValidation = new RebarWeightValidation($this->telegram);

            if(empty($rebarWeight->d)) {
                
                // user input validation for numeric values
                $rebarWeightValidation->isNumericValidation($text, 'd');
                // user input validation for positive integer values
                $rebarWeightValidation->isPositiveInteger($text, 'd');
                // user input validation for not zero entries
                $rebarWeightValidation->isNotZero($text, 'd');

                $rebarWeightObj = $latestAction->rebarWeight()->create([
                    'd' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $rebarWeightObj->id,
                ]);

                $rebarWeightBotResponse->displayFinalResults();
            }  

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $rebarWeight = $this->user->actions->flatMap->rebarWeight->first();
        
        if(!is_null($rebarWeight)) {
            $rebarWeight->delete();
        }

        return $this->displayItem();
    }
} 

