<?php

namespace App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarWeight;

use PDF;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarWeight\RebarWeightResult;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarWeight\RebarWeightCalculation;

class RebarWeightBotResponse extends RebarWeightCalculation {

    public $telegram;
    public $latestAction;
    public $rebarWeight;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->rebarWeight = $this->latestAction->rebarWeight->first();
    }

    public function processParameterSubmission() {
        if(empty($this->rebarWeight->d)) {
            return $this->sendPamameterDText();
        } else {
            return $this->displayFinalResults();
        }
    }

    public function sendPamameterDText() {
        try {
            $text = 'قطر میلگرد را بر حسب میلیمتر وارد نمایید';

            $option = array( 
                // First row
                array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
            );

            $keyb = $this->telegram->buildInlineKeyBoard($option);

            $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
        } catch (\Exception $e) {
            // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function displayFinalResults() {

        $rebarWeightResult = new RebarWeightResult($this->telegram);

        $results = $rebarWeightResult->calculateRebarWeight();

        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        $text .= '
وزن یک متر میلگرد '. $results['d'] .' برابر '. $results['w'] .' کیلوگرم
وزن یک شاخه 12 متری میلگرد '. $results['d'] .' برابر '. $results['w1'] .' کیلوگرم
';

        $text .= '
برای دریافت فایل پی دی اف روی دکمه دانلود کلیک کنید 📥

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('⬇ دانلود پی دی اف محاسبات', '', '/rebarweightdownloadresults')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔁 پروژه جدید', '', '/rebarweightresetresults')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function downloadResults() {

        $telegram = $this->telegram;
        $chat_id = $telegram->ChatID();

        $rebarWeightResult = new RebarWeightResult($this->telegram);

        $data = $rebarWeightResult->calculateRebarWeight();

        // Step 1: Generate the PDF content
        $pdf = PDF::loadView('reabar-and-strirrup.rebar-weight.generatepdf-rebar-weight', $data);

        // Step 2: Save the generated PDF to a temporary location
        $uniqueFileName = hexdec(uniqid());
        $filename = $uniqueFileName . '.' . 'pdf';
        $pdfPath = storage_path('app/public/' . $filename);
        $pdf->save($pdfPath);

        // Step 3: Use curl_file_create() to create a CURLFile object
        $file = curl_file_create($pdfPath, 'application/pdf', 'calculations.pdf');

        // Step 4: Send the file using Telegram bot
        $content = array('chat_id' => $chat_id, 'document' => $file);
        $result = $telegram->sendDocument($content);

        // Step 5: Remove the temporary file
        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }
       
        $this->saveMessageId($telegram, $result);
    }
}