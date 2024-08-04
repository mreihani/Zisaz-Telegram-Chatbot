<?php

namespace App\Services\ZisazBot\Sections\ConstructionCalculation;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionBotResponse;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionCalculation;

class ConstructionValidation extends ConstructionCalculation {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    private function switchValidation($responseObject, $paramType) {
        switch ($paramType) {
            case 'c':
                $responseObject->sendPamameterCText();
                break;
            case 'm':
                $responseObject->sendPamameterMText();
                break;
            case 'a':
                $responseObject->sendPamameterAText();
                break;
            case 'b':
                $responseObject->sendPamameterBText();
                break;
            case 'nb':
                $responseObject->sendPamameterNBText();
                break;
            case 'nf':
                $responseObject->sendPamameterNFText();
                break;
            case 'nb1':
                $responseObject->sendPamameterBasement1Text();
                break;
            case 'nb2':
                $responseObject->sendPamameterBasement2Text();
                break;
            case 'g':
                $responseObject->sendPamameterGText();
                break;
            case 'f1':
                $responseObject->sendPamameterF1Text();
                break;
            case 'f2':
                $responseObject->sendPamameterF2Text();
                break;
            case 'f3':
                $responseObject->sendPamameterF3Text();
                break;
            case 'f4':
                $responseObject->sendPamameterF4Text();
                break;
            case 'f5':
                $responseObject->sendPamameterF5Text();
                break;
            case 'f6':
                $responseObject->sendPamameterF6Text();
                break;
            case 'f7':
                $responseObject->sendPamameterF7Text();
                break;
            case 'f8':
                $responseObject->sendPamameterF8Text();
                break;
            case 'b1':
                $responseObject->sendPamameterB1Text();
                break;
            case 'b2':
                $responseObject->sendPamameterB2Text();
                break;
            case 'b3':
                $responseObject->sendPamameterB3Text();
                break;
            case 'pc':
                $responseObject->sendPamameterPCText();
                break;
            case 'pm':
                $responseObject->sendPamameterPMText();
                break;
            case 'pa':
                $responseObject->sendPamameterPAText();
                break;
            case 'ps':
                $responseObject->sendPamameterPSText();
                break;
            case 'pk':
                $responseObject->sendPamameterPKText();
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
            $constructionBotResponse = new ConstructionBotResponse($this->telegram);

            $this->switchValidation($constructionBotResponse, $paramType);

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
            $constructionBotResponse = new ConstructionBotResponse($this->telegram);

            $this->switchValidation($constructionBotResponse, $paramType);

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
            $constructionBotResponse = new ConstructionBotResponse($this->telegram);

            $this->switchValidation($constructionBotResponse, $paramType);

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
            $constructionBotResponse = new ConstructionBotResponse($this->telegram);

            $this->switchValidation($constructionBotResponse, $paramType);

            throw new \Exception($message);
        }
    }
} 

