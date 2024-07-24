<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class TelegramBotService {
    protected $token;
    protected $apiEndpoint;
    protected $headers;

    public function __construct() {
        $this->token = env('TELEGRAM_BOT_TOKEN');
        $this->apiEndpoint = env('TELEGRAM_API_ENDPOINT');
        $this->setHeaders();
    }

    protected function setHeaders() {
        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
    }

    public function sendMessage($text = '', $chat_id, $reply_to_message_id) {
        $result = [
            'success' => false,
            'body' => []
        ];
       
        $params = [
            'chat_id' => $chat_id,
            'reply_to_message_id' => $reply_to_message_id,
            'text' => $text
        ];
        
        $url = "{$this->apiEndpoint}/{$this->token}/sendMessage";

        try {
            $response = Http::withHeaders($this->headers)->post($url, $params);
            $result = [
                'success' => $response->ok(),
                'body' => $response->json()
            ];
        } catch(\Throwable $th) {
            $result['error'] = $th->getMessage();
        }

        return $result;
    }
}