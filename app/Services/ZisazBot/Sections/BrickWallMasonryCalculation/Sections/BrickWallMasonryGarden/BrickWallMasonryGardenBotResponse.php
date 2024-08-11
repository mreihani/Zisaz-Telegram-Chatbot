<?php

namespace App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryGarden;

use PDF;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryGarden\BrickWallMasonryGardenResult;
use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryGarden\BrickWallMasonryGardenCalculation;

class BrickWallMasonryGardenBotResponse extends BrickWallMasonryGardenCalculation {

    public $telegram;
    public $latestAction;
    public $brickWallMasonryGarden;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->brickWallMasonryGarden = $this->latestAction->brickWallMasonryGarden->first();
    }

    public function processParameterSubmission() {
        if(empty($this->brickWallMasonryGarden->l)) {
            return $this->sendPamameterLText();
        } elseif(empty($this->brickWallMasonryGarden->h)) {
            return $this->sendPamameterHText();
        } elseif(empty($this->brickWallMasonryGarden->b)) {
            return $this->sendPamameterBText();
        } elseif(empty($this->brickWallMasonryGarden->type)) {
            return $this->sendPamameterTypeText();
        } elseif(empty($this->brickWallMasonryGarden->d)) {
            return $this->sendPamameterDText();
        } else {
            return $this->displayFinalResults();
        }
    }

    public function sendPamameterLText() {
        try {
            $text = 'طول دیوار را برحسب متر وارد نمایید';

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
            $text = 'ارتفاع دیوار را بر حسب متر وارد نمایید';

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

    public function sendPamameterTypeText() {
        try {
            $text = 'لطفا یکی از حالت های زیر را انتخاب نمایید:';
        
            $option = array( 
                // First row
                array($this->telegram->buildInlineKeyBoardButton('محاسبات دیوار چینی با جرز های بلوکی بدون شناژ با فاصله 3.60 متر', '', '/brickwallmasonrygardensendpamametertypea')), 
                // Second row
                array($this->telegram->buildInlineKeyBoardButton('محاسبات دیوار چینی  با شناژ افقی و عمودی بتونی', '', '/brickwallmasonrygardensendpamametertypeb')), 
                // Third row
                array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
            );

            $keyb = $this->telegram->buildInlineKeyBoard($option);

            $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
        } catch (\Exception $e) {
            // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function sendPamameterDText() {
        try {
            $text = 'عمق پی کنی (شالوده) را به متر وارد نمایید';

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

        $brickWallMasonryGardenResultResult = new BrickWallMasonryGardenResult($this->telegram);

        $results = $brickWallMasonryGardenResultResult->calculateBrickWallMasonryGarden();
     
        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        if($results['type'] == 'a') {
            $text .= '
دیوار چینی با جرز های بلوکی 40 سانتی متری بدون شناژ با فاصله 3.6 متر اجرا می شود
ابعاد بلوک 20*20*40 سانتی متر است
طول پی کنی برابر '. $results['l'] .' است
پی کنی (شالوده) به عمق '. $results['d'] .' متر اجرا می شود
تعداد بلوک تقریبی مورد نیاز '. $results['n'] .' عدد است
مقدار سیمان مورد نیاز برابر '. $results['w'] .' کبلوگرم است
مقدار ماسه مورد نیاز برابر '. $results['s'] .' کیلوگرم است
        ';

        $text .= '
⚠ توجه
1- این محاسبات برای دیوار چینی  با جرز های بلوکی بدون شناژ با فاصله 3.60 متر  انجام شده است.
2- ابعاد بلوک 20*20*40 سانتی متر در نظر گرفته شده است. 
3- برای جلوگیری از نشست دیوار، از کف شالوده تا همسطح  زمین بلوک 40 سانتی چیده می شود.
4- عیار ملات بلوک چینی 250 کیلوگرم بر مترمکعب در نظر گرفته شده است.
5- پرت مصالح 6% در نظر گرفته شده است.

برای دریافت فایل پی دی اف روی دکمه دانلود کلیک کنید 📥
⤵
';
        } else {
            $text .= '
دیوار چینی با جرز های بلوکی 40 سانتی متری بدون شناژ با فاصله 3.6 متر اجرا می شود
ابعاد بلوک 20*20*40 سانتی متر است
طول پی کنی برابر '. $results['l'] .' متر است
پی کنی (شالوده) به عمق '. $results['d'] .' متر اجرا می شود
تعداد بلوک تقریبی مورد نیاز '. $results['n'] .' عدد است
مقدار سیمان مورد نیاز برابر '. $results['w'] .' کبلوگرم است
مقدار ماسه مورد نیاز برابر '. $results['s'] .' کیلوگرم است
وزن خاموت 8 برابر '. $results['w2'] .' است.
وزن خاموت 14 برابر '. $results['w1'] .' است.
';
            
                    $text .= '
⚠ توجه
1- این محاسبات برای دیوار چینی با شناژ افقی و شناژ عمودی به فاصله 3.60 متر  انجام شده است.
2- ابعاد بلوک 20*20*40 سانتی متر در نظر گرفته شده است.
3- قطر میلگرد شناژ 14 میلیمتر در نظر گرفته شده است.
4- قطر میلگرد خاموت 8 میلیمتر در نظر گرفته شده است.
5- عیار ملات بلوک چینی  250 کیلو گرم بر مترمکعب در نظر گرفته شده است.
6- پرت مصالح 6% در نظر گرفته شده است.

برای دریافت فایل پی دی اف روی دکمه دانلود کلیک کنید 📥
⤵
';
        }
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('⬇ دانلود پی دی اف محاسبات', '', '/brickwallmasonrygardendownloadresults')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔁 پروژه جدید', '', '/brickwallmasonrygardenresetresults')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function downloadResults() {

        $telegram = $this->telegram;
        $chat_id = $telegram->ChatID();

        $brickWallMasonryGardenResultResult = new BrickWallMasonryGardenResult($this->telegram);

        $data = $brickWallMasonryGardenResultResult->calculateBrickWallMasonryGarden();

        // Step 1: Generate the PDF content
        if($data['type'] == 'a') {
            $pdf = PDF::loadView('brick-wall-masonry.brick-wall-masonry-garden.generatepdf-brick-wall-masonry-garden-type-a', $data);
        } else {
            $pdf = PDF::loadView('brick-wall-masonry.brick-wall-masonry-garden.generatepdf-brick-wall-masonry-garden-type-b', $data);
        }

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