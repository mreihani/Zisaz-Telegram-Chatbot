<?php

namespace App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\Concreting;

use PDF;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\Concreting\ConcretingResult;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\Concreting\ConcretingCalculation;

class ConcretingBotResponse extends ConcretingCalculation {

    public $telegram;
    public $latestAction;
    public $concreting;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->concreting = $this->latestAction->concreting->first();
    }

    public function processParameterSubmission() {
        if(empty($this->concreting->v)) {
            return $this->sendPamameterVText();
        } elseif(empty($this->concreting->c)) {
            return $this->sendPamameterCText();
        } else {
            return $this->displayFinalResults();
        }
    }

    public function sendPamameterVText() {
        try {
            $text = 'حجم بتن را بر حسب متر مکعب وارد نمایید';

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

    public function sendPamameterCText() {
        try {
            $text = 'عیار سیمان را وارد نمایید';

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

        $concretingResult = new ConcretingResult($this->telegram);

        $results = $concretingResult->calculateConcreting();

        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        $text .= '
وزن سیمان مصرفی '. $results['w1'] .' کیلوگرم
وزن ماسه شسته '. $results['w2'] .' کیلوگرم
وزن شن نخودی و بادامی '. $results['w3'] .' کیلوگرم
حجم آب '. $results['v'] .' لیتر
';

        $text .= '

توجه⚠ : محاسبات فوق بر اساس شرایط معمول کارگاهی بود و برای محاسبات دقیق بایستی به طرح اختلاط بتون بر اساس مصالح موجود در محل مراجعه کرد.

        ';

        $text .= '
برای دریافت فایل پی دی اف روی دکمه دانلود کلیک کنید 📥

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('⬇ دانلود پی دی اف محاسبات', '', '/concretingdownloadresults')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔁 پروژه جدید', '', '/concretingresetresults')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function downloadResults() {

        $telegram = $this->telegram;
        $chat_id = $telegram->ChatID();

        $concretingResult = new ConcretingResult($this->telegram);

        $data = $concretingResult->calculateConcreting();

        // Step 1: Generate the PDF content
        $pdf = PDF::loadView('concreting-matrials-calculation.generatepdf-concreting', $data);

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