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

    public function sendMessageWithInlineKeyBoard($telegram, $keyb, $text) {
        $chat_id = $telegram->ChatID();

        if($telegram->getUpdateType() === 'callback_query') {
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text, 'message_id' => $telegram->MessageID());
            $telegram->editMessageText($content);
        } else {
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text);
            $telegram->sendMessage($content);
        }
    }

    public function setUser($telegram) {
        if(empty($telegram->ChatID())) {
           return;
        }

        $user = User::updateOrCreate([
            'chat_id' => $telegram->ChatID()
        ],[
            'firstname' => !empty($telegram->FirstName()) ? $telegram->FirstName() : null,
            'lastname' => !empty($telegram->LastName()) ? $telegram->LastName() : null,
            'username' => !empty($telegram->Username()) ? $telegram->Username() : null,
        ]);

        return $user;
    }
} 