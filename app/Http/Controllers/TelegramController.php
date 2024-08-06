<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TelegramBotService;
use App\Services\TelegramBotPhp\Telegram;
use App\Services\ZisazBot\Sections\UserPrompts;
use App\Services\ZisazBot\Sections\StartSection;
use App\Models\Action\BeamAndBlockRoof\BeamAndBlockRoof;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionBotResponse;
use App\Services\ZisazBot\Sections\ConstructionCalculation\Sections\ConstCalcArea;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionCalculation;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofBotResponse;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofCalculation;

class TelegramController extends Controller
{
    public function inbound(Request $request) {

        $telegram = new Telegram(env('TELEGRAM_BOT_TOKEN'));
        $incoming_text = $telegram->Text();
        $isCommand = false;

        // https://api.telegram.org/bot6488918893:AAEdVK7H9e_t68YnWGHZpbdu1vY8XT5oH9A/setWebhook?url=https://jaban.ir/api/telegram/webhooks/inbound
        // $telegram->deleteWebhook();
        // return;
        if(trim($telegram->ChatID()) !== '103534250') {
            return;
        }

        // start command
        if($incoming_text === '/start') {
            $isCommand = true;
            $startSection = new StartSection($telegram);
            $startSection->displayItem();

            // محاسبات زیربنا، هزینه و  مشارکت در ساخت     
        } elseif($incoming_text === '/getconstructioncalculation' || $incoming_text == 'محاسبات زیربنا، هزینه و مشارکت در ساخت') {
            $isCommand = true;
            $constructionCalculationSection = new ConstructionCalculation($telegram);
            $constructionCalculationSection->displayItem();

        } elseif($incoming_text === '/constructionsendpamameteratext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $constructionBotResponse = new ConstructionBotResponse($telegram);
            $constructionBotResponse->processParameterSubmission();
        } elseif($incoming_text === '/getconstcalcexpenses') {
            // محاسبه هزینه ساخت 
            // نسبت منصفانه مشارکت در ساخت 



            
            // محاسبات سقف تیرچه و بلوک
        } elseif($incoming_text === '/getbeamandblockroof' || $incoming_text == 'سقف تیرچه و بلوک') {
            $isCommand = true;
            $beamAndBlockRoofCalculation = new BeamAndBlockRoofCalculation($telegram);
            $beamAndBlockRoofCalculation->displayItem();
        } elseif($incoming_text === '/beamandblockroofsendpamameteratext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $beamAndBlockRoofBotResponse = new BeamAndBlockRoofBotResponse($telegram);
            $beamAndBlockRoofBotResponse->processParameterSubmission();
        } elseif($incoming_text === '/beamandblockroofdownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $beamAndBlockRoofBotResponse = new BeamAndBlockRoofBotResponse($telegram);
            $beamAndBlockRoofBotResponse->downloadResults();
        } elseif($incoming_text === '/beamandblockroofresetresults') {
            // محاسبه مجدد
            $isCommand = true;
            $beamAndBlockRoofBotResponse = new BeamAndBlockRoofBotResponse($telegram);
            $beamAndBlockRoofBotResponse->resetResults();
            
            // دریافت کلیه ورودی های تایپ شده کاربر
        } elseif(!$isCommand) {
            try {
                $userPrompts = new UserPrompts($telegram);
                $userPrompts->checkUserPrompt();
            } catch(Exception $e) {
                \Log::info('An error occurred: ' . $e->getMessage());
            }
        }
    }
}
