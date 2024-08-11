<?php

namespace App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryGarden;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\BrickWallMasonry\BrickWallMasonryGarden;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryGarden\BrickWallMasonryGardenValidation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryGarden\BrickWallMasonryGardenBotResponse;

class BrickWallMasonryGardenCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات دیوار بلوکی حصار باغ یا حیاط انجام می شود. 
             
اطلاعات مورد نیاز:
1- طول دیوار
2- ارتفاع دیوار
3- عرض دیوار چینی
4- عمق شالوده
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/brickwallmasonrygardensendpamameterltext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new BrickWallMasonryGarden());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $brickWallMasonryGarden = $latestAction->brickWallMasonryGarden->first();

            $brickWallMasonryGardenBotResponse = new BrickWallMasonryGardenBotResponse($this->telegram);
            $brickWallMasonryGardenValidation = new BrickWallMasonryGardenValidation($this->telegram);

            if(empty($brickWallMasonryGarden->l)) {
                
                // user input validation for numeric values
                $brickWallMasonryGardenValidation->isNumericValidation($text, 'l');
                // user input validation for positive integer values
                $brickWallMasonryGardenValidation->isPositiveInteger($text, 'l');
                // user input validation for not zero entries
                $brickWallMasonryGardenValidation->isNotZero($text, 'l');

                $brickWallMasonryGardenObj = $latestAction->brickWallMasonryGarden()->create([
                    'l' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $brickWallMasonryGardenObj->id,
                ]);

                $brickWallMasonryGardenBotResponse->sendPamameterHText();

            } elseif(empty($brickWallMasonryGarden->h)) {

                // user input validation for numeric values
                $brickWallMasonryGardenValidation->isNumericValidation($text, 'h');
                // user input validation for specific number span
                $brickWallMasonryGardenValidation->isBetween($text, 'h', [0.2, 3]);

                $latestAction->brickWallMasonryGarden()->update([
                    'h' => !empty($text) ? $text : null,
                ]);

                $brickWallMasonryGardenBotResponse->sendPamameterTypeText();

            } elseif(empty($brickWallMasonryGarden->type)) {

                if($text == '/brickwallmasonrygardensendpamametertypea' || $text == 'a') {
                    $text = 'a';
                } elseif($text == '/brickwallmasonrygardensendpamametertypeb' || $text == 'b') {
                    $text = 'b';
                } else {
                    $text = 'a';
                }

                $latestAction->brickWallMasonryGarden()->update([
                    'type' => !empty($text) ? $text : null,
                ]);

                $brickWallMasonryGardenBotResponse->sendPamameterDText();

            } elseif(empty($brickWallMasonryGarden->d)) {

                // user input validation for numeric values
                $brickWallMasonryGardenValidation->isNumericValidation($text, 'd');
                // user input validation for specific number span
                $brickWallMasonryGardenValidation->isBetween($text, 'd', [0.1, 0.8]);

                $latestAction->brickWallMasonryGarden()->update([
                    'd' => !empty($text) ? $text : null,
                ]);

                $brickWallMasonryGardenBotResponse->displayFinalResults();
            } 

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $brickWallMasonryGarden = $this->user->actions->flatMap->brickWallMasonryGarden->first();
        
        if(!is_null($brickWallMasonryGarden)) {
            $brickWallMasonryGarden->delete();
        }

        return $this->displayItem();
    }
} 

