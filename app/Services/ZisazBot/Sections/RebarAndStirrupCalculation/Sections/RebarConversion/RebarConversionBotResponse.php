<?php

namespace App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarConversion;

use PDF;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarConversion\RebarConversionResult;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarConversion\RebarConversionCalculation;

class RebarConversionBotResponse extends RebarConversionCalculation {

    public $telegram;
    public $latestAction;
    public $rebarConversion;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->rebarConversion = $this->latestAction->rebarConversion->first();
    }

    public function processParameterSubmission() {
        if(empty($this->rebarConversion->d1)) {
            return $this->sendPamameterD1Text();
        } elseif(empty($this->rebarConversion->n)) {
            return $this->sendPamameterNText();
        } elseif(empty($this->rebarConversion->d2)) {
            return $this->sendPamameterD2Text();
        } else {
            return $this->displayFinalResults();
        }
    }

    public function sendPamameterD1Text() {
        try {
            $text = 'قطر میلگرد نقشه را بر حسب میلی متر وارد نمایید';

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

    public function sendPamameterNText() {
        try {
            $text = 'تعداد میلگرد نقشه را وارد نمایید';

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

    public function sendPamameterD2Text() {
        try {
            $text = 'قطر میلگرد موجود (جدید) را بر حسب سانتی متر وارد نمایید';

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

        $rebarConversionResult = new RebarConversionResult($this->telegram);

        $results = $rebarConversionResult->calculateRebarConversion();

        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        $text .= '
سطح مقطع کل '. $results['n'] .' عدد میلگرد '. $results['d1'] .' برابر '. $results['a1'] .' سانتی متر
سطح مقطع یک عدد میلگرد جدید با قطر '. $results['d2'] .' برابر '. $results['a2'] .' سانتی متر مربع
تعداد میلگرد معادل سازی شده برابر '. $results['n1'] .' عدد
';

        $text .= '
برای دریافت فایل پی دی اف روی دکمه دانلود کلیک کنید 📥

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('⬇ دانلود پی دی اف محاسبات', '', '/rebarconversiondownloadresults')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔁 پروژه جدید', '', '/rebarconversionresetresults')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function downloadResults() {

        $telegram = $this->telegram;
        $chat_id = $telegram->ChatID();

        $rebarConversionResult = new RebarConversionResult($this->telegram);

        $data = $rebarConversionResult->calculateRebarConversion();

        // Step 1: Generate the PDF content
        $pdf = PDF::loadView('reabar-and-strirrup.rebar-conversion.generatepdf-rebar-conversion', $data);

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