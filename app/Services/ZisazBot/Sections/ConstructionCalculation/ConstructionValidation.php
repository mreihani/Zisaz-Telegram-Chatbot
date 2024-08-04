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

    public function isNumericValidation($text, $paramType) {
        if(!is_numeric($text)) {

            $message = '
                ⛔خطا!

            مقدار وارد شده باید عدد باشد!
            ';

            $this->sendMessage($this->telegram, $message);
            $constructionBotResponse = new ConstructionBotResponse($this->telegram);

            switch ($paramType) {
                case 'c':
                    $constructionBotResponse->sendPamameterCText();
                    break;
                case 'm':
                    $constructionBotResponse->sendPamameterMText();
                    break;
                case 'a':
                    $constructionBotResponse->sendPamameterAText();
                    break;
                case 'b':
                    $constructionBotResponse->sendPamameterBText();
                    break;
                case 'nb':
                    $constructionBotResponse->sendPamameterNBText();
                    break;
                case 'nf':
                    $constructionBotResponse->sendPamameterNFText();
                    break;
                case 'nb1':
                    $constructionBotResponse->sendPamameterBasement1Text();
                    break;
                case 'nb2':
                    $constructionBotResponse->sendPamameterBasement2Text();
                    break;
                case 'g':
                    $constructionBotResponse->sendPamameterGText();
                    break;
                case 'f1':
                    $constructionBotResponse->sendPamameterF1Text();
                    break;
                case 'f2':
                    $constructionBotResponse->sendPamameterF2Text();
                    break;
                case 'f3':
                    $constructionBotResponse->sendPamameterF3Text();
                    break;
                case 'f4':
                    $constructionBotResponse->sendPamameterF4Text();
                    break;
                case 'f5':
                    $constructionBotResponse->sendPamameterF5Text();
                    break;
                case 'f6':
                    $constructionBotResponse->sendPamameterF6Text();
                    break;
                case 'f7':
                    $constructionBotResponse->sendPamameterF7Text();
                    break;
                case 'f8':
                    $constructionBotResponse->sendPamameterF8Text();
                    break;
                case 'b1':
                    $constructionBotResponse->sendPamameterB1Text();
                    break;
                case 'b2':
                    $constructionBotResponse->sendPamameterB2Text();
                    break;
                case 'b3':
                    $constructionBotResponse->sendPamameterB3Text();
                    break;
                case 'pc':
                    $constructionBotResponse->sendPamameterPCText();
                    break;
                case 'pm':
                    $constructionBotResponse->sendPamameterPMText();
                    break;
                case 'pa':
                    $constructionBotResponse->sendPamameterPAText();
                    break;
                case 'ps':
                    $constructionBotResponse->sendPamameterPSText();
                    break;
                case 'pk':
                    $constructionBotResponse->sendPamameterPKText();
                    break;
                default:
                    // Handle default case or error
                    break;
            }

            throw new \Exception($message);
        }
    }

    public function isPositiveInteger($text, $paramType) {
        if($text <= 0) {

            $message = '
                ⛔خطا!

            مقدار وارد شده نمی تواند صفر یا منفی باشد!
            ';

            $this->sendMessage($this->telegram, $message);
            $constructionBotResponse = new ConstructionBotResponse($this->telegram);

            switch ($paramType) {
                case 'c':
                    $constructionBotResponse->sendPamameterCText();
                    break;
                case 'm':
                    $constructionBotResponse->sendPamameterMText();
                    break;
                case 'a':
                    $constructionBotResponse->sendPamameterAText();
                    break;
                case 'b':
                    $constructionBotResponse->sendPamameterBText();
                    break;
                case 'nb':
                    $constructionBotResponse->sendPamameterNBText();
                    break;
                case 'nf':
                    $constructionBotResponse->sendPamameterNFText();
                    break;
                case 'nb1':
                    $constructionBotResponse->sendPamameterBasement1Text();
                    break;
                case 'nb2':
                    $constructionBotResponse->sendPamameterBasement2Text();
                    break;
                case 'g':
                    $constructionBotResponse->sendPamameterGText();
                    break;
                case 'f1':
                    $constructionBotResponse->sendPamameterF1Text();
                    break;
                case 'f2':
                    $constructionBotResponse->sendPamameterF2Text();
                    break;
                case 'f3':
                    $constructionBotResponse->sendPamameterF3Text();
                    break;
                case 'f4':
                    $constructionBotResponse->sendPamameterF4Text();
                    break;
                case 'f5':
                    $constructionBotResponse->sendPamameterF5Text();
                    break;
                case 'f6':
                    $constructionBotResponse->sendPamameterF6Text();
                    break;
                case 'f7':
                    $constructionBotResponse->sendPamameterF7Text();
                    break;
                case 'f8':
                    $constructionBotResponse->sendPamameterF8Text();
                    break;
                case 'b1':
                    $constructionBotResponse->sendPamameterB1Text();
                    break;
                case 'b2':
                    $constructionBotResponse->sendPamameterB2Text();
                    break;
                case 'b3':
                    $constructionBotResponse->sendPamameterB3Text();
                    break;
                case 'pc':
                    $constructionBotResponse->sendPamameterPCText();
                    break;
                case 'pm':
                    $constructionBotResponse->sendPamameterPMText();
                    break;
                case 'pa':
                    $constructionBotResponse->sendPamameterPAText();
                    break;
                case 'ps':
                    $constructionBotResponse->sendPamameterPSText();
                    break;
                case 'pk':
                    $constructionBotResponse->sendPamameterPKText();
                    break;
                default:
                    // Handle default case or error
                    break;
            }

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

            switch ($paramType) {
                case 'c':
                    $constructionBotResponse->sendPamameterCText();
                    break;
                case 'm':
                    $constructionBotResponse->sendPamameterMText();
                    break;
                case 'a':
                    $constructionBotResponse->sendPamameterAText();
                    break;
                case 'b':
                    $constructionBotResponse->sendPamameterBText();
                    break;
                case 'nb':
                    $constructionBotResponse->sendPamameterNBText();
                    break;
                case 'nf':
                    $constructionBotResponse->sendPamameterNFText();
                    break;
                case 'nb1':
                    $constructionBotResponse->sendPamameterBasement1Text();
                    break;
                case 'nb2':
                    $constructionBotResponse->sendPamameterBasement2Text();
                    break;
                case 'g':
                    $constructionBotResponse->sendPamameterGText();
                    break;
                case 'f1':
                    $constructionBotResponse->sendPamameterF1Text();
                    break;
                case 'f2':
                    $constructionBotResponse->sendPamameterF2Text();
                    break;
                case 'f3':
                    $constructionBotResponse->sendPamameterF3Text();
                    break;
                case 'f4':
                    $constructionBotResponse->sendPamameterF4Text();
                    break;
                case 'f5':
                    $constructionBotResponse->sendPamameterF5Text();
                    break;
                case 'f6':
                    $constructionBotResponse->sendPamameterF6Text();
                    break;
                case 'f7':
                    $constructionBotResponse->sendPamameterF7Text();
                    break;
                case 'f8':
                    $constructionBotResponse->sendPamameterF8Text();
                    break;
                case 'b1':
                    $constructionBotResponse->sendPamameterB1Text();
                    break;
                case 'b2':
                    $constructionBotResponse->sendPamameterB2Text();
                    break;
                case 'b3':
                    $constructionBotResponse->sendPamameterB3Text();
                    break;
                case 'pc':
                    $constructionBotResponse->sendPamameterPCText();
                    break;
                case 'pm':
                    $constructionBotResponse->sendPamameterPMText();
                    break;
                case 'pa':
                    $constructionBotResponse->sendPamameterPAText();
                    break;
                case 'ps':
                    $constructionBotResponse->sendPamameterPSText();
                    break;
                case 'pk':
                    $constructionBotResponse->sendPamameterPKText();
                    break;
                default:
                    // Handle default case or error
                    break;
            }

            throw new \Exception($message);
        }
    }
} 

