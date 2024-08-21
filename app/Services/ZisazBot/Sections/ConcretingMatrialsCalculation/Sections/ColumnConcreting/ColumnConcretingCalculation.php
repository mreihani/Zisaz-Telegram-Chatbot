<?php

namespace App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\ColumnConcreting;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\Concreting\ColumnConcreting;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\ColumnConcreting\ColumnConcretingValidation;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\ColumnConcreting\ColumnConcretingBotResponse;

class ColumnConcretingCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات مصالح مورد نیاز برای بتن ریزی ستون ها انجام می شود. 
            
اطلاعات مورد نیاز:
1- تعداد ستون
2- طول ستون
3- عرض ستون
4- ارتفاع ستون
5- عیار سیمان

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/columnconcretingsendpamametervtext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new ColumnConcreting());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $columnConcreting = $latestAction->columnConcreting->first();

            $columnConcretingBotResponse = new ColumnConcretingBotResponse($this->telegram);
            $columnConcretingValidation = new ColumnConcretingValidation($this->telegram);

            if(empty($columnConcreting->v)) {
                
                // user input validation for numeric values
                $columnConcretingValidation->isNumericValidation($text, 'v');
                // user input validation for positive integer values
                $columnConcretingValidation->isPositiveInteger($text, 'v');
                // user input validation for not zero entries
                $columnConcretingValidation->isNotZero($text, 'v');

                $columnConcretingObj = $latestAction->columnConcreting()->create([
                    'v' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $columnConcretingObj->id,
                ]);

                $columnConcretingBotResponse->sendPamameterLText();
            } elseif(empty($columnConcreting->l)) {
                
                // user input validation for numeric values
                $columnConcretingValidation->isNumericValidation($text, 'l');
                // user input validation for positive integer values
                $columnConcretingValidation->isPositiveInteger($text, 'l');
                // user input validation for not zero entries
                $columnConcretingValidation->isNotZero($text, 'l');

                $latestAction->columnConcreting()->update([
                    'l' => !empty($text) ? $text : null,
                ]);

                $columnConcretingBotResponse->sendPamameterBText();
            } elseif(empty($columnConcreting->b)) {
                
                // user input validation for numeric values
                $columnConcretingValidation->isNumericValidation($text, 'b');
                // user input validation for positive integer values
                $columnConcretingValidation->isPositiveInteger($text, 'b');
                // user input validation for not zero entries
                $columnConcretingValidation->isNotZero($text, 'b');

                $latestAction->columnConcreting()->update([
                    'b' => !empty($text) ? $text : null,
                ]);

                $columnConcretingBotResponse->sendPamameterHText();
            } elseif(empty($columnConcreting->h)) {
                
                // user input validation for numeric values
                $columnConcretingValidation->isNumericValidation($text, 'h');
                // user input validation for positive integer values
                $columnConcretingValidation->isPositiveInteger($text, 'h');
                // user input validation for not zero entries
                $columnConcretingValidation->isNotZero($text, 'h');

                $latestAction->columnConcreting()->update([
                    'h' => !empty($text) ? $text : null,
                ]);

                $columnConcretingBotResponse->sendPamameterCText();
            } elseif(empty($columnConcreting->c)) {
                
                // user input validation for numeric values
                $columnConcretingValidation->isNumericValidation($text, 'c');
                // user input validation for positive integer values
                $columnConcretingValidation->isPositiveInteger($text, 'c');
                // user input validation for not zero entries
                $columnConcretingValidation->isNotZero($text, 'c');

                $latestAction->columnConcreting()->update([
                    'c' => !empty($text) ? $text : null,
                ]);

                $columnConcretingBotResponse->displayFinalResults();
            } 

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $columnConcreting = $this->user->actions->flatMap->columnConcreting->first();
        
        if(!is_null($columnConcreting)) {
            $columnConcreting->delete();
        }

        return $this->displayItem();
    }
} 

