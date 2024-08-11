<?php

namespace App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation;

use PDF;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofResult;
use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofCalculation;

class BeamAndBlockRoofBotResponse extends BeamAndBlockRoofCalculation {

    public $telegram;
    public $latestAction;
    public $beamAndBlockRoof;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->beamAndBlockRoof = $this->latestAction->beamAndBlockRoof->first();
    }

    public function processParameterSubmission() {
        if(empty($this->beamAndBlockRoof->a) && empty($this->beamAndBlockRoof->h) && empty($this->beamAndBlockRoof->c)) {
            return $this->sendPamameterAText();
        } elseif(empty($this->beamAndBlockRoof->h) && empty($this->beamAndBlockRoof->c)) {
            return $this->sendPamameterHText();
        } elseif(empty($this->beamAndBlockRoof->c)) {
            return $this->sendPamameterCText();
        } else {
            return $this->displayFinalResults();
        }
    }

    public function sendPamameterAText() {
        try {
            $text = 'مساحت کل سقف را وارد کنید';
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
        $text = 'ارتفاع تیرچه را به سانتی متر انتخاب نمایید';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('20', '', '/beamandblockroofsendpamameterh20')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('25', '', '/beamandblockroofsendpamameterh25')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function sendPamameterCText() {
        $text = 'عیار بتون را وارد کنید';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function displayFinalResults() {

        $beamAndBlockRoofResult = new BeamAndBlockRoofResult($this->telegram);

        $h = $this->beamAndBlockRoof->h;

        if($h == 25) {
            $results = $beamAndBlockRoofResult->calculateH25();
        } elseif($h == 20) {
            $results = $beamAndBlockRoofResult->calculateH20();
        } 

        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        $text .= '
مساحت کل سقف ' . $results['a'] . '	متر مربع 
ارتفاع تیرچه ' . $results['h'] . ' سانتی متر
تعداد فوم مورد نیاز 	' . $results['n'] . '	عدد
متراژ تیرچه مورد نیاز تقریبی	' . $results['l'] . '	متر
حجم بتون تقریبی	' . $results['v'] . '	متر مکعب
وزن  سیمان  تقریبی مورد نیا ز	' . $results['w'] . '	کیلو گرم 
وزن شن و ماسه  تقریبی مورد نیاز 	' . $results['s'] . '	کیلو گرم 
وزن میلگرد حراراتی تقریبی مورد نیاز 	' . $results['wi'] . '	کیلو گرم 
        ';

        $text .= '
⚠ توجه
1-اندازه و مقادیر دقیق پارامتر های خروجی تابع ابعاد شناژ ها، پوتر های بتونی ، همچنین اندازه  دهانه تیرچه ها می باشد 
2-ارتفاع تیرچه  H سانتی متر 
3-ابعاد فوم 200*50 سانتی متر در نظر گرفته شده است .
4- عیار بتون 350 کیلو گرم بر مترمکعب د رنظر گرفته شده است .

برای دریافت فایل پی دی اف روی دکمه دانلود کلیک کنید 📥
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('⬇ دانلود پی دی اف محاسبات', '', '/beamandblockroofdownloadresults')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔁 پروژه جدید', '', '/beamandblockroofresetresults')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function downloadResults() {

        $telegram = $this->telegram;
        $chat_id = $telegram->ChatID();

        $beamAndBlockRoofResult = new BeamAndBlockRoofResult($this->telegram);

        $h = $this->beamAndBlockRoof->h;

        if($h == 25) {
            $data = $beamAndBlockRoofResult->calculateH25();
        } elseif($h == 20) {
            $data = $beamAndBlockRoofResult->calculateH20();
        } 

        // Step 1: Generate the PDF content
        $pdf = PDF::loadView('beam-and-block-roof.generatepdf-beam-and-block-roof', $data);

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