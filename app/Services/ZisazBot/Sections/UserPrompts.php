<?php

namespace App\Services\ZisazBot\Sections;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionCalculation;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofCalculation;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarWeight\RebarWeightCalculation;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\Concreting\ConcretingCalculation;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampSteep\RampSteepCalculation;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampLength\RampLengthCalculation;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\StirrupWeight\StirrupWeightCalculation;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarConversion\RebarConversionCalculation;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\ExpansionJoint\ExpansionJointCalculation;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\ColumnConcreting\ColumnConcretingCalculation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryGarden\BrickWallMasonryGardenCalculation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPartition\BrickWallMasonryPartitionCalculation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPressedBrick\BrickWallMasonryPressedBrickCalculation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryApartmentBlock\BrickWallMasonryApartmentBlockCalculation;

class UserPrompts extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function checkUserPrompt() {

        if(empty($this->user)) {
            throw new \Exception('User is empty');
        }

        // هر وقت به مشکل خوردی این رو فعال کن
        if(env('ZISAZ_BOT_LASTOBJECT_DEBUG')) {
            return;
        }
        
        $latestAction = $this->getLastActionObject($this->telegram);

        // ورودی های کاربران برای محاسبات زیربنا، هزینه و مشارکت در ساخت
        if($latestAction->subaction_type === 'App\Models\Action\Construction\Construction') {
            $constructionCalculation = new ConstructionCalculation($this->telegram);
            $constructionCalculation->getUserPrompts();
        }
        
        // ورودی های کاربران برای سقف تیرچه و بلوک
        if($latestAction->subaction_type === 'App\Models\Action\BeamAndBlockRoof\BeamAndBlockRoof') {
            $beamAndBlockRoofCalculation = new BeamAndBlockRoofCalculation($this->telegram);
            $beamAndBlockRoofCalculation->getUserPrompts();
        }
        
        // ورودی های کاربر برای دیوار چینی بلوکی و آجری - دیوار پارتیشن بلوکی آپارتمان
        if($latestAction->subaction_type === 'App\Models\Action\BrickWallMasonry\BrickWallMasonryApartmentBlock') {
            $brickWallMasonryCalculation = new BrickWallMasonryApartmentBlockCalculation($this->telegram);
            $brickWallMasonryCalculation->getUserPrompts();
        }

        // ورودی های کاربر برای دیوار چینی بلوکی و آجری - دیوار بلوکی حصار باغ یا حیاط
        if($latestAction->subaction_type === 'App\Models\Action\BrickWallMasonry\BrickWallMasonryGarden') {
            $brickWallMasonryCalculation = new BrickWallMasonryGardenCalculation($this->telegram);
            $brickWallMasonryCalculation->getUserPrompts();
        }
        
        // ورودی های کاربر برای دیوار چینی بلوکی و آجری - برآورد دیوار با آجر فشاری یا سه گل
        if($latestAction->subaction_type === 'App\Models\Action\BrickWallMasonry\BrickWallMasonryPressedBrick') {
            $brickWallMasonryCalculation = new BrickWallMasonryPressedBrickCalculation($this->telegram);
            $brickWallMasonryCalculation->getUserPrompts();
        }

        // ورودی های کاربر برای دیوار چینی بلوکی و آجری - برآورد دیوار با آجر پارتیشن
        if($latestAction->subaction_type === 'App\Models\Action\BrickWallMasonry\BrickWallMasonryPartition') {
            $brickWallMasonryCalculation = new BrickWallMasonryPartitionCalculation($this->telegram);
            $brickWallMasonryCalculation->getUserPrompts();
        }

        // ورودی های کاربر برای محاسبات رمپ و درز انقطاع - محاسبه شیب رمپ
        if($latestAction->subaction_type === 'App\Models\Action\Ramp\RampSteep') {
            $rampAndExpansionJointCalculation = new RampSteepCalculation($this->telegram);
            $rampAndExpansionJointCalculation->getUserPrompts();
        }

        // ورودی های کاربر برای محاسبات رمپ و درز انقطاع - محاسبه طول رمپ
        if($latestAction->subaction_type === 'App\Models\Action\Ramp\RampLength') {
            $rampAndExpansionJointCalculation = new RampLengthCalculation($this->telegram);
            $rampAndExpansionJointCalculation->getUserPrompts();
        }

        // ورودی های کاربر برای محاسبات رمپ و درز انقطاع - محاسبه درز انقطاع
        if($latestAction->subaction_type === 'App\Models\Action\Ramp\ExpansionJoint') {
            $rampAndExpansionJointCalculation = new ExpansionJointCalculation($this->telegram);
            $rampAndExpansionJointCalculation->getUserPrompts();
        }

        // محاسبات وزن میلگرد و خامود - وزن میلگرد
        if($latestAction->subaction_type === 'App\Models\Action\RebarAndStirrup\RebarWeight') {
            $rebarAndStirrupCalculation = new RebarWeightCalculation($this->telegram);
            $rebarAndStirrupCalculation->getUserPrompts();
        }

        // محاسبات وزن میلگرد و خامود - وزن خاموت
        if($latestAction->subaction_type === 'App\Models\Action\RebarAndStirrup\StirrupWeight') {
            $rebarAndStirrupCalculation = new StirrupWeightCalculation($this->telegram);
            $rebarAndStirrupCalculation->getUserPrompts();
        }

        // محاسبات وزن میلگرد و خامود - معادل سازی میلگرد
        if($latestAction->subaction_type === 'App\Models\Action\RebarAndStirrup\RebarConversion') {
            $rebarAndStirrupCalculation = new RebarConversionCalculation($this->telegram);
            $rebarAndStirrupCalculation->getUserPrompts();
        }

        // محاسبات مصالح مورد نیاز بتن ریزی
        if($latestAction->subaction_type === 'App\Models\Action\Concreting\Concreting') {
            $concretingCalculation = new ConcretingCalculation($this->telegram);
            $concretingCalculation->getUserPrompts();
        }

        // محاسبات مصالح مورد نیاز بتن ریزی ستون ها
        if($latestAction->subaction_type === 'App\Models\Action\Concreting\ColumnConcreting') {
            $columnConcretingCalculation = new ColumnConcretingCalculation($this->telegram);
            $columnConcretingCalculation->getUserPrompts();
        }
    }
} 