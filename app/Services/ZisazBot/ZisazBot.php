<?php

namespace App\Services\ZisazBot;

use App\Models\User;
use App\Models\Message\Message;
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

            $this->saveMessageId($telegram, $result);
            $this->deleteUserMessages($telegram);
        }
    }

    public function sendMessageWithKeyBoard($telegram, $text) {
        $chat_id = $telegram->ChatID();

        // send an image with keyboard markup
        // get the image file
        $img = curl_file_create(asset('assets/img/start.jpg'), 'image/jpg'); 

        $option = array( 
            // First row
            array($telegram->buildKeyboardButton('👷🏽 محاسبات زیربنا، هزینه و مشارکت در ساخت')), 
            // Second row 
            array($telegram->buildKeyboardButton('📐 محاسبات رمپ و درز انقطاع'), $telegram->buildKeyboardButton('🧱 دیوار چینی'), $telegram->buildKeyboardButton('🏗️ سقف تیرچه و بلوک')), 
            // Third row
            array($telegram->buildKeyboardButton('🏢 مصالح نما و کف ساختمان'), $telegram->buildKeyboardButton('🌫️ محاسبه مصالح بتون ریزی'), $telegram->buildKeyboardButton('➰ وزن میلگرد و خاموت')), 
            // Fourth row
            array($telegram->buildKeyboardButton('🙋 پیشنهادات'), $telegram->buildKeyboardButton('🚨 پشتیبانی'), $telegram->buildKeyboardButton('❓ سوالات متداول')), 
        );

        $keyb = $telegram->buildKeyBoard($option, $onetime = true, $resize = true, $is_persistent = true);
        
        $content = array('chat_id' => $chat_id, 'photo' => $img, 'reply_markup' => $keyb, 'caption' => $text);
        $result = $telegram->sendPhoto($content);

        $this->saveMessageId($telegram, $result);
        $this->deleteUserMessages($telegram);
    }

    public function getUser($telegram) {

        $chat_id = $telegram->ChatID();

        if($telegram === null || empty($chat_id) || $chat_id < 0) {
            throw new \Exception('telegram chat_id is null, or zero, or a negative number!');
        }

        $user = User::where('chat_id', $chat_id)->first();
        
        if(empty($user)) {
            $user = User::create([
                'user_id' => !empty($telegram->UserID()) ? $telegram->UserID() : null,
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
            'message_id' => $messageId,
            'text' => !empty($telegram->Text()) ? $telegram->Text() : null,
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
            'message_id' => $messageId,
            'text' => !empty($telegram->Text()) ? $telegram->Text() : null,
        ]);
    }

    public function getUserMessageIdsArray($telegram) {
        $user = $this->getUser($telegram);

        $messageIds = $user->messages->pluck('message_id')->toArray();

        return $messageIds;
    }

    // این متد میاد آخرین مسجی که مربوط به کامند استارت هست رو پیدا می کنه و از آرایه کلی حذف می کنه
    // کامند استارت مهمه چون صفحه کلید چسبان فقط با این کامند ایجاد میشه و نباید حذف بشه
    private function getLatestStartCommandMessageId($user, $messageIds) {
        // find /start commands and remove from messageIds, to preserve it, because if you remove start message, reply keyboard will be removed as well!
        $startId = $user->messages()->where('text', '/start')->get()->pluck('message_id')->last();
        $messageIds = array_filter($messageIds, function($value) use ($startId) {
            return $value !== $startId;
        });
        // Reindex the array from zero
        return $messageIds = array_values($messageIds);
    }

    public function deleteUserMessages($telegram) {
        $chat_id = $telegram->ChatID();
        $user = $this->getUser($telegram);
        $messageIds = $this->getUserMessageIdsArray($telegram);

        // Omit the last message_id from the array
        array_pop($messageIds);
        array_pop($messageIds);

        // کارفرما می خواست صفحه کلید دائمی به پایین همیشه چسبیده باشه و مانع اینجا این بود که تاریخچه آخرین دستور استارت نباید پاک میشد 
        // چون اگر آخرین کامند استارت پاک بشه همزمان با اون صفحه کلید چسبان پایین هم پاک میشه
        $messageIds = $this->getLatestStartCommandMessageId($user, $messageIds);
       
        $content = ['chat_id' => $chat_id, 'message_ids' => json_encode($messageIds)];

        $telegram->deleteMessages($content);

        // delete all user messages
        $user->messages()->whereIn('message_id', $messageIds)->delete();
    }
} 