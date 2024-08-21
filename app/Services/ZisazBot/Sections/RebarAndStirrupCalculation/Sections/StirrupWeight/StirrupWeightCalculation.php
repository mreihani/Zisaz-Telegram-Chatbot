<?php

namespace App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\StirrupWeight;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\RebarAndStirrup\StirrupWeight;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\StirrupWeight\StirrupWeightValidation;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\StirrupWeight\StirrupWeightBotResponse;

class StirrupWeightCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات وزن خاموت انجام می شود. 
            
اطلاعات مورد نیاز:
1- قطر میلگرد خاموت
2- طول خاموت
3- عرض خاموت
4- تعداد کل خاموت

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/stirrupweightsendpamameterdtext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new StirrupWeight());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $stirrupWeight = $latestAction->stirrupWeight->first();

            $stirrupWeightBotResponse = new StirrupWeightBotResponse($this->telegram);
            $stirrupWeightValidation = new StirrupWeightValidation($this->telegram);

            if(empty($stirrupWeight->d)) {
                
                // user input validation for numeric values
                $stirrupWeightValidation->isNumericValidation($text, 'd');
                // user input validation for positive integer values
                $stirrupWeightValidation->isPositiveInteger($text, 'd');
                // user input validation for not zero entries
                $stirrupWeightValidation->isNotZero($text, 'd');

                $stirrupWeightObj = $latestAction->stirrupWeight()->create([
                    'd' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $stirrupWeightObj->id,
                ]);

                $stirrupWeightBotResponse->sendPamameterLText();
            } elseif(empty($stirrupWeight->l)) {
                
                // user input validation for numeric values
                $stirrupWeightValidation->isNumericValidation($text, 'l');
                // user input validation for positive integer values
                $stirrupWeightValidation->isPositiveInteger($text, 'l');
                // user input validation for not zero entries
                $stirrupWeightValidation->isNotZero($text, 'l');

                $latestAction->stirrupWeight()->update([
                    'l' => !empty($text) ? $text : null,
                ]);

                $stirrupWeightBotResponse->sendPamameterBText();
            } elseif(empty($stirrupWeight->b)) {
                
                // user input validation for numeric values
                $stirrupWeightValidation->isNumericValidation($text, 'b');
                // user input validation for positive integer values
                $stirrupWeightValidation->isPositiveInteger($text, 'b');
                // user input validation for not zero entries
                $stirrupWeightValidation->isNotZero($text, 'b');

                $latestAction->stirrupWeight()->update([
                    'b' => !empty($text) ? $text : null,
                ]);

                $stirrupWeightBotResponse->sendPamameterNText();
            } elseif(empty($stirrupWeight->n)) {
                
                // user input validation for numeric values
                $stirrupWeightValidation->isNumericValidation($text, 'n');
                // user input validation for positive integer values
                $stirrupWeightValidation->isPositiveInteger($text, 'n');
                // user input validation for not zero entries
                $stirrupWeightValidation->isNotZero($text, 'n');

                $latestAction->stirrupWeight()->update([
                    'n' => !empty($text) ? $text : null,
                ]);

                $stirrupWeightBotResponse->displayFinalResults();
            } 

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $stirrupWeight = $this->user->actions->flatMap->stirrupWeight->first();
        
        if(!is_null($stirrupWeight)) {
            $stirrupWeight->delete();
        }

        return $this->displayItem();
    }
} 

