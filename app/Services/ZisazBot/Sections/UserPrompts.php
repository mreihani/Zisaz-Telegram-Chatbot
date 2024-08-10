<?php

namespace App\Services\ZisazBot\Sections;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionCalculation;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofCalculation;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\BrickWallMasonryCalculation;

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

        // ورودی های کاربر برای دیوار چینی بلوکی و آجری
        if($latestAction->subaction_type === 'App\Models\Action\BrickWallMasonry\BrickWallMasonry') {
            $brickWallMasonryCalculation = new BrickWallMasonryCalculation($this->telegram);
            $brickWallMasonryCalculation->getUserPrompts();
        }
       
    }
} 