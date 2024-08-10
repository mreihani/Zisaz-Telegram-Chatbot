<?php

namespace App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryApartmentBlock;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\BrickWallMasonry\BrickWallMasonryApartmentBlock;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryApartmentBlock\BrickWallMasonryApartmentBlockValidation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryApartmentBlock\BrickWallMasonryApartmentBlockBotResponse;

class BrickWallMasonryApartmentBlockCalculation extends ZisazBot {

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
        $this->initializeAction(new BrickWallMasonryApartmentBlock());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $brickWallMasonryApartmentBlock = $latestAction->brickWallMasonryApartmentBlock->first();

            $brickWallMasonryApartmentBlockBotResponse = new BrickWallMasonryApartmentBlockBotResponse($this->telegram);
            $brickWallMasonryApartmentBlockValidation = new BrickWallMasonryApartmentBlockValidation($this->telegram);

            if(empty($brickWallMasonryApartmentBlock->a)) {
                
                // user input validation for numeric values
                $brickWallMasonryApartmentBlockValidation->isNumericValidation($text, 'a');
                // user input validation for positive integer values
                $brickWallMasonryApartmentBlockValidation->isPositiveInteger($text, 'a');
                // user input validation for not zero entries
                $brickWallMasonryApartmentBlockValidation->isNotZero($text, 'a');

                $brickWallMasonryApartmentBlockObj = $latestAction->brickWallMasonryApartmentBlock()->create([
                    'a' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $brickWallMasonryApartmentBlockObj->id,
                ]);

                $brickWallMasonryApartmentBlockBotResponse->sendPamameterBText();

            } elseif(empty($brickWallMasonryApartmentBlock->b)) {

                if($text == '/brickwallmasonryapartmentblocksendpamameterb7' || $text == 7) {
                    $text = 7;
                } elseif($text == '/brickwallmasonryapartmentblocksendpamameterb10' || $text == 10) {
                    $text = 10;
                } elseif($text == '/brickwallmasonryapartmentblocksendpamameterb15' || $text == 15) {
                    $text = 15;
                } elseif($text == '/brickwallmasonryapartmentblocksendpamameterb20' || $text == 20) {
                    $text = 20;
                } else {
                    $text = 7;
                }

                // user input validation for numeric values
                $brickWallMasonryApartmentBlockValidation->isNumericValidation($text, 'b');
                // user input validation for positive integer values
                $brickWallMasonryApartmentBlockValidation->isPositiveInteger($text, 'b');

                $latestAction->brickWallMasonryApartmentBlock()->update([
                    'b' => !empty($text) ? $text : null,
                ]);

                $brickWallMasonryApartmentBlockBotResponse->displayFinalResults();
            } 

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $brickWallMasonryApartmentBlock = $this->user->actions->flatMap->brickWallMasonryApartmentBlock->first();
        
        if(!is_null($brickWallMasonryApartmentBlock)) {
            $brickWallMasonryApartmentBlock->delete();
        }

        return $this->displayItem();
    }
} 

