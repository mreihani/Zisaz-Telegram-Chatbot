<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TelegramBotService;
use App\Services\TelegramBotPhp\Telegram;
use App\Services\ZisazBot\Sections\MainMenu;
use App\Services\ZisazBot\Sections\StartSection;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionCalculation;

class TelegramController extends Controller
{
    public function inbound(Request $request) {
      
        $telegram = new Telegram(env('TELEGRAM_BOT_TOKEN'));
        $incoming_text = $telegram->Text();

        switch ($incoming_text) {
            case '/start':
                $startSection = new StartSection($telegram);
                $startSection->displayItem();
                break;
            case '/getmenu':
                $mainMenuSection = new MainMenu($telegram);
                $mainMenuSection->displayItem();
                break;
            case '/getconstractioncalculation':
                $constructionCalculationSection = new ConstructionCalculation($telegram);
                $constructionCalculationSection->displayItem();
                break;
            // case '/getbeamandblockroof':
            //     $this->showBeamAndBlockRoofCalculation($telegram);
            //     break;
            // case '/getbuildingwall':
            //     $this->showBuildingWallCalculation($telegram);
            //     break;
            // case '/getrampcutseamandjoin':
            //     $this->showRampCutSeamCalculation($telegram);
            //     break;
            // case '/getweightofrebarandstirrup':
            //     $this->showWeightOfRebarAndStirrupCalculation($telegram);
            //     break;
            // case '/getconcretematerials':
            //     $this->showConcretePouringMaterialsCalculation($telegram);
            //     break;
            // case '/elevationandfloormaterials':
            //     $this->showBuildingFacadeAndFloorMaterialsCalculation($telegram);
            //     break;
            // case '/getsuggestion':
            //     $this->showSuggestions($telegram);
            //     break;
            // case '/getsupport':
            //     $this->showSupport($telegram);
            //     break;
            default:
                $mainMenuSection = new MainMenu($telegram);
                $mainMenuSection->displayItem();
        }
    }
}
