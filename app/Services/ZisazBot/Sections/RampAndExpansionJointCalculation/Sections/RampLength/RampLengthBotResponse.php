<?php

namespace App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampLength;

use PDF;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampLength\RampLengthResult;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampLength\RampLengthCalculation;

class RampLengthBotResponse extends RampLengthCalculation {

    public $telegram;
    public $latestAction;
    public $rampLength;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->rampLength = $this->latestAction->rampLength->first();
    }

    public function processParameterSubmission() {
        if(empty($this->rampLength->h)) {
            return $this->sendPamameterHText();
        } elseif(empty($this->rampLength->s)) {
            return $this->sendPamameterSText();
        } else {
            return $this->displayFinalResults();
        }
    }

    public function sendPamameterHText() {
        try {
            $text = 'ارتفاع رمپ را بر حسب متر وارد نمایید';

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
    
    public function sendPamameterSText() {
        try {
            $text = 'شیب رمپ را به درصد وارد نمایید';

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

        $rampLengthResult = new RampLengthResult($this->telegram);

        $results = $rampLengthResult->calculateRampLength();

        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        $text .= '
ارتفاع رمپ '. $results['h'] .' متر
شیب رمپ '. $results['s'] .' درصد
طول رمپ '. $results['l'] .' متر
';

        $text .= '
برای دریافت فایل پی دی اف روی دکمه دانلود کلیک کنید 📥

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('⬇ دانلود پی دی اف محاسبات', '', '/ramplengthdownloadresults')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔁 پروژه جدید', '', '/ramplengthresetresults')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function downloadResults() {

        $telegram = $this->telegram;
        $chat_id = $telegram->ChatID();

        $rampLengthResult = new RampLengthResult($this->telegram);

        $data = $rampLengthResult->calculateRampLength();

        // Step 1: Generate the PDF content
        $pdf = PDF::loadView('ramp-and-expansion-joint.ramp-length.generatepdf-ramp-length', $data);

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