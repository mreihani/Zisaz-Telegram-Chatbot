<?php

namespace App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampSteep;

use PDF;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampSteep\RampSteepResult;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampSteep\RampSteepCalculation;

class RampSteepBotResponse extends RampSteepCalculation {

    public $telegram;
    public $latestAction;
    public $rampSteep;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->rampSteep = $this->latestAction->rampSteep->first();
    }

    public function processParameterSubmission() {
        if(empty($this->rampSteep->h)) {
            return $this->sendPamameterHText();
        } elseif(empty($this->rampSteep->l)) {
            return $this->sendPamameterLText();
        } else {
            return $this->displayFinalResults();
        }
    }

    public function sendPamameterHText() {
        try {
            $text = 'ارتفاع رمپ را بر حسب متر وارد نمایید';

            $option = array( 
                // First row
                array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
            );

            $keyb = $this->telegram->buildInlineKeyBoard($option);

            $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
        } catch (\Exception $e) {
            // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }
    
    public function sendPamameterLText() {
        try {
            $text = 'طول رمپ را بر حسب متر وارد نمایید';

            $option = array( 
                // First row
                array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
            );

            $keyb = $this->telegram->buildInlineKeyBoard($option);

            $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
        } catch (\Exception $e) {
            // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function displayFinalResults() {

        $rampSteepResult = new RampSteepResult($this->telegram);

        $results = $rampSteepResult->calculateRampSteep();

        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        $text .= '
ارتفاع رمپ '. $results['h'] .' متر
طول رمپ '. $results['l'] .' متر
شیب رمپ '. $results['s'] .' درصد
';

        $text .= '
برای دریافت فایل پی دی اف روی دکمه دانلود کلیک کنید 📥

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('⬇ دانلود پی دی اف محاسبات', '', '/rampsteepdownloadresults')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔁 پروژه جدید', '', '/rampsteepresetresults')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function downloadResults() {

        $telegram = $this->telegram;
        $chat_id = $telegram->ChatID();

        $rampSteepResult = new RampSteepResult($this->telegram);

        $data = $rampSteepResult->calculateRampSteep();

        // Step 1: Generate the PDF content
        $pdf = PDF::loadView('ramp-and-expansion-joint.ramp-steep.generatepdf-ramp-steep', $data);

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