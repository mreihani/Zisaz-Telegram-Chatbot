<?php

namespace App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\BeamAndBlockRoof\BeamAndBlockRoof;

class BeamAndBlockRoofCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات مربوط به سقف تیرچه و بلوک انجام می شود. 
            
اطلاعات مورد نیاز:
1- مساحت کل سقف
2- ارتفاع تیرچه
3- عیار بتن       
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/beamandblockroofsendpamameteratext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/getmenu')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new BeamAndBlockRoof());
    }

    public function getUserPrompts() {

        $text = $this->telegram->Text();

        $latestAction = $this->getLastActionObject($this->telegram);

        if(is_null($latestAction)) {
            return;
        }

        $beamAndBlockRoof = $latestAction->beamAndBlockRoof->first();

        if(empty($beamAndBlockRoof->a) && empty($beamAndBlockRoof->h) && empty($beamAndBlockRoof->c)) {
            $beamAndBlockRoof = $latestAction->beamAndBlockRoof()->create([
                'a' => !empty($text) ? $text : null,
            ]);

            $latestAction->update([
                'subaction_id' => $beamAndBlockRoof->id,
            ]);

            $this->sendPamameterHText();

        } elseif(empty($beamAndBlockRoof->h) && empty($beamAndBlockRoof->c)) {

            if($text == '/beamandblockroofsendpamameterh20' || $text == 20) {
                $text = 20;
            } elseif($text == '/beamandblockroofsendpamameterh25' || $text == 25) {
                $text = 25;
            } else {
                $text = 20;
            }

            $latestAction->beamAndBlockRoof()->update([
                'h' => !empty($text) ? $text : null,
            ]);

            $this->sendPamameterCText();

        } elseif(empty($beamAndBlockRoof->c)) {
            $latestAction->beamAndBlockRoof()->update([
                'c' => !empty($text) ? $text : null,
            ]);

            $this->displayFinalResults();
        } else {
            // $this->displayFinalResults();
        }
    }

    public function sendPamameterAText() {

        // first check if user has already submitted all the requirements or not, if not, it will ask for the first parameter
        $latestAction = $this->getLastActionObject($this->telegram);
        if(is_null($latestAction)) {
            return;
        }
        $beamAndBlockRoof = $latestAction->beamAndBlockRoof->first();
        if(!empty($beamAndBlockRoof->a) && !empty($beamAndBlockRoof->h) && !empty($beamAndBlockRoof->c)) {
            $this->displayFinalResults();
            return; 
        }

        // second, after checked for not having any previous submission, it asks for the first item to enter
        $text = 'مساحت کل سقف را وارد کنید';
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/getmenu')), 
        );
        $keyb = $this->telegram->buildInlineKeyBoard($option);
        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function sendPamameterHText() {
        $text = 'ارتفاع تیرچه را به سانتی متر انتخاب نمایید';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('20', '', '/beamandblockroofsendpamameterh20')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('25', '', '/beamandblockroofsendpamameterh25')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/getmenu')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function sendPamameterCText() {
        $text = 'عیار بتون را وارد کنید';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/getmenu')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function displayFinalResults() {
        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        $text .= '
مساحت کل سقف 	A	متر مربع 
ارتفاع تیرچه 	H	سانتی متر
تعداد فوم مورد نیاز 	N	عدد
متراژ تیرچه مورد نیاز تقریبی	L	متر
حجم بتون تقریبی	V	متر مکعب
وزن  سیمان  تقریبی مورد نیا ز	W	کیلو گرم 
وزن شن و ماسه  تقریبی مورد نیاز 	S	کیلو گرم 
وزن میلگرد حراراتی تقریبی مورد نیاز 	W1	کیلو گرم 
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
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/getmenu')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    // private function getLastActionObject() {
    //     $latestAction = $this->user->actions()->orderBy('updated_at', 'desc')->first();
    
    //     if(empty($latestAction)) {
    //         return null;
    //     }
    
    //     return $latestAction;
    // }

} 

