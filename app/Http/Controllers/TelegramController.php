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
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofParameters;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofCalculation;

class TelegramController extends Controller
{
    public function inbound(Request $request) {
        
        // throw new \Exception('Temporarily down!');
        // return;

        $telegram = new Telegram(env('TELEGRAM_BOT_TOKEN'));
        $incoming_text = $telegram->Text();
        $isCommand = false;

        // start command
        if($incoming_text === '/start') {
            $isCommand = true;
            $startSection = new StartSection($telegram);
            $startSection->displayItem();
        } elseif($incoming_text === '/getmenu') {
            // get menu command
            $isCommand = true;
            $mainMenuSection = new MainMenu($telegram);
            $mainMenuSection->displayItem();
        } elseif($incoming_text === '/getconstractioncalculation') {
            // محاسبات زیربنا، هزینه و  مشارکت در ساخت     
            $isCommand = true;
            $constructionCalculationSection = new ConstructionCalculation($telegram);
            $constructionCalculationSection->displayItem();
        } elseif($incoming_text === '/getconstcalcarea') {
            // محاسبه زیربنا 
            $isCommand = true;
            $constCalcArea = new ConstCalcArea($telegram);
            $constCalcArea->displayItem();
        } elseif($incoming_text === '/getbeamandblockroof') {
            // محاسبه هزینه ساخت 
            // نسبت منصفانه مشارکت در ساخت 
    
            // سقف تیرچه و بلوک
            $isCommand = true;
            $beamAndBlockRoofCalculation = new BeamAndBlockRoofCalculation($telegram);
            $beamAndBlockRoofCalculation->displayItem();
        } elseif($incoming_text === '/beamandblockroofsendpamameteratext') {
            $isCommand = true;
            $beamAndBlockRoofCalculation = new BeamAndBlockRoofParameters($telegram);
            $beamAndBlockRoofCalculation->processParameterSubmission();
        } elseif($incoming_text === '/beamandblockroofdownloadresults') {
            $isCommand = true;
            $beamAndBlockRoofCalculation = new BeamAndBlockRoofParameters($telegram);
            $beamAndBlockRoofCalculation->downloadResults();
        } elseif($incoming_text === '/beamandblockroofresetresults') {
            $isCommand = true;
            $beamAndBlockRoofCalculation = new BeamAndBlockRoofParameters($telegram);
            $beamAndBlockRoofCalculation->resetResults();
        } elseif(!$isCommand) {
            // دریافت ورودی های کاربر
            try {
                $userPrompts = new UserPrompts($telegram);
                $userPrompts->checkUserPrompt();
            } catch(Exception $e) {
                \Log::info('An error occurred: ' . $e->getMessage());
            }
        }
    }
}
