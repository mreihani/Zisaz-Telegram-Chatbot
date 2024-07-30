<?php

namespace App\Services\ZisazBot;

use App\Models\User;
abstract class ZisazBot {

    public function sendMessage($telegram, $text) {
        $chat_id = $telegram->ChatID();

        if($telegram->getUpdateType() === 'callback_query') {
            $content = array('chat_id' => $chat_id,'text' => $text, 'message_id' => $telegram->MessageID());
            $telegram->editMessageText($content);
        } else {
            $content = array('chat_id' => $chat_id,'text' => $text);
            $telegram->sendMessage($content);
        }
    }

    public function sendMessageWithInlineKeyBoard($telegram, $keyb, $text, $img = null) {
        $chat_id = $telegram->ChatID();

        if(!is_null($img)) {
            $content = array('chat_id' => $chat_id, 'photo' => $img);
            $telegram->sendPhoto($content);
        }

        if($telegram->getUpdateType() === 'callback_query') {
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text, 'message_id' => $telegram->MessageID());
            $telegram->editMessageText($content);
        } else {
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text);
            $telegram->sendMessage($content);
        }
    }

    public function getUser($telegram) {
        if($telegram === null || empty($telegram->ChatID())) {
            throw new \Exception('telegram or chatId is null');
        }

        $user = User::where('chat_id', $telegram->ChatID())->first();

        if(empty($user)) {
            $user = User::create([
                'chat_id' => $telegram->ChatID(),
                'firstname' => !empty($telegram->FirstName()) ? $telegram->FirstName() : null,
                'lastname' => !empty($telegram->LastName()) ? $telegram->LastName() : null,
                'username' => !empty($telegram->Username()) ? $telegram->Username() : null,
            ]);
        }

        return $user;
    }

    public function initializeAction($model) {
        $this->user->actions()->updateOrCreate([
            'subaction_type' => get_class($model)
        ],[
            'updated_at' => now()
        ]);
    }

    // get the latest action object
    public function getLastActionObject($telegram) {

        $user = $this->getUser($telegram);
        $latestAction = $user->actions()->orderBy('updated_at', 'desc')->first();
        
        if(empty($latestAction)) {
            throw new \Exception('Latest action object is null');
        }
    
        return $latestAction;
    }

    
} 