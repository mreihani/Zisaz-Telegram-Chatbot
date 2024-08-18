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
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\RebarAndStirrupCalculation;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofBotResponse;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofCalculation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\BrickWallMasonryCalculation;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\ConcretingMatrialsCalculation;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\RampAndExpansionJointCalculation;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarWeight\RebarWeightBotResponse;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarWeight\RebarWeightCalculation;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\Concreting\ConcretingBotResponse;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\Concreting\ConcretingCalculation;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampSteep\RampSteepBotResponse;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampSteep\RampSteepCalculation;
use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\FacadeAndFlooringMaterialCalculation;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampLength\RampLengthBotResponse;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampLength\RampLengthCalculation;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\StirrupWeight\StirrupWeightBotResponse;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\StirrupWeight\StirrupWeightCalculation;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarConversion\RebarConversionBotResponse;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarConversion\RebarConversionCalculation;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\ExpansionJoint\ExpansionJointBotResponse;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\ExpansionJoint\ExpansionJointCalculation;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\ColumnConcreting\ColumnConcretingBotResponse;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\ColumnConcreting\ColumnConcretingCalculation;
use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\DecorativeStone\DecorativeStoneBotResponse;
use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\DecorativeStone\DecorativeStoneCalculation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryGarden\BrickWallMasonryGardenBotResponse;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryGarden\BrickWallMasonryGardenCalculation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPartition\BrickWallMasonryPartitionBotResponse;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPartition\BrickWallMasonryPartitionCalculation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPressedBrick\BrickWallMasonryPressedBrickBotResponse;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPressedBrick\BrickWallMasonryPressedBrickCalculation;
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

        // if(trim($telegram->ChatID()) !== '103534250') {
        //     return;
        // }

        // start command
        if($incoming_text === '/start') {
            $isCommand = true;
            $startSection = new StartSection($telegram);
            return $startSection->displayItem();

            // محاسبات زیربنا، هزینه و  مشارکت در ساخت     
        } elseif($incoming_text === '/getconstructioncalculation' || $incoming_text == '👷🏽 محاسبات زیربنا، هزینه و مشارکت در ساخت') {
            $isCommand = true;
            $constructionCalculationSection = new ConstructionCalculation($telegram);
            return $constructionCalculationSection->displayItem();
        } elseif($incoming_text === '/constructionsendpamameteratext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $constructionBotResponse = new ConstructionBotResponse($telegram);
            return $constructionBotResponse->processParameterSubmission();
        } elseif($incoming_text === '/constructioncalcexpensedownloadresults') {
            // دانلود پی دی اف محاسبات  کل  زیر بنا و هزینه ساخت
            $isCommand = true;
            $constructionBotResponse = new ConstructionBotResponse($telegram);
            return $constructionBotResponse->downloadConstCalcExpenseResults();
        } elseif($incoming_text === '/constructioncalccollaborativedownloadresults') {
            // دانلود پی دی اف محاسبات  مشارکت در ساخت منصفانه
            $isCommand = true;
            $constructionBotResponse = new ConstructionBotResponse($telegram);
            return $constructionBotResponse->downloadConstCalcCollaborativeResults();
        } elseif($incoming_text === '/constructionresetresults') {
            // پروژه جدید
            $isCommand = true;
            $constructionBotResponse = new ConstructionBotResponse($telegram);
            return $constructionBotResponse->resetResults();
            
            // محاسبات سقف تیرچه و بلوک
        } elseif($incoming_text === '/getbeamandblockroof' || $incoming_text == '🏗️ سقف تیرچه و بلوک') {
            $isCommand = true;
            $beamAndBlockRoofCalculation = new BeamAndBlockRoofCalculation($telegram);
            return $beamAndBlockRoofCalculation->displayItem();
        } elseif($incoming_text === '/beamandblockroofsendpamameteratext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $beamAndBlockRoofBotResponse = new BeamAndBlockRoofBotResponse($telegram);
            return $beamAndBlockRoofBotResponse->processParameterSubmission();
        } elseif($incoming_text === '/beamandblockroofdownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $beamAndBlockRoofBotResponse = new BeamAndBlockRoofBotResponse($telegram);
            return $beamAndBlockRoofBotResponse->downloadResults();
        } elseif($incoming_text === '/beamandblockroofresetresults') {
            // پروژه جدید
            $isCommand = true;
            $beamAndBlockRoofBotResponse = new BeamAndBlockRoofBotResponse($telegram);
            return $beamAndBlockRoofBotResponse->resetResults();
            
            // محاسبات دیوار چینی بلوکی و آجری 
        } elseif($incoming_text === '/getbrickwallmasonry' || $incoming_text == '🧱 دیوار چینی') {
            $isCommand = true;
            $brickWallMasonryCalculation = new BrickWallMasonryCalculation($telegram);
            return $brickWallMasonryCalculation->displayItem();
            
            // دیوار پارتیشن بلوکی آپارتمان
        } elseif($incoming_text === '/brickwallmasonryapartmentblock') {
            $isCommand = true;
            $brickWallMasonryApartmentBlock = new BrickWallMasonryApartmentBlockCalculation($telegram);
            return $brickWallMasonryApartmentBlock->displayItem();
        } elseif($incoming_text === '/brickwallmasonryapartmentblocksendpamameteratext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $brickWallMasonryApartmentBlock = new BrickWallMasonryApartmentBlockBotResponse($telegram);
            return $brickWallMasonryApartmentBlock->processParameterSubmission();
        } elseif($incoming_text === '/brickwallmasonryapartmentblockdownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $brickWallMasonryApartmentBlock = new BrickWallMasonryApartmentBlockBotResponse($telegram);
            return $brickWallMasonryApartmentBlock->downloadResults();
        } elseif($incoming_text === '/brickwallmasonryapartmentblockresetresults') {
            // پروژه جدید
            $isCommand = true;
            $brickWallMasonryApartmentBlock = new BrickWallMasonryApartmentBlockBotResponse($telegram);
            return $brickWallMasonryApartmentBlock->resetResults();

            // دیوار بلوکی حصار باغ یا حیاط 
        } elseif($incoming_text === '/brickwallmasonrygarden') {
            $isCommand = true;
            $brickWallMasonryGarden = new BrickWallMasonryGardenCalculation($telegram);
            return $brickWallMasonryGarden->displayItem();
        } elseif($incoming_text === '/brickwallmasonrygardensendpamameterltext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $brickWallMasonryGarden = new BrickWallMasonryGardenBotResponse($telegram);
            return $brickWallMasonryGarden->processParameterSubmission();
        } elseif($incoming_text === '/brickwallmasonrygardendownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $brickWallMasonryGarden = new BrickWallMasonryGardenBotResponse($telegram);
            return $brickWallMasonryGarden->downloadResults();
        } elseif($incoming_text === '/brickwallmasonrygardenresetresults') {
            // پروژه جدید
            $isCommand = true;
            $brickWallMasonryGarden = new BrickWallMasonryGardenBotResponse($telegram);
            return $brickWallMasonryGarden->resetResults();

            // آجر فشاری یا سه گل 
        } elseif($incoming_text === '/brickwallmasonrypressedbrick') {
            $isCommand = true;
            $brickWallMasonryPressedBrick = new BrickWallMasonryPressedBrickCalculation($telegram);
            return $brickWallMasonryPressedBrick->displayItem();
        } elseif($incoming_text === '/brickwallmasonrypressedbricksendpamameteratext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $brickWallMasonryPressedBrick = new BrickWallMasonryPressedBrickBotResponse($telegram);
            return $brickWallMasonryPressedBrick->processParameterSubmission();
        } elseif($incoming_text === '/brickwallmasonrypressedbrickdownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $brickWallMasonryPressedBrick = new BrickWallMasonryPressedBrickBotResponse($telegram);
            return $brickWallMasonryPressedBrick->downloadResults();
        } elseif($incoming_text === '/brickwallmasonrypressedbrickresetresults') {
            // پروژه جدید
            $isCommand = true;
            $brickWallMasonryPressedBrick = new BrickWallMasonryPressedBrickBotResponse($telegram);
            return $brickWallMasonryPressedBrick->resetResults();
            
            // دیوار با آجر پارتیشن
        } elseif($incoming_text === '/brickwallmasonrypartition') {
            $isCommand = true;
            $brickWallMasonryPartition = new BrickWallMasonryPartitionCalculation($telegram);
            return $brickWallMasonryPartition->displayItem();
        } elseif($incoming_text === '/brickwallmasonrypartitionsendpamameteratext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $brickWallMasonryPartition = new BrickWallMasonryPartitionBotResponse($telegram);
            return $brickWallMasonryPartition->processParameterSubmission();
        } elseif($incoming_text === '/brickwallmasonrypartitiondownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $brickWallMasonryPartition = new BrickWallMasonryPartitionBotResponse($telegram);
            return $brickWallMasonryPartition->downloadResults();
        } elseif($incoming_text === '/brickwallmasonrypartitionresetresults') {
            // پروژه جدید
            $isCommand = true;
            $brickWallMasonryPartition = new BrickWallMasonryPartitionBotResponse($telegram);
            return $brickWallMasonryPartition->resetResults();
            
            // محاسبات رمپ و درز انقطاع
        } elseif($incoming_text === '/getrampandexpansionjoint' || $incoming_text == '📐 محاسبات رمپ و درز انقطاع') {
            $isCommand = true;
            $rampAndExpansionJointCalculation = new RampAndExpansionJointCalculation($telegram);
            return $rampAndExpansionJointCalculation->displayItem();

            // شیب رمپ
        } elseif($incoming_text === '/rampsteep') {
            $isCommand = true;
            $rampSteep = new RampSteepCalculation($telegram);
            return $rampSteep->displayItem();
        } elseif($incoming_text === '/rampsteepsendpamameterhtext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $rampSteep = new RampSteepBotResponse($telegram);
            return $rampSteep->processParameterSubmission();
        } elseif($incoming_text === '/rampsteepdownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $rampSteep = new RampSteepBotResponse($telegram);
            return $rampSteep->downloadResults();
        } elseif($incoming_text === '/rampsteepresetresults') {
            // پروژه جدید
            $isCommand = true;
            $rampSteep = new RampSteepBotResponse($telegram);
            return $rampSteep->resetResults();

            // طول رمپ
        } elseif($incoming_text === '/ramplength') {
            $isCommand = true;
            $rampLength = new RampLengthCalculation($telegram);
            return $rampLength->displayItem();
        } elseif($incoming_text === '/ramplengthsendpamameterhtext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $rampLength = new RampLengthBotResponse($telegram);
            return $rampLength->processParameterSubmission();
        } elseif($incoming_text === '/ramplengthdownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $rampLength = new RampLengthBotResponse($telegram);
            return $rampLength->downloadResults();
        } elseif($incoming_text === '/ramplengthresetresults') {
            // پروژه جدید
            $isCommand = true;
            $rampLength = new RampLengthBotResponse($telegram);
            return $rampLength->resetResults();

            // محاسبه درز انقطاع ( ژوئن)
        } elseif($incoming_text === '/expansionjoint') {
            $isCommand = true;
            $expansionJoint = new ExpansionJointCalculation($telegram);
            return $expansionJoint->displayItem();
        } elseif($incoming_text === '/expansionjointsendpamameterhtext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $expansionJoint = new ExpansionJointBotResponse($telegram);
            return $expansionJoint->processParameterSubmission();
        } elseif($incoming_text === '/expansionjointdownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $expansionJoint = new ExpansionJointBotResponse($telegram);
            return $expansionJoint->downloadResults();
        } elseif($incoming_text === '/expansionjointresetresults') {
            // پروژه جدید
            $isCommand = true;
            $expansionJoint = new ExpansionJointBotResponse($telegram);
            return $expansionJoint->resetResults();

            // محاسبات وزن میلگرد و خاموت
        } elseif($incoming_text === '/getrebarandstirrup' || $incoming_text == '➰ وزن میلگرد و خاموت') {
            $isCommand = true;
            $rebarAndStirrupCalculation = new RebarAndStirrupCalculation($telegram);
            return $rebarAndStirrupCalculation->displayItem();

            // محاسبه وزن یک شاخه میلگرد
        } elseif($incoming_text === '/rebarweight') {
            $isCommand = true;
            $rebarWeight = new RebarWeightCalculation($telegram);
            return $rebarWeight->displayItem();
        } elseif($incoming_text === '/rebarweightsendpamameterdtext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $rebarWeight = new RebarWeightBotResponse($telegram);
            return $rebarWeight->processParameterSubmission();
        } elseif($incoming_text === '/rebarweightdownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $rebarWeight = new RebarWeightBotResponse($telegram);
            return $rebarWeight->downloadResults();
        } elseif($incoming_text === '/rebarweightresetresults') {
            // پروژه جدید
            $isCommand = true;
            $rebarWeight = new RebarWeightBotResponse($telegram);
            return $rebarWeight->resetResults();

            // محاسبه وزن خاموت
        } elseif($incoming_text === '/stirrupweight') {
            $isCommand = true;
            $stirrupWeight = new StirrupWeightCalculation($telegram);
            return $stirrupWeight->displayItem();
        } elseif($incoming_text === '/stirrupweightsendpamameterdtext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $stirrupWeight = new StirrupWeightBotResponse($telegram);
            return $stirrupWeight->processParameterSubmission();
        } elseif($incoming_text === '/stirrupweightdownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $stirrupWeight = new StirrupWeightBotResponse($telegram);
            return $stirrupWeight->downloadResults();
        } elseif($incoming_text === '/stirrupweightresetresults') {
            // پروژه جدید
            $isCommand = true;
            $stirrupWeight = new StirrupWeightBotResponse($telegram);
            return $stirrupWeight->resetResults();
            
            // معادل سازی میلگرد
        } elseif($incoming_text === '/rebarconversion') {
            $isCommand = true;
            $rebarConversion = new RebarConversionCalculation($telegram);
            return $rebarConversion->displayItem();
        } elseif($incoming_text === '/rebarconversiond1text') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $rebarConversion = new RebarConversionBotResponse($telegram);
            return $rebarConversion->processParameterSubmission();
        } elseif($incoming_text === '/rebarconversiondownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $rebarConversion = new RebarConversionBotResponse($telegram);
            return $rebarConversion->downloadResults();
        } elseif($incoming_text === '/rebarconversionresetresults') {
            // پروژه جدید
            $isCommand = true;
            $rebarConversion = new RebarConversionBotResponse($telegram);
            return $rebarConversion->resetResults();

            // محاسبه مصالح بتن ریزی
        } elseif($incoming_text === '/getconcretingmaterials' || $incoming_text == '🌫️ محاسبه مصالح بتون ریزی') {
            $isCommand = true;
            $concretingMatrialsCalculation = new ConcretingMatrialsCalculation($telegram);
            return $concretingMatrialsCalculation->displayItem();

            // محاسبه مصالح بتن ریزی
        } elseif($incoming_text === '/concreting') {
            $isCommand = true;
            $concreting = new ConcretingCalculation($telegram);
            return $concreting->displayItem();
        } elseif($incoming_text === '/concretingsendpamametervtext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $concreting = new ConcretingBotResponse($telegram);
            return $concreting->processParameterSubmission();
        } elseif($incoming_text === '/concretingdownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $concreting = new ConcretingBotResponse($telegram);
            return $concreting->downloadResults();
        } elseif($incoming_text === '/concretingresetresults') {
            // پروژه جدید
            $isCommand = true;
            $concreting = new ConcretingBotResponse($telegram);
            return $concreting->resetResults();

            // محاسبه مصالح بتن ریزی ستون ها
        } elseif($incoming_text === '/columnconcreting') {
            $isCommand = true;
            $columnconcreting = new ColumnConcretingCalculation($telegram);
            return $columnconcreting->displayItem();
        } elseif($incoming_text === '/columnconcretingsendpamametervtext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $columnconcreting = new ColumnConcretingBotResponse($telegram);
            return $columnconcreting->processParameterSubmission();
        } elseif($incoming_text === '/columnconcretingdownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $columnconcreting = new ColumnConcretingBotResponse($telegram);
            return $columnconcreting->downloadResults();
        } elseif($incoming_text === '/columnconcretingresetresults') {
            // پروژه جدید
            $isCommand = true;
            $columnconcreting = new ColumnConcretingBotResponse($telegram);
            return $columnconcreting->resetResults();

            // محاسبه مصالح نما و کف
        } elseif($incoming_text === '/getfacadeandflooringmaterialcalculation' || $incoming_text == '🏢 مصالح نما و کف ساختمان') {
            $isCommand = true;
            $facadeAndFlooringMaterialCalculation = new FacadeAndFlooringMaterialCalculation($telegram);
            return $facadeAndFlooringMaterialCalculation->displayItem();

            // محاسبه مصالح سنگ نما
        } elseif($incoming_text === '/decorativestone') {
            $isCommand = true;
            $decorativeStone = new DecorativeStoneCalculation($telegram);
            return $decorativeStone->displayItem();
        } elseif($incoming_text === '/decorativestonesendpamameterttext') {
            // دریافت اطلاعات و پارامتر های محاسباتی
            $isCommand = true;
            $decorativeStone = new DecorativeStoneBotResponse($telegram);
            return $decorativeStone->processParameterSubmission();
        } elseif($incoming_text === '/decorativestonedownloadresults') {
            // دانلود پی دی اف
            $isCommand = true;
            $decorativeStone = new DecorativeStoneBotResponse($telegram);
            return $decorativeStone->downloadResults();
        } elseif($incoming_text === '/decorativestoneresetresults') {
            // پروژه جدید
            $isCommand = true;
            $decorativeStone = new DecorativeStoneBotResponse($telegram);
            return $decorativeStone->resetResults();

            // دریافت کلیه ورودی های تایپ شده کاربر
        } elseif(!$isCommand) {
            try {
                $userPrompts = new UserPrompts($telegram);
                return $userPrompts->checkUserPrompt();
            } catch(Exception $e) {
                \Log::info('An error occurred: ' . $e->getMessage());
            }
        }
    }
}
