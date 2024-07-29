<?php

namespace App\Services\ZisazBot\Sections;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofCalculation;

class UserPrompts extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
    }

    public function checkUserPrompt() {

        if(empty($this->user)) {
            return;
        }

        $latestAction = $this->user->actions()->orderBy('updated_at', 'desc')->first();

        if(empty($latestAction)) {
            return;
        }
        
        if($latestAction->subaction_type === 'App\Models\Action\BeamAndBlockRoof\BeamAndBlockRoof') {
            $beamAndBlockRoofCalculation = new BeamAndBlockRoofCalculation($this->telegram);
            $beamAndBlockRoofCalculation->getUserPrompts();
        }
        
       
    }
} 