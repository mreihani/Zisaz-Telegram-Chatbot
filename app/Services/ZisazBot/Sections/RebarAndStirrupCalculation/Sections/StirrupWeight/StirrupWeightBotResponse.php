<?php

namespace App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\StirrupWeight;

use PDF;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\StirrupWeight\StirrupWeightResult;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\StirrupWeight\StirrupWeightCalculation;


class StirrupWeightBotResponse extends StirrupWeightCalculation {

    public $telegram;
    public $latestAction;
    public $stirrupWeight;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->stirrupWeight = $this->latestAction->stirrupWeight->first();
    }

    public function processParameterSubmission() {
        if(empty($this->stirrupWeight->d)) {
            return $this->sendPamameterDText();
        } elseif(empty($this->stirrupWeight->l)) {
            return $this->sendPamameterLText();
        } elseif(empty($this->stirrupWeight->b)) {
            return $this->sendPamameterBText();
        } elseif(empty($this->stirrupWeight->n)) {
            return $this->sendPamameterNText();
        } else {
            return $this->displayFinalResults();
        }
    }

    public function sendPamameterDText() {
        try {
            $text = 'قطر میلگرد خاموت را بر حسب اینچ وارد نمایید';

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
            $text = 'طول خاموت را بر حسب متر وارد نمایید';

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
            $text = 'عرض خاموت را بر حسب متر وارد نمایید';

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

    public function sendPamameterNText() {
        try {
            $text = 'تعداد کل خاموت را وارد نمایید';

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

        $stirrupWeightResult = new StirrupWeightResult($this->telegram);

        $results = $stirrupWeightResult->calculateStirrupWeight();

        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        $text .= '
وزن یک متر میلگرد '. $results['d'] .' برابر '. $results['w'] .' کیلوگرم
وزن یک عدد خاموت به ابعاد '. $results['l'] .' * '. $results['b'] .' برابر '. $results['w1'] .' کیلوگرم
وزن '. $results['n'] .' عدد خاموت به ابعاد '. $results['l'] .' * '. $results['b'] .' برابر '. $results['w2'] .' کیلوگرم
';

        $text .= '
برای دریافت فایل پی دی اف روی دکمه دانلود کلیک کنید 📥

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('⬇ دانلود پی دی اف محاسبات', '', '/stirrupweightdownloadresults')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔁 پروژه جدید', '', '/stirrupweightresetresults')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function downloadResults() {

        $telegram = $this->telegram;
        $chat_id = $telegram->ChatID();

        $stirrupWeightResult = new StirrupWeightResult($this->telegram);

        $data = $stirrupWeightResult->calculateStirrupWeight();

        // Step 1: Generate the PDF content
        $pdf = PDF::loadView('reabar-and-strirrup.stirrup-weight.generatepdf-stirrup-weight', $data);

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