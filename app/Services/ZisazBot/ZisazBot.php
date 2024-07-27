<?php

namespace App\Services\ZisazBot;




abstract class ZisazBot {

    abstract public function displayItem();

    public function sendMessageWithInlineKeyBoard($telegram, $keyb, $text) {
        $chat_id = $telegram->ChatID();
        \Log::info($chat_id);

        if($telegram->getUpdateType() === 'callback_query') {
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text, 'message_id' => $telegram->MessageID());
            $telegram->editMessageText($content);
        } else {
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text);
            $telegram->sendMessage($content);
        }
       
        // if($telegram->getUpdateType() === 'callback_query') {
        //     $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text, 'message_id' => $telegram->MessageID());
        //     $telegram->editMessageText($content);
        // } else {
        //     $result = $telegram->getData();
        //     $message_id = $result['message']['message_id'];

        //     $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text);
        //     $telegram->sendMessage($content);

        //     // Retrieve the existing cached message IDs
        //     $cachedMessages = Cache::get('message_ids', []);

        //     // Append the new message_id to the array
        //     $cachedMessages[] = $message_id;

        //     // Save the updated array back into the cache as a stringified JSON
        //     Cache::forever('message_ids', $cachedMessages);

        //     // Log the updated cached message IDs
        //     Log::info(json_encode($cachedMessages));
        // }
    }
} 