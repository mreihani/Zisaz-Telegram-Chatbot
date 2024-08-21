<?php

namespace App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\BodyTile;

use PDF;
use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\BodyTile\BodyTileResult;
use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\BodyTile\BodyTileCalculation;

class BodyTileBotResponse extends BodyTileCalculation {

    public $telegram;
    public $latestAction;
    public $bodyTile;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->bodyTile = $this->latestAction->bodyTile->first();
    }

    public function processParameterSubmission() {
        if(empty($this->bodyTile->t)) {
            return $this->sendPamameterTText();
        } elseif(empty($this->bodyTile->a)) {
            return $this->sendPamameterAText();
        } else {
            return $this->displayFinalResults();
        }
    }

    public function sendPamameterTText() {
        try {
            $text = 'ضخامت متوسط دوغاب کاشی بدنه را بر حسب سانتی متر وارد نمایید';

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

    public function sendPamameterAText() {
        try {
            $text = 'متراژ کل را بر حسب متر مربع وارد نمایید';

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

        $bodyTileResult = new BodyTileResult($this->telegram);

        $results = $bodyTileResult->calculateBodyTile();

        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        $text .= '
متراژ کل کار برابر '. $results['a'] .' متر مربع
وزن سیمان مورد نیاز برابر '. $results['w1'] .' کیلوگرم
وزن ماسه مورد نیاز برابر '. $results['w2'] .' کیلوگرم
';

        $text .= '
توجه ⚠: 
1- این محاسبات بر اساس تجربه کارگاهی انجام شده است .
2- در ورود اطلاعات خواسته شده دقت کنید.
3- در صورتی که اطلاعات دقیق وارد نشود از اعداد پیش فرض  سیستم استفاده می شود .
4- پرت مصالح 5% در نظر گرفته شده است.
        ';

        $text .= '
برای دریافت فایل پی دی اف روی دکمه دانلود کلیک کنید 📥

⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('⬇ دانلود پی دی اف محاسبات', '', '/bodytiledownloadresults')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔁 پروژه جدید', '', '/bodytileresetresults')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function downloadResults() {

        $telegram = $this->telegram;
        $chat_id = $telegram->ChatID();

        $bodyTileResult = new BodyTileResult($this->telegram);

        $data = $bodyTileResult->calculateBodyTile();

        // Step 1: Generate the PDF content
        $pdf = PDF::loadView('facade-and-flooring-material-calculation.generatepdf-body-tile', $data);

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