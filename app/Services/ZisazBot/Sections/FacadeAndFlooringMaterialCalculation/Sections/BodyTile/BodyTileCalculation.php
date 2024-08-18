<?php

namespace App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\BodyTile;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\FacadeAndFlooringMaterial\BodyTile;
use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\BodyTile\BodyTileValidation;
use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\BodyTile\BodyTileBotResponse;

class BodyTileCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات مصالح مورد نیاز کاشی بدنه انجام می شود. 
            
اطلاعات مورد نیاز:
1- ضخامت متوسط دوغاب کاشی بدنه
2- متراژ کل

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/bodytilesendpamameterttext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new BodyTile());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $bodyTile = $latestAction->bodyTile->first();

            $bodyTileBotResponse = new BodyTileBotResponse($this->telegram);
            $bodyTileValidation = new BodyTileValidation($this->telegram);

            if(empty($bodyTile->t)) {
                
                // user input validation for numeric values
                $bodyTileValidation->isNumericValidation($text, 't');
                // user input validation for positive integer values
                $bodyTileValidation->isPositiveInteger($text, 't');
                // user input validation for not zero entries
                $bodyTileValidation->isNotZero($text, 't');

                $bodyTileObj = $latestAction->bodyTile()->create([
                    't' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $bodyTileObj->id,
                ]);

                $bodyTileBotResponse->sendPamameterAText();
            } elseif(empty($bodyTile->a)) {
                
                // user input validation for numeric values
                $bodyTileValidation->isNumericValidation($text, 'a');
                // user input validation for positive integer values
                $bodyTileValidation->isPositiveInteger($text, 'a');
                // user input validation for not zero entries
                $bodyTileValidation->isNotZero($text, 'a');

                $latestAction->bodyTile()->update([
                    'a' => !empty($text) ? $text : null,
                ]);

                $bodyTileBotResponse->displayFinalResults();
            }

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $bodyTile = $this->user->actions->flatMap->bodyTile->first();
        
        if(!is_null($bodyTile)) {
            $bodyTile->delete();
        }

        return $this->displayItem();
    }
} 

