<?php

namespace App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryApartmentBlock;

use PDF;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryApartmentBlock\BrickWallMasonryApartmentBlockResult;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryApartmentBlock\BrickWallMasonryApartmentBlockCalculation;


class BrickWallMasonryApartmentBlockBotResponse extends BrickWallMasonryApartmentBlockCalculation {

    public $telegram;
    public $latestAction;
    public $brickWallMasonryApartmentBlock;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->brickWallMasonryApartmentBlock = $this->latestAction->brickWallMasonryApartmentBlock->first();
    }

    public function processParameterSubmission() {
        if(empty($this->brickWallMasonryApartmentBlock->a)) {
            return $this->sendPamameterAText();
        } elseif(empty($this->brickWallMasonryApartmentBlock->b)) {
            return $this->sendPamameterBText();
        } else {
            return $this->displayFinalResults();
        }
    }

    public function sendPamameterAText() {
        try {
            $text = 'مساحت کل دیوار به متر مربع را وارد نمایید';

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

    public function sendPamameterBText() {
        $text = 'عرض دیوار ( بلوک) انتخاب نمایید';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('7', '', '/brickwallmasonryapartmentblocksendpamameterb7')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('10', '', '/brickwallmasonryapartmentblocksendpamameterb10')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('15', '', '/brickwallmasonryapartmentblocksendpamameterb15')), 
            // Fourth row
            array($this->telegram->buildInlineKeyBoardButton('20', '', '/brickwallmasonryapartmentblocksendpamameterb20')), 
            // Fifth row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function displayFinalResults() {

        $brickWallMasonryApartmentBlockResult = new BrickWallMasonryApartmentBlockResult($this->telegram);

        $results = $brickWallMasonryApartmentBlockResult->calculateBrickWallMasonryApartmentBlock();

        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        $text .= '
ابعاد بلوک '. $results['b'] .'*20*40	سانتی متر 
متراژ کل دیوار چینی '. $results['a'] .' متر مربع
تعداد بلوک مورد نیاز '. $results['n'] .' عدد
مقدار سیمان مورد نیاز برابر '. $results['w'] .' کیلوگرم
مقدار ماسه مورد نیاز برابر '. $results['s'] .' کیلوگرم
        ';

        $text .= '
⚠ توجه
1- این محاسبات برای بلوک چینی پارتیشن آپارتمان انجام شده است.
2- ابعاد بلوک '. $results['b'] .'*20*40 می باشد. 
3- عیار ملات بلوک چینی 250 کیلو گرم بر مترمکعب در نظر گرفته شده است.
4- پرت مصالح 6% در نظر گرفته شده است.

برای دریافت فایل پی دی اف روی دکمه دانلود کلیک کنید 📥
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('⬇ دانلود پی دی اف محاسبات', '', '/brickwallmasonryapartmentblockdownloadresults')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔁 پروژه جدید', '', '/brickwallmasonryapartmentblockresetresults')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت به منوی اصلی', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function downloadResults() {

        $telegram = $this->telegram;
        $chat_id = $telegram->ChatID();

        $brickWallMasonryApartmentBlockResult = new BrickWallMasonryApartmentBlockResult($this->telegram);

        $data = $brickWallMasonryApartmentBlockResult->calculateBrickWallMasonryApartmentBlock();

        // Step 1: Generate the PDF content
        $pdf = PDF::loadView('brick-wall-masonry.brick-wall-masonry-apartment-block.generatepdf-brick-wall-masonry-apartment-block', $data);

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