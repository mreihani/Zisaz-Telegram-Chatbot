<?php

namespace App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPartition;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\BrickWallMasonry\BrickWallMasonryPartition;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPartition\BrickWallMasonryPartitionValidation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPartition\BrickWallMasonryPartitionBotResponse;

class BrickWallMasonryPartitionCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات برآورد دیوار با آجر پارتیشن انجام می شود. 
            
اطلاعات مورد نیاز:
1- مساحت کل دیوار
2- عرض دیوار
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/brickwallmasonrypartitionsendpamameteratext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new BrickWallMasonryPartition());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $brickWallMasonryPartition = $latestAction->brickWallMasonryPartition->first();

            $brickWallMasonryPartitionBotResponse = new BrickWallMasonryPartitionBotResponse($this->telegram);
            $brickWallMasonryPartitionValidation = new BrickWallMasonryPartitionValidation($this->telegram);

            if(empty($brickWallMasonryPartition->a)) {
                
                // user input validation for numeric values
                $brickWallMasonryPartitionValidation->isNumericValidation($text, 'a');
                // user input validation for positive integer values
                $brickWallMasonryPartitionValidation->isPositiveInteger($text, 'a');
                // user input validation for not zero entries
                $brickWallMasonryPartitionValidation->isNotZero($text, 'a');

                $brickWallMasonryPartitionObj = $latestAction->brickWallMasonryPartition()->create([
                    'a' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $brickWallMasonryPartitionObj->id,
                ]);

                $brickWallMasonryPartitionBotResponse->sendPamameterBText();

            } elseif(empty($brickWallMasonryPartition->b)) {
                
                if($text === '/brickwallmasonrypartitionsendpamameterb8' || $text == 8) {
                    $text = 8;
                } elseif($text === '/brickwallmasonrypartitionsendpamameterb13' || $text == 13) {
                    $text = 13;
                } else {
                    $text = 8;
                }

                // user input validation for numeric values
                $brickWallMasonryPartitionValidation->isNumericValidation($text, 'b');
                // user input validation for positive integer values
                $brickWallMasonryPartitionValidation->isPositiveInteger($text, 'b');

                $latestAction->brickWallMasonryPartition()->update([
                    'b' => !empty($text) ? $text : null,
                ]);

                $brickWallMasonryPartitionBotResponse->displayFinalResults();
            } 

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $brickWallMasonryPartition = $this->user->actions->flatMap->brickWallMasonryPartition->first();
        
        if(!is_null($brickWallMasonryPartition)) {
            $brickWallMasonryPartition->delete();
        }

        return $this->displayItem();
    }
} 

