<?php

namespace App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\ColumnConcreting;

use PDF;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\ColumnConcreting\ColumnConcretingResult;
use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\ColumnConcreting\ColumnConcretingCalculation;

class ColumnConcretingBotResponse extends ColumnConcretingCalculation {

    public $telegram;
    public $latestAction;
    public $columnConcreting;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->columnConcreting = $this->latestAction->columnConcreting->first();
    }

    public function processParameterSubmission() {
        if(empty($this->columnConcreting->v)) {
            return $this->sendPamameterVText();
        } elseif(empty($this->columnConcreting->l)) {
            return $this->sendPamameterLText();
        } elseif(empty($this->columnConcreting->b)) {
            return $this->sendPamameterBText();
        } elseif(empty($this->columnConcreting->h)) {
            return $this->sendPamameterHText();
        } elseif(empty($this->columnConcreting->c)) {
            return $this->sendPamameterCText();
        } else {
            return $this->displayFinalResults();
        }
    }

    public function sendPamameterVText() {
        try {
            $text = 'تعداد ستون را وارد نمایید';

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

    public function sendPamameterLText() {
        try {
            $text = 'طول ستون را بر حسب سانتی متر وارد نمایید';

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

    public function sendPamameterBText() {
        try {
            $text = 'عرض ستون را بر حسب سانتی متر وارد نمایید';

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

    public function sendPamameterHText() {
        try {
            $text = 'ارتفاع ستون را بر حسب سانتی متر وارد نمایید';

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
            $text = 'عیار سیمان را بر حسب کیلوگرم وارد نمایید';

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

        $columnConcretingResult = new ColumnConcretingResult($this->telegram);

        $results = $columnConcretingResult->calculateColumnConcreting();

        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        $text .= '
حجم کل بتن ریزی برابر '. $results['v'] .' متر مکعب
وزن سیمان مصرفی برابر '. $results['w1'] .' کیلوگرم
وزن ماسه شسته برابر '. $results['w2'] .' کیلوگرم
وزن شن نخودی و بادامی برابر '. $results['w3'] .' کیلوگرم
حجم آب برابر '. $results['v1'] .' لیتر
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
            array($this->telegram->buildInlineKeyBoardButton('⬇ دانلود پی دی اف محاسبات', '', '/columnconcretingdownloadresults')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔁 پروژه جدید', '', '/columnconcretingresetresults')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function downloadResults() {

        $telegram = $this->telegram;
        $chat_id = $telegram->ChatID();

        $columnConcretingResult = new ColumnConcretingResult($this->telegram);

        $data = $columnConcretingResult->calculateColumnConcreting();

        // Step 1: Generate the PDF content
        $pdf = PDF::loadView('concreting-matrials-calculation.generatepdf-column-concreting', $data);

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