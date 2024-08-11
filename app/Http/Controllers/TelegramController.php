<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TelegramBotService;
use App\Services\TelegramBotPhp\Telegram;
use App\Services\ZisazBot\Sections\UserPrompts;
use App\Services\ZisazBot\Sections\StartSection;
use App\Models\Action\BeamAndBlockRoof\BeamAndBlockRoof;
use App\Services\ZisazBot\Sections\ConstructionCalculation\Sections\ConstCalcArea;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionBotResponse;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionCalculation;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofBotResponse;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofCalculation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\BrickWallMasonryCalculation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryGarden\BrickWallMasonryGardenBotResponse;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryGarden\BrickWallMasonryGardenCalculation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryApartmentBlock\BrickWallMasonryApartmentBlockBotResponse;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryApartmentBlock\BrickWallMasonryApartmentBlockCalculation;

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
        } elseif($incoming_text === '/getconstructioncalculation' || $incoming_text == '👷🏽 محاسبات زیربنا، هزینه و مشارکت در ساخت') {
            $isCommand = true;
            $constructionCalculationSection = new ConstructionCalculation($telegram);
            $constructionCalculationSection->displayItem();
        } elseif($incoming_text === '/constructionsendpamameteratext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $constructionBotResponse = new ConstructionBotResponse($telegram);
            $constructionBotResponse->processParameterSubmission();
        } elseif($incoming_text === '/constructioncalcexpensedownloadresults') {
            // دانلود پی دی اف محاسبات  کل  زیر بنا و هزینه ساخت
            $isCommand = true;
            $constructionBotResponse = new ConstructionBotResponse($telegram);
            $constructionBotResponse->downloadConstCalcExpenseResults();
        } elseif($incoming_text === '/constructioncalccollaborativedownloadresults') {
            // دانلود پی دی اف محاسبات  مشارکت در ساخت منصفانه
            $isCommand = true;
            $constructionBotResponse = new ConstructionBotResponse($telegram);
            $constructionBotResponse->downloadConstCalcCollaborativeResults();
        } elseif($incoming_text === '/constructionresetresults') {
            // پروژه جدید
            $isCommand = true;
            $constructionBotResponse = new ConstructionBotResponse($telegram);
            $constructionBotResponse->resetResults();
            
            // محاسبات سقف تیرچه و بلوک
        } elseif($incoming_text === '/getbeamandblockroof' || $incoming_text == '🏗️ سقف تیرچه و بلوک') {
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
            // پروژه جدید
            $isCommand = true;
            $beamAndBlockRoofBotResponse = new BeamAndBlockRoofBotResponse($telegram);
            $beamAndBlockRoofBotResponse->resetResults();
            
            // محاسبات دیوار چینی بلوکی و آجری 
        } elseif($incoming_text === '/getbrickwallmasonry' || $incoming_text == '🧱 دیوار چینی') {
            $isCommand = true;
            $brickWallMasonryCalculation = new BrickWallMasonryCalculation($telegram);
            $brickWallMasonryCalculation->displayItem();
            
            // دیوار پارتیشن بلوکی آپارتمان
        } elseif($incoming_text == '/brickwallmasonryapartmentblock') {
            $isCommand = true;
            $brickWallMasonryApartmentBlock = new BrickWallMasonryApartmentBlockCalculation($telegram);
            $brickWallMasonryApartmentBlock->displayItem();
        } elseif($incoming_text == '/brickwallmasonryapartmentblocksendpamameteratext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $brickWallMasonryApartmentBlock = new BrickWallMasonryApartmentBlockBotResponse($telegram);
            $brickWallMasonryApartmentBlock->processParameterSubmission();
        } elseif($incoming_text === '/brickwallmasonryapartmentblockdownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $brickWallMasonryApartmentBlock = new BrickWallMasonryApartmentBlockBotResponse($telegram);
            $brickWallMasonryApartmentBlock->downloadResults();
        } elseif($incoming_text === '/brickwallmasonryapartmentblockresetresults') {
            // پروژه جدید
            $isCommand = true;
            $brickWallMasonryApartmentBlock = new BrickWallMasonryApartmentBlockBotResponse($telegram);
            $brickWallMasonryApartmentBlock->resetResults();

            // دیوار بلوکی حصار باغ یا حیاط 
        } elseif($incoming_text == '/brickwallmasonrygarden') {
            $isCommand = true;
            $brickWallMasonryGarden = new BrickWallMasonryGardenCalculation($telegram);
            $brickWallMasonryGarden->displayItem();
        } elseif($incoming_text == '/brickwallmasonrygardensendpamameterltext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $brickWallMasonryGarden = new BrickWallMasonryGardenBotResponse($telegram);
            $brickWallMasonryGarden->processParameterSubmission();
        } elseif($incoming_text === '/brickwallmasonrygardendownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $brickWallMasonryGarden = new BrickWallMasonryGardenBotResponse($telegram);
            $brickWallMasonryGarden->downloadResults();
        } elseif($incoming_text === '/brickwallmasonrygardenresetresults') {
            // پروژه جدید
            $isCommand = true;
            $brickWallMasonryGarden = new BrickWallMasonryGardenBotResponse($telegram);
            $brickWallMasonryGarden->resetResults();

        } elseif($incoming_text == '/brickwallmasonrypartition') {
            
        } elseif($incoming_text == '/brickwallmasonrypressedbrick') {
            
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
