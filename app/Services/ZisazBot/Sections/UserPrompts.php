<?php

namespace App\Services\ZisazBot\Sections;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\BeamAndBlockRoof\BeamAndBlockRoof;

class UserPrompts extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = !empty($telegram->ChatID()) ? User::where('chat_id', $telegram->ChatID())->first() : null;
    }

    public function checkUserPromt() {
        $action = $this->user->actions()->create([
            'subaction_id' => null
        ]);
        
        // $beamAndBlockRoof = BeamAndBlockRoof::create([
        //     'a' => !empty(trim($this->telegram->Text())) ? trim($this->telegram->Text()) : null,
        // ]);

        $action->beamAndBlockRoof()->create([
            'a' => !empty(trim($this->telegram->Text())) ? trim($this->telegram->Text()) : null,
        ]);

        $action->update([
            'subaction_id' => $beamAndBlockRoof->id,
            'subaction_type' => 'App\Models\Action\BeamAndBlockRoof'
        ]);
    }
} 