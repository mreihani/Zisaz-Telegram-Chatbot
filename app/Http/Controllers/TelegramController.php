<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TelegramBotService;
use App\Services\TelegramBotPhp\Telegram;
use App\Services\ZisazBot\Sections\MainMenu;
use App\Services\ZisazBot\Sections\UserPrompts;
use App\Services\ZisazBot\Sections\StartSection;
use App\Models\Action\BeamAndBlockRoof\BeamAndBlockRoof;
use App\Services\ZisazBot\Sections\ConstructionCalculation\Sections\ConstCalcArea;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionCalculation;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofCalculation;

class TelegramController extends Controller
{
    public function inbound(Request $request) {
      
        $telegram = new Telegram(env('TELEGRAM_BOT_TOKEN'));
        $incoming_text = $telegram->Text();
        $isCommand = false;

        // start command
        if($incoming_text == '/start') {
            $isCommand = true;
            $startSection = new StartSection($telegram);
            $startSection->displayItem();
        }

        // get menu command
        if($incoming_text == '/getmenu') {
            $isCommand = true;
            $mainMenuSection = new MainMenu($telegram);
            $mainMenuSection->displayItem();
        }

        // محاسبات زیربنا، هزینه و  مشارکت در ساخت     
        if($incoming_text == '/getconstractioncalculation') {
            $isCommand = true;
            $constructionCalculationSection = new ConstructionCalculation($telegram);
            $constructionCalculationSection->displayItem();
        }

        // محاسبه زیربنا 
        if($incoming_text == '/getconstcalcarea') {
            $isCommand = true;
            $constCalcArea = new ConstCalcArea($telegram);
            $constCalcArea->displayItem();
        }
        // محاسبه هزینه ساخت 
        // نسبت منصفانه مشارکت در ساخت 

        // سقف تیرچه و بلوک
        if($incoming_text == '/getbeamandblockroof') {
            $isCommand = true;
            $beamAndBlockRoofCalculation = new BeamAndBlockRoofCalculation($telegram);
            $beamAndBlockRoofCalculation->displayItem();
        }
        if($incoming_text == '/beamandblockroofsendpamameteratext') {
            $isCommand = true;
            $beamAndBlockRoofCalculation = new BeamAndBlockRoofCalculation($telegram);
            $beamAndBlockRoofCalculation->sendPamameterAText();
        }
        if($incoming_text == '/beamandblockroofdownloadresults') {
            $isCommand = true;
            $beamAndBlockRoofCalculation = new BeamAndBlockRoofCalculation($telegram);
            $beamAndBlockRoofCalculation->displayFinalResults();
        }
       
        // محاسبه متن ها و ورودی های کاربر
        if(!$isCommand) {
            $userPrompts = new UserPrompts($telegram);
            $userPrompts->checkUserPrompt();
        }
    }
}
