<?php

namespace App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarConversion;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\RebarAndStirrup\RebarConversion;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarConversion\RebarConversionValidation;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarConversion\RebarConversionBotResponse;

class RebarConversionCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات معادل سازی میلگرد انجام می شود. 
            
اطلاعات مورد نیاز:
1- قطر میلگرد نقشه
2- تعداد میلگرد نقشه
3- قطر میلگرد موجود (جدید)

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/rebarconversiond1text')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new RebarConversion());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $rebarConversion = $latestAction->rebarConversion->first();

            $rebarConversionBotResponse = new RebarConversionBotResponse($this->telegram);
            $rebarConversionValidation = new RebarConversionValidation($this->telegram);

            if(empty($rebarConversion->d1)) {
                
                // user input validation for numeric values
                $rebarConversionValidation->isNumericValidation($text, 'd1');
                // user input validation for positive integer values
                $rebarConversionValidation->isPositiveInteger($text, 'd1');
                // user input validation for not zero entries
                $rebarConversionValidation->isNotZero($text, 'd1');

                $rebarConversionObj = $latestAction->rebarConversion()->create([
                    'd1' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $rebarConversionObj->id,
                ]);

                $rebarConversionBotResponse->sendPamameterNText();
            } elseif(empty($rebarConversion->n)) {
                
                // user input validation for numeric values
                $rebarConversionValidation->isNumericValidation($text, 'n');
                // user input validation for positive integer values
                $rebarConversionValidation->isPositiveInteger($text, 'n');
                // user input validation for not zero entries
                $rebarConversionValidation->isNotZero($text, 'n');

                $latestAction->rebarConversion()->update([
                    'n' => !empty($text) ? $text : null,
                ]);

                $rebarConversionBotResponse->sendPamameterD2Text();
            } elseif(empty($rebarConversion->d2)) {
                
                // user input validation for numeric values
                $rebarConversionValidation->isNumericValidation($text, 'd2');
                // user input validation for positive integer values
                $rebarConversionValidation->isPositiveInteger($text, 'd2');
                // user input validation for not zero entries
                $rebarConversionValidation->isNotZero($text, 'd2');

                $latestAction->rebarConversion()->update([
                    'd2' => !empty($text) ? $text : null,
                ]);

                $rebarConversionBotResponse->displayFinalResults();
            } 

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $rebarConversion = $this->user->actions->flatMap->rebarConversion->first();
        
        if(!is_null($rebarConversion)) {
            $rebarConversion->delete();
        }

        return $this->displayItem();
    }
} 

