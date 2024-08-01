<?php

namespace App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\BeamAndBlockRoof\BeamAndBlockRoof;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofBotResponse;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofCalculation;

class BeamAndBlockRoofValidation extends BeamAndBlockRoofCalculation {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function isNumericValidation($text, $paramType) {
        if(!is_numeric($text)) {

            $message = '
                ⛔خطا!

مقدار وارد شده باید عدد باشد!
            ';

            $this->sendMessage($this->telegram, $message);
            $beamAndBlockRoofBotResponse = new BeamAndBlockRoofBotResponse($this->telegram);

            switch ($paramType) {
                case 'a':
                    $beamAndBlockRoofBotResponse->sendPamameterAText();
                    break;
                case 'h':
                    $beamAndBlockRoofBotResponse->sendPamameterHText();
                    break;
                case 'c':
                    $beamAndBlockRoofBotResponse->sendPamameterCText();
                    break;
                default:
                    // Handle default case or error
                    break;
            }

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

            switch ($paramType) {
                case 'a':
                    $beamAndBlockRoofBotResponse->sendPamameterAText();
                    break;
                case 'h':
                    $beamAndBlockRoofBotResponse->sendPamameterHText();
                    break;
                case 'c':
                    $beamAndBlockRoofBotResponse->sendPamameterCText();
                    break;
                default:
                    // Handle default case or error
                    break;
            }

            throw new \Exception($message);
        }
    }
} 

