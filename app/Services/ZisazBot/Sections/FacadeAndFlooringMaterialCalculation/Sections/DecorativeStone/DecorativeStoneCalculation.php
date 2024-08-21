<?php

namespace App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\DecorativeStone;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\FacadeAndFlooringMaterial\DecorativeStone;
use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\DecorativeStone\DecorativeStoneValidation;
use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\DecorativeStone\DecorativeStoneBotResponse;

class DecorativeStoneCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات مصالح مورد نیاز سنگ نما انجام می شود. 
            
اطلاعات مورد نیاز:
1- ضخامت متوسط دوغاب نما
2- متراژ کل

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/decorativestonesendpamameterttext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new DecorativeStone());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $dececorativeStone = $latestAction->dececorativeStone->first();

            $decorativeStoneBotResponse = new DecorativeStoneBotResponse($this->telegram);
            $decorativeStoneValidation = new DecorativeStoneValidation($this->telegram);

            if(empty($dececorativeStone->t)) {
                
                // user input validation for numeric values
                $decorativeStoneValidation->isNumericValidation($text, 't');
                // user input validation for positive integer values
                $decorativeStoneValidation->isPositiveInteger($text, 't');
                // user input validation for not zero entries
                $decorativeStoneValidation->isNotZero($text, 't');

                $dececorativeStoneObj = $latestAction->dececorativeStone()->create([
                    't' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $dececorativeStoneObj->id,
                ]);

                $decorativeStoneBotResponse->sendPamameterAText();
            } elseif(empty($dececorativeStone->a)) {
                
                // user input validation for numeric values
                $decorativeStoneValidation->isNumericValidation($text, 'a');
                // user input validation for positive integer values
                $decorativeStoneValidation->isPositiveInteger($text, 'a');
                // user input validation for not zero entries
                $decorativeStoneValidation->isNotZero($text, 'a');

                $latestAction->dececorativeStone()->update([
                    'a' => !empty($text) ? $text : null,
                ]);

                $decorativeStoneBotResponse->displayFinalResults();
            }

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $dececorativeStone = $this->user->actions->flatMap->dececorativeStone->first();
        
        if(!is_null($dececorativeStone)) {
            $dececorativeStone->delete();
        }

        return $this->displayItem();
    }
} 

