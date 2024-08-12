<?php

namespace App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPressedBrick;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\BrickWallMasonry\BrickWallMasonryPressedBrick;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPressedBrick\BrickWallMasonryPressedBrickValidation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPressedBrick\BrickWallMasonryPressedBrickBotResponse;

class BrickWallMasonryPressedBrickCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات برآورد دیوار با آجر فشاری یا سه گل انجام می شود. 
            
اطلاعات مورد نیاز:
1- مساحت کل دیوار
2- عرض دیوار
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/brickwallmasonrypressedbricksendpamameteratext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new BrickWallMasonryPressedBrick());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $brickWallMasonryPressedBrick = $latestAction->brickWallMasonryPressedBrick->first();

            $brickWallMasonryPressedBrickBotResponse = new BrickWallMasonryPressedBrickBotResponse($this->telegram);
            $brickWallMasonryPressedBrickValidation = new BrickWallMasonryPressedBrickValidation($this->telegram);

            if(empty($brickWallMasonryPressedBrick->a)) {
                
                // user input validation for numeric values
                $brickWallMasonryPressedBrickValidation->isNumericValidation($text, 'a');
                // user input validation for positive integer values
                $brickWallMasonryPressedBrickValidation->isPositiveInteger($text, 'a');
                // user input validation for not zero entries
                $brickWallMasonryPressedBrickValidation->isNotZero($text, 'a');

                $brickWallMasonryPressedBrickObj = $latestAction->brickWallMasonryPressedBrick()->create([
                    'a' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $brickWallMasonryPressedBrickObj->id,
                ]);

                $brickWallMasonryPressedBrickBotResponse->sendPamameterBText();

            } elseif(empty($brickWallMasonryPressedBrick->b)) {

                if($text == '/brickwallmasonrypressedbricksendpamameterb11' || $text == 11) {
                    $text = 11;
                } elseif($text == '/brickwallmasonrypressedbricksendpamameterb22' || $text == 22) {
                    $text = 22;
                } elseif($text == '/brickwallmasonrypressedbricksendpamameterb35' || $text == 35) {
                    $text = 35;
                } else {
                    $text = 11;
                }

                // user input validation for numeric values
                $brickWallMasonryPressedBrickValidation->isNumericValidation($text, 'b');
                // user input validation for positive integer values
                $brickWallMasonryPressedBrickValidation->isPositiveInteger($text, 'b');

                $latestAction->brickWallMasonryPressedBrick()->update([
                    'b' => !empty($text) ? $text : null,
                ]);

                $brickWallMasonryPressedBrickBotResponse->displayFinalResults();
            } 

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $brickWallMasonryPressedBrick = $this->user->actions->flatMap->brickWallMasonryPressedBrick->first();
        
        if(!is_null($brickWallMasonryPressedBrick)) {
            $brickWallMasonryPressedBrick->delete();
        }

        return $this->displayItem();
    }
} 

