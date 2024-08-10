<?php

namespace App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryApartmentBlock;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;


class BrickWallMasonryApartmentBlockValidation extends BeamAndBlockRoofCalculation {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    private function switchValidation($responseObject, $paramType) {
        switch ($paramType) {
            case 'a':
                $responseObject->sendPamameterAText();
                break;
            case 'h':
                $responseObject->sendPamameterHText();
                break;
            case 'c':
                $responseObject->sendPamameterCText();
                break;
            default:
                // Handle default case or error
                break;
        }
    }
    
    public function isNumericValidation($text, $paramType) {
        if(!is_numeric($text)) {

            $message = '
                ⛔خطا!

                مقدار وارد شده باید عدد باشد!
            ';

            $this->sendMessage($this->telegram, $message);
            $beamAndBlockRoofBotResponse = new BeamAndBlockRoofBotResponse($this->telegram);

            $this->switchValidation($beamAndBlockRoofBotResponse, $paramType);

            throw new \Exception($message);
        }
    }

    public function isPositiveInteger($text, $paramType) {
        if($text < 0) {

            $message = '
                ⛔خطا!

                مقدار وارد شده نمی تواند منفی باشد!
            ';

            $this->sendMessage($this->telegram, $message);
            $beamAndBlockRoofBotResponse = new BeamAndBlockRoofBotResponse($this->telegram);

            $this->switchValidation($beamAndBlockRoofBotResponse, $paramType);

            throw new \Exception($message);
        }
    }

    public function isBetween($text, $paramType, $spanArray) {
        if($text < $spanArray[0] || $text > $spanArray[1]) {

            $message = "
                ⛔خطا!

            مقدار وارد شده نمی تواند کوچکتر $spanArray[0] از یا بزرگتر از $spanArray[1] باشد!
            ";

            $this->sendMessage($this->telegram, $message);
            $beamAndBlockRoofBotResponse = new BeamAndBlockRoofBotResponse($this->telegram);

            $this->switchValidation($beamAndBlockRoofBotResponse, $paramType);

            throw new \Exception($message);
        }
    }

    public function isNotZero($text, $paramType) {
        if($text == 0) {

            $message = '
                ⛔خطا!

            مقدار وارد شده نمی تواند صفر باشد!
            ';

            $this->sendMessage($this->telegram, $message);
            $beamAndBlockRoofBotResponse = new BeamAndBlockRoofBotResponse($this->telegram);

            $this->switchValidation($beamAndBlockRoofBotResponse, $paramType);

            throw new \Exception($message);
        }
    }
} 

