<?php

namespace App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\Ceramic;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\FacadeAndFlooringMaterial\Ceramic;

class CeramicCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات مصالح مورد نیاز سرامیک کف انجام می شود. 
            
اطلاعات مورد نیاز:
1- ضخامت متوسط ملات سرامیک کف
2- متراژ کل

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/ceramicsendpamameterttext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new Ceramic());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $ceramic = $latestAction->ceramic->first();

            $ceramicBotResponse = new CeramicBotResponse($this->telegram);
            $ceramicValidation = new CeramicValidation($this->telegram);

            if(empty($ceramic->t)) {
                
                // user input validation for numeric values
                $ceramicValidation->isNumericValidation($text, 't');
                // user input validation for positive integer values
                $ceramicValidation->isPositiveInteger($text, 't');
                // user input validation for not zero entries
                $ceramicValidation->isNotZero($text, 't');

                $ceramicObj = $latestAction->ceramic()->create([
                    't' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $ceramicObj->id,
                ]);

                $ceramicBotResponse->sendPamameterAText();
            } elseif(empty($ceramic->a)) {
                
                // user input validation for numeric values
                $ceramicValidation->isNumericValidation($text, 'a');
                // user input validation for positive integer values
                $ceramicValidation->isPositiveInteger($text, 'a');
                // user input validation for not zero entries
                $ceramicValidation->isNotZero($text, 'a');

                $latestAction->ceramic()->update([
                    'a' => !empty($text) ? $text : null,
                ]);

                $ceramicBotResponse->displayFinalResults();
            }

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $ceramic = $this->user->actions->flatMap->ceramic->first();
        
        if(!is_null($ceramic)) {
            $ceramic->delete();
        }

        return $this->displayItem();
    }
} 

