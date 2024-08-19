<?php

namespace App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\Mosaic;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\FacadeAndFlooringMaterial\Mosaic;
use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\Mosaic\MosaicValidation;
use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\Mosaic\MosaicBotResponse;

class MosaicCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات مصالح مورد نیاز موزائیک کف انجام می شود. 
            
اطلاعات مورد نیاز:
1- ضخامت متوسط ملات موزائیک کف
2- متراژ کل

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/mosaicsendpamameterttext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new Mosaic());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $mosaic = $latestAction->mosaic->first();

            $mosaicBotResponse = new MosaicBotResponse($this->telegram);
            $mosaicValidation = new MosaicValidation($this->telegram);

            if(empty($mosaic->t)) {
                
                // user input validation for numeric values
                $mosaicValidation->isNumericValidation($text, 't');
                // user input validation for positive integer values
                $mosaicValidation->isPositiveInteger($text, 't');
                // user input validation for not zero entries
                $mosaicValidation->isNotZero($text, 't');

                $mosaicObj = $latestAction->mosaic()->create([
                    't' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $mosaicObj->id,
                ]);

                $mosaicBotResponse->sendPamameterAText();
            } elseif(empty($mosaic->a)) {
                
                // user input validation for numeric values
                $mosaicValidation->isNumericValidation($text, 'a');
                // user input validation for positive integer values
                $mosaicValidation->isPositiveInteger($text, 'a');
                // user input validation for not zero entries
                $mosaicValidation->isNotZero($text, 'a');

                $latestAction->mosaic()->update([
                    'a' => !empty($text) ? $text : null,
                ]);

                $mosaicBotResponse->displayFinalResults();
            }

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $mosaic = $this->user->actions->flatMap->mosaic->first();
        
        if(!is_null($mosaic)) {
            $mosaic->delete();
        }

        return $this->displayItem();
    }
} 

