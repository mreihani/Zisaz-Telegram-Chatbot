<?php

namespace App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\ExpansionJoint;

use PDF;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\ExpansionJoint\ExpansionJointResult;
use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\ExpansionJoint\ExpansionJointCalculation;

class ExpansionJointBotResponse extends ExpansionJointCalculation {

    public $telegram;
    public $latestAction;
    public $expansionJoint;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->expansionJoint = $this->latestAction->expansionJoint->first();
    }

    public function processParameterSubmission() {
        if(empty($this->expansionJoint->h)) {
            return $this->sendPamameterHText();
        } else {
            return $this->displayFinalResults();
        }
    }

    public function sendPamameterHText() {
        try {
            $text = 'ارتفاع ساختمان را بر حسب متر وارد نمایید';

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

        $expansionJointResult = new ExpansionJointResult($this->telegram);

        $results = $expansionJointResult->calculateExpansionJoint();

        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        $text .= '
درز انقطاع برای هر یک از مجاورین برابر '. $results['b'] .' سانتی متر است        
';

        $text .= '
برای دریافت فایل پی دی اف روی دکمه دانلود کلیک کنید 📥

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('⬇ دانلود پی دی اف محاسبات', '', '/expansionjointdownloadresults')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔁 پروژه جدید', '', '/expansionjointresetresults')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function downloadResults() {

        $telegram = $this->telegram;
        $chat_id = $telegram->ChatID();

        $expansionJointResult = new ExpansionJointResult($this->telegram);

        $data = $expansionJointResult->calculateExpansionJoint();

        // Step 1: Generate the PDF content
        $pdf = PDF::loadView('ramp-and-expansion-joint.expansion-joint.generatepdf-expansion-joint', $data);

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