<?php

namespace App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\BeamAndBlockRoof\BeamAndBlockRoof;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofParameters;

class BeamAndBlockRoofCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات مربوط به سقف تیرچه و بلوک انجام می شود. 
            
اطلاعات مورد نیاز:
1- مساحت کل سقف
2- ارتفاع تیرچه
3- عیار بتن       
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/beamandblockroofsendpamameteratext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/getmenu')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new BeamAndBlockRoof());
    }

    public function getUserPrompts() {

        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $beamAndBlockRoof = $latestAction->beamAndBlockRoof->first();

            $beamAndBlockRoofParameters = new BeamAndBlockRoofParameters($this->telegram);

            if(empty($beamAndBlockRoof->a) && empty($beamAndBlockRoof->h) && empty($beamAndBlockRoof->c)) {
                
                // user input validation for numeric values
                $this->isNumericValidation($text, 'a');

                // user input validation for positive integer values
                $this->isPositiveInteger($text, 'a');

                $beamAndBlockRoofObj = $latestAction->beamAndBlockRoof()->create([
                    'a' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $beamAndBlockRoofObj->id,
                ]);

                $beamAndBlockRoofParameters->sendPamameterHText();

            } elseif(empty($beamAndBlockRoof->h) && empty($beamAndBlockRoof->c)) {

                if($text == '/beamandblockroofsendpamameterh20' || $text == 20) {
                    $text = 20;
                } elseif($text == '/beamandblockroofsendpamameterh25' || $text == 25) {
                    $text = 25;
                } else {
                    $text = 20;
                }

                // user input validation for numeric values
                $this->isNumericValidation($text, 'h');

                // user input validation for positive integer values
                $this->isPositiveInteger($text, 'h');

                $latestAction->beamAndBlockRoof()->update([
                    'h' => !empty($text) ? $text : null,
                ]);

                $beamAndBlockRoofParameters->sendPamameterCText();

            } elseif(empty($beamAndBlockRoof->c)) {

                // user input validation for numeric values
                $this->isNumericValidation($text, 'c');

                // user input validation for positive integer values
                $this->isPositiveInteger($text, 'c');

                $latestAction->beamAndBlockRoof()->update([
                    'c' => !empty($text) ? $text : null,
                ]);

                $beamAndBlockRoofParameters->displayFinalResults();
            } 

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function resetResults() {
        $latestAction = $this->getLastActionObject($this->telegram);

        $beamAndBlockRoof = $latestAction->beamAndBlockRoof->first();

        $beamAndBlockRoof->delete();

        return $this->displayItem();
    }

    private function isNumericValidation($text, $paramType) {
        if(!is_numeric($text)) {

            $message = '
                ⛔خطا!

مقدار وارد شده باید عدد باشد!
            ';

            $this->sendMessage($this->telegram, $message);
            $beamAndBlockRoofParameters = new BeamAndBlockRoofParameters($this->telegram);

            switch ($paramType) {
                case 'a':
                    $beamAndBlockRoofParameters->sendPamameterAText();
                    break;
                case 'h':
                    $beamAndBlockRoofParameters->sendPamameterHText();
                    break;
                case 'c':
                    $beamAndBlockRoofParameters->sendPamameterCText();
                    break;
                default:
                    // Handle default case or error
                    break;
            }

            throw new \Exception($message);
        }
    }

    private function isPositiveInteger($text, $paramType) {
        if($text < 0) {

            $message = '
                ⛔خطا!

مقدار وارد شده نمی تواند منفی باشد!
            ';

            $this->sendMessage($this->telegram, $message);
            $beamAndBlockRoofParameters = new BeamAndBlockRoofParameters($this->telegram);

            switch ($paramType) {
                case 'a':
                    $beamAndBlockRoofParameters->sendPamameterAText();
                    break;
                case 'h':
                    $beamAndBlockRoofParameters->sendPamameterHText();
                    break;
                case 'c':
                    $beamAndBlockRoofParameters->sendPamameterCText();
                    break;
                default:
                    // Handle default case or error
                    break;
            }

            throw new \Exception($message);
        }
    }
} 

