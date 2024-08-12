<?php

namespace App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPartition;

use PDF;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPartition\BrickWallMasonryPartitionResult;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPartition\BrickWallMasonryPartitionCalculation;

class BrickWallMasonryPartitionBotResponse extends BrickWallMasonryPartitionCalculation {

    public $telegram;
    public $latestAction;
    public $brickWallMasonryPartition;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->brickWallMasonryPartition = $this->latestAction->brickWallMasonryPartition->first();
    }

    public function processParameterSubmission() {
        if(empty($this->brickWallMasonryPartition->a)) {
            return $this->sendPamameterAText();
        } elseif(empty($this->brickWallMasonryPartition->b)) {
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
                array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
            );

            $keyb = $this->telegram->buildInlineKeyBoard($option);

            $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
        } catch (\Exception $e) {
            // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }
    
    public function sendPamameterBText() {
        $text = 'عرض دیوار را انتخاب نمایید';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('8', '', '/brickwallmasonrypartitionsendpamameterb8')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('13', '', '/brickwallmasonrypartitionsendpamameterb13')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function displayFinalResults() {

        $brickWallMasonryPartitionResult = new BrickWallMasonryPartitionResult($this->telegram);

        $results = $brickWallMasonryPartitionResult->calculateBrickWallMasonryPartition();

        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        $text .= '
ضخامت دیوار '. $results['b'] .' سانتی متر
مساحت دیوار چینی '. $results['a'] .' متر مربع
تعداد آجر مورد نیاز '. $results['n'] .' عدد
وزن سیمان مورد نیاز '. $results['w'] .' کیلوگرم
وزن ماسه مورد نیاز '. $results['s'] .' کیلوگرم
';

        $text .= '
⚠ توجه
1- این محاسبات برای برآورد دیوار با آجر پارتیشن انجام شده است.
2- ابعاد آجر فشاری '. $results['r'] .'*'. $results['e'] .'*'. $results['f'] .' می باشد. 
3- عیار ملات بلوک چینی '. $results['c'] .' کیلو گرم بر مترمکعب در نظر گرفته شده است.
4- پرت مصالح 6% در نظر گرفته شده است.

برای دریافت فایل پی دی اف روی دکمه دانلود کلیک کنید 📥
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('⬇ دانلود پی دی اف محاسبات', '', '/brickwallmasonrypartitiondownloadresults')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔁 پروژه جدید', '', '/brickwallmasonrypartitionresetresults')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function downloadResults() {

        $telegram = $this->telegram;
        $chat_id = $telegram->ChatID();

        $brickWallMasonryPartitionResult = new BrickWallMasonryPartitionResult($this->telegram);

        $data = $brickWallMasonryPartitionResult->calculateBrickWallMasonryPartition();

        // Step 1: Generate the PDF content
        $pdf = PDF::loadView('brick-wall-masonry.brick-wall-masonry-partition.generatepdf-brick-wall-masonry-partition-brick', $data);

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