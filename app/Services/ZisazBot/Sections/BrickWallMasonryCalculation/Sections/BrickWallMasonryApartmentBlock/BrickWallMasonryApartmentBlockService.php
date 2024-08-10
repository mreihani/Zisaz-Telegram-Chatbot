<?php

namespace App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryApartmentBlock;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\BrickWallMasonry\BrickWallMasonry;

class BrickWallMasonryApartmentBlockService extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات دیوار پارتیشن بلوکی آپارتمان انجام می شود. 
            
اطلاعات مورد نیاز:
1- مساحت کل دیوار
2- عرض دیوار
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/brickwallmasonryapartmentblocksendpamameteratext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new BrickWallMasonry());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $brickWallMasonry = $latestAction->brickWallMasonry->first();
            $brickWallMasonryApartmentBlock = $brickWallMasonry->brickWallMasonryApartmentBlock;

            $beamAndBlockRoofBotResponse = new BeamAndBlockRoofBotResponse($this->telegram);
            $beamAndBlockRoofValidation = new BeamAndBlockRoofValidation($this->telegram);

            if(empty($beamAndBlockRoof->a) && empty($beamAndBlockRoof->h) && empty($beamAndBlockRoof->c)) {
                
                // user input validation for numeric values
                $beamAndBlockRoofValidation->isNumericValidation($text, 'a');
                // user input validation for positive integer values
                $beamAndBlockRoofValidation->isPositiveInteger($text, 'a');

                $beamAndBlockRoofObj = $latestAction->beamAndBlockRoof()->create([
                    'a' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $beamAndBlockRoofObj->id,
                ]);

                $beamAndBlockRoofBotResponse->sendPamameterHText();

            } elseif(empty($beamAndBlockRoof->h) && empty($beamAndBlockRoof->c)) {

                if($text == '/beamandblockroofsendpamameterh20' || $text == 20) {
                    $text = 20;
                } elseif($text == '/beamandblockroofsendpamameterh25' || $text == 25) {
                    $text = 25;
                } else {
                    $text = 20;
                }

                // user input validation for numeric values
                $beamAndBlockRoofValidation->isNumericValidation($text, 'h');
                // user input validation for positive integer values
                $beamAndBlockRoofValidation->isPositiveInteger($text, 'h');

                $latestAction->beamAndBlockRoof()->update([
                    'h' => !empty($text) ? $text : null,
                ]);

                $beamAndBlockRoofBotResponse->sendPamameterCText();

            } elseif(empty($beamAndBlockRoof->c)) {

                // user input validation for numeric values
                $beamAndBlockRoofValidation->isNumericValidation($text, 'c');
                // user input validation for positive integer values
                $beamAndBlockRoofValidation->isPositiveInteger($text, 'c');

                $latestAction->beamAndBlockRoof()->update([
                    'c' => !empty($text) ? $text : null,
                ]);

                $beamAndBlockRoofBotResponse->displayFinalResults();
            } 

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $beamAndBlockRoof = $this->user->actions->flatMap->beamAndBlockRoof->first();
        
        if(!is_null($beamAndBlockRoof)) {
            $beamAndBlockRoof->delete();
        }

        return $this->displayItem();
    }
} 

