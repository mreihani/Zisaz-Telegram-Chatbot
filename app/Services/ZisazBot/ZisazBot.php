<?php

namespace App\Services\ZisazBot;

use App\Models\User;
abstract class ZisazBot {

    public function sendMessage($telegram, $text) {
        $chat_id = $telegram->ChatID();

        if($telegram->getUpdateType() === 'callback_query') {
            $content = array('chat_id' => $chat_id,'text' => $text, 'message_id' => $telegram->MessageID());
            $result = $telegram->editMessageText($content);
        } else {
            $content = array('chat_id' => $chat_id,'text' => $text);
            $result = $telegram->sendMessage($content);
        }

        $this->deleteUserMessages($telegram);
        $this->saveMessageId($telegram, $result);
    }

    public function sendMessageWithInlineKeyBoard($telegram, $keyb, $text) {
        $chat_id = $telegram->ChatID();

        if($telegram->getUpdateType() === 'callback_query') {
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text, 'message_id' => $telegram->MessageID());
            $result = $telegram->editMessageText($content);
        } else {
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text);
            $result = $telegram->sendMessage($content);
        }

        $this->deleteUserMessages($telegram);
        $this->saveMessageId($telegram, $result);
    }

    public function getUser($telegram) {

        $chat_id = $telegram->ChatID();

        if($telegram === null || empty($chat_id) || $chat_id < 0) {
            throw new \Exception('telegram chat_id is null, or zero, or a negative number!');
        }

        $user = User::where('chat_id', $chat_id)->first();

        if(empty($user)) {
            $user = User::create([
                'chat_id' => $chat_id,
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
            throw new \Exception('Latest Action object is empty');
        }
    
        return $latestAction;
    }

    // this is from inline keyboards
    // این متد برای ذخیره شماره پیام هایی است که تلگرام به کاربر ارسال می کند مثل دکمه های شیشه ای
    public function saveMessageId($telegram, $result) {
        $messageId = (!empty($result) && !empty($result['result']) && !empty($result['result']['message_id'])) ? $result['result']['message_id'] : null;

        if(empty($messageId)) {
            return;
        }

        $user = $this->getUser($telegram);

        $user->messages()->updateOrCreate([
            'message_id' => $messageId
        ]);
    }

    // this method is for user inputs
    // این متد برای ذخیره شماره پیام هایی است که کاربر به تلگرام ارسال می کند مثل کامند ها یا تایپ می کند
    public function saveMessageIdUserPrompt($telegram) {
        $messageId = $telegram->MessageID();

        if(empty($messageId)) {
            return;
        }

        $user = $this->getUser($telegram);
        $user->messages()->updateOrCreate([
            'message_id' => $messageId
        ]);
    }

    public function getUserMessageIdsArray($telegram) {
        $user = $this->getUser($telegram);

        $messageIds = $user->messages->pluck('message_id')->toArray();

        return $messageIds;
    }

    public function deleteUserMessages($telegram) {
        $chat_id = $telegram->ChatID();
        $user = $this->getUser($telegram);
        $messageIds = $this->getUserMessageIdsArray($telegram);

        // Omit the last message_id from the array
        array_pop($messageIds);

        $content = ['chat_id' => $chat_id, 'message_ids' => json_encode($messageIds)];

        $telegram->deleteMessages($content);

        // delete all user messages
        $user->messages()->whereIn('message_id', $messageIds)->delete();
    }
} 