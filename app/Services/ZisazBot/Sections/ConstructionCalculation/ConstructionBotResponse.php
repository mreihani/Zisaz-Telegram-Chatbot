<?php

namespace App\Services\ZisazBot\Sections\ConstructionCalculation;

use PDF;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionCalculation;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionCalculationResult;

class ConstructionBotResponse extends ConstructionCalculation {

    public $telegram;
    public $latestAction;
    public $construction;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->latestAction = $this->getLastActionObject($telegram);
        $this->construction = $this->latestAction->construction->first();
    }

    public function processParameterSubmission() {

        $construction = $this->construction;

        // نام شهر
        if(empty($construction) || is_null($construction->c)) {
            return $this->sendPamameterCText();
        // موقعیت قرارگیری ملک
        } elseif(is_null($construction->m)) {
            return $this->sendPamameterMText();
        // مساحت زمین    
        } elseif(is_null($construction->a)) {
            return $this->sendPamameterAText();
        // عرض متوسط ملک    
        } elseif(is_null($construction->b)) {
            return $this->sendPamameterBText();
        // تعداد طبقات زیر زمین    
        } elseif(is_null($construction->nb)) {
            return $this->sendPamameterNBText();
        // تعداد طبقات بالای همکف    
        } elseif(is_null($construction->nf)) {
            return $this->sendPamameterNFText();
        // درصد سطح اشغال زیر زمین اول در صورت وجود یک زیر زمین    
        } elseif($construction->nb == 1 && (empty($construction->constructionBasements) || is_null($construction->constructionBasements->b1))) {
            return $this->sendPamameterBasement1Text();
        // درصد سطح اشغال زیر زمین در صورت وجود دو زیر زمین    
        } elseif($construction->nb == 2 && (empty($construction->constructionBasements) || is_null($construction->constructionBasements->b1))) {
            return $this->sendPamameterBasement1Text();
        // درصد سطح اشغال زیر زمین دوم در صورت وجود دو زیر زمین    
        } elseif($construction->nb == 2 && (empty($construction->constructionBasements) || is_null($construction->constructionBasements->b2))) {
            return $this->sendPamameterBasement2Text();
        // درصد  سطح اشغال طبقه همکف
        } elseif(empty($construction->constructionFloors) || is_null($construction->constructionFloors->g)) {
            return $this->sendPamameterGText();
        // درصد سطح اشغال طبقه اول در صورت وجود یک طبقه بالای همکف
        } elseif($construction->nf == 1 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {
            return $this->sendPamameterF1Text();
        // درصد سطح اشغال طبقه اول در صورت وجود دو طبقه بالای همکف
        } elseif($construction->nf == 2 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {
            return $this->sendPamameterF1Text();
        // درصد سطح اشغال طبقه دوم در صورت وجود دو طبقه بالای همکف
        } elseif($construction->nf == 2 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f2))) {
            return $this->sendPamameterF2Text();
        // درصد سطح اشغال طبقه اول در صورت وجود سه طبقه بالای همکف
        } elseif($construction->nf == 3 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {
            return $this->sendPamameterF1Text();
        // درصد سطح اشغال طبقه دوم در صورت وجود سه طبقه بالای همکف
        } elseif($construction->nf == 3 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f2))) {
            return $this->sendPamameterF2Text();
        // درصد سطح اشغال طبقه سوم در صورت وجود سه طبقه بالای همکف
        } elseif($construction->nf == 3 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f3))) {
            return $this->sendPamameterF3Text();
        // درصد سطح اشغال طبقه اول در صورت وجود چهار طبقه بالای همکف
        } elseif($construction->nf == 4 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {
            return $this->sendPamameterF1Text();
        // درصد سطح اشغال طبقه دوم در صورت وجود چهار طبقه بالای همکف
        } elseif($construction->nf == 4 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f2))) {
            return $this->sendPamameterF2Text();
        // درصد سطح اشغال طبقه سوم در صورت وجود چهار طبقه بالای همکف
        } elseif($construction->nf == 4 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f3))) {
            return $this->sendPamameterF3Text();
        // درصد سطح اشغال طبقه چهارم در صورت وجود چهار طبقه بالای همکف
        } elseif($construction->nf == 4 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f4))) {
            return $this->sendPamameterF4Text();
        // درصد سطح اشغال طبقه اول در صورت وجود پنج طبقه بالای همکف
        } elseif($construction->nf == 5 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {
            return $this->sendPamameterF1Text();
        // درصد سطح اشغال طبقه دوم در صورت وجود پنج طبقه بالای همکف
        } elseif($construction->nf == 5 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f2))) {
            return $this->sendPamameterF2Text();
        // درصد سطح اشغال طبقه سوم در صورت وجود پنج طبقه بالای همکف
        } elseif($construction->nf == 5 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f3))) {
            return $this->sendPamameterF3Text();
        // درصد سطح اشغال طبقه چهارم در صورت وجود پنج طبقه بالای همکف
        } elseif($construction->nf == 5 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f4))) {
            return $this->sendPamameterF4Text();
        // درصد سطح اشغال طبقه پنجم در صورت وجود پنج طبقه بالای همکف
        } elseif($construction->nf == 5 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f5))) {
            return $this->sendPamameterF5Text();
        // درصد سطح اشغال طبقه اول در صورت وجود شش طبقه بالای همکف
        } elseif($construction->nf == 6 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {
            return $this->sendPamameterF1Text();
        // درصد سطح اشغال طبقه دوم در صورت وجود شش طبقه بالای همکف
        } elseif($construction->nf == 6 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f2))) {
            return $this->sendPamameterF2Text();
        // درصد سطح اشغال طبقه سوم در صورت وجود شش طبقه بالای همکف
        } elseif($construction->nf == 6 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f3))) {
            return $this->sendPamameterF3Text();
        // درصد سطح اشغال طبقه چهارم در صورت وجود شش طبقه بالای همکف
        } elseif($construction->nf == 6 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f4))) {
            return $this->sendPamameterF4Text();
        // درصد سطح اشغال طبقه پنجم در صورت وجود شش طبقه بالای همکف
        } elseif($construction->nf == 6 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f5))) {
            return $this->sendPamameterF5Text();
        // درصد سطح اشغال طبقه ششم در صورت وجود شش طبقه بالای همکف
        } elseif($construction->nf == 6 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f6))) {
            return $this->sendPamameterF6Text();
        // درصد سطح اشغال طبقه اول در صورت وجود هفت طبقه بالای همکف
        } elseif($construction->nf == 7 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {
            return $this->sendPamameterF1Text();
        // درصد سطح اشغال طبقه دوم در صورت وجود هفت طبقه بالای همکف
        } elseif($construction->nf == 7 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f2))) {
            return $this->sendPamameterF2Text();
        // درصد سطح اشغال طبقه سوم در صورت وجود هفت طبقه بالای همکف
        } elseif($construction->nf == 7 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f3))) {
            return $this->sendPamameterF3Text();
        // درصد سطح اشغال طبقه چهارم در صورت وجود هفت طبقه بالای همکف
        } elseif($construction->nf == 7 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f4))) {
            return $this->sendPamameterF4Text();
        // درصد سطح اشغال طبقه پنجم در صورت وجود هفت طبقه بالای همکف
        } elseif($construction->nf == 7 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f5))) {
            return $this->sendPamameterF5Text();
        // درصد سطح اشغال طبقه ششم در صورت وجود هفت طبقه بالای همکف
        } elseif($construction->nf == 7 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f6))) {
            return $this->sendPamameterF6Text();
        // درصد سطح اشغال طبقه هفتم در صورت وجود هفت طبقه بالای همکف
        } elseif($construction->nf == 7 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f7))) {
            return $this->sendPamameterF7Text();
        // درصد سطح اشغال طبقه اول در صورت وجود هشت طبقه بالای همکف
        } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {
            return $this->sendPamameterF1Text();
        // درصد سطح اشغال طبقه دوم در صورت وجود هشت طبقه بالای همکف
        } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f2))) {
            return $this->sendPamameterF2Text();
        // درصد سطح اشغال طبقه سوم در صورت وجود هشت طبقه بالای همکف
        } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f3))) {
            return $this->sendPamameterF3Text();
        // درصد سطح اشغال طبقه چهارم در صورت وجود هشت طبقه بالای همکف
        } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f4))) {
            return $this->sendPamameterF4Text();
        // درصد سطح اشغال طبقه پنجم در صورت وجود هشت طبقه بالای همکف
        } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f5))) {
            return $this->sendPamameterF5Text();
        // درصد سطح اشغال طبقه ششم در صورت وجود هشت طبقه بالای همکف
        } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f6))) {
            return $this->sendPamameterF6Text();
        // درصد سطح اشغال طبقه هفتم در صورت وجود هشت طبقه بالای همکف
        } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f7))) {
            return $this->sendPamameterF7Text();
        // درصد سطح اشغال طبقه هشتم در صورت وجود هشت طبقه بالای همکف
        } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f8))) {
            return $this->sendPamameterF8Text();
        // موقعیت قرار گیری ملک درب از حیاط است    
        } elseif($construction->m == 1 && (empty($construction->constructionBalconies) || is_null($construction->constructionBalconies->b1))) {
            return $this->sendPamameterB1Text();
        // موقعیت قرار گیری ملک درب از ساختمان است    
        } elseif($construction->m == 2 && (empty($construction->constructionBalconies) || is_null($construction->constructionBalconies->b1))) {
            return $this->sendPamameterB1Text();
        // موقعیت قرار گیری ملک درب از ساختمان است    
        } elseif($construction->m == 2 && (empty($construction->constructionBalconies) || is_null($construction->constructionBalconies->b2))) {
            return $this->sendPamameterB2Text();
        // موقعیت قرار گیری ملک دو بر یا سر نبش است 
        } elseif($construction->m > 2 && (empty($construction->constructionBalconies) || is_null($construction->constructionBalconies->b1))) {
            return $this->sendPamameterB1Text();
        } elseif($construction->m > 2 && (empty($construction->constructionBalconies) || is_null($construction->constructionBalconies->b2))) {
            return $this->sendPamameterB2Text();
        } elseif($construction->m > 2 && (empty($construction->constructionBalconies) || is_null($construction->constructionBalconies->b3))) {
            return $this->sendPamameterB3Text();
        // هزینه ساخت هر متر مربع
        } elseif(empty($construction->constructionPrices) || is_null($construction->constructionPrices->pc)) {
            return $this->sendPamameterPCText();
        // قیمت هر متر مربع زمین
        } elseif(empty($construction->constructionPrices) || is_null($construction->constructionPrices->pm)) {
            return $this->sendPamameterPMText();
        // قیمت فروش آپارتمان
        } elseif(empty($construction->constructionPrices) || is_null($construction->constructionPrices->pa)) {
            return $this->sendPamameterPAText();
        // هزینه های پروانه ساخت شهرداری
        } elseif(empty($construction->constructionPrices) || is_null($construction->constructionPrices->ps)) {
            return $this->sendPamameterPSText();
        // هزینه های خاص پروژه
        } elseif(empty($construction->constructionPrices) || is_null($construction->constructionPrices->pk)) {
            return $this->sendPamameterPKText();
        } else {
            return $this->displayFinalSelection();
        }  
    }

    public function sendPamameterCText() {
        try {
            $text = 'نام شهر را بنویسید';
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
   
    public function sendPamameterMText() {
        try {
            $text = 'موقعیت قرارگیری ملک را انتخاب نمایید';
            $option = array( 
                // First row
                array($this->telegram->buildInlineKeyBoardButton('درب از حیاط', '', '/constructionsendpamameterm1')), 
                // Second row
                array($this->telegram->buildInlineKeyBoardButton('درب از ساختمان', '', '/constructionsendpamameterm2')), 
                // Third row
                array($this->telegram->buildInlineKeyBoardButton('دوبر (دو کله)', '', '/constructionsendpamameterm3')), 
                // Fourth row
                array($this->telegram->buildInlineKeyBoardButton('سر نبش درب از ساختمان', '', '/constructionsendpamameterm4')), 
                // Fifth row
                array($this->telegram->buildInlineKeyBoardButton('سر نبش درب از کوچه', '', '/constructionsendpamameterm5')), 
                // Sixth row
                array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
            );
            $keyb = $this->telegram->buildInlineKeyBoard($option);
            $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        } catch (\Exception $e) {
            // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function sendPamameterAText() {
        try {
            $text = 'مساحت زمین را به متر مربع وارد نمایید';
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
            $text = 'عرض متوسط ملک را به متر مربع وارد نمایید';
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

    public function sendPamameterNBText() {
        try {
            $text = 'تعداد طبقات زیر زمین را انتخاب نمایید';
            $option = array( 
                // First row
                array($this->telegram->buildInlineKeyBoardButton('فاقد زیر زمین', '', '/constructionsendpamameternb1')), 
                // Second row
                array($this->telegram->buildInlineKeyBoardButton('1', '', '/constructionsendpamameternb2')), 
                // Third row
                array($this->telegram->buildInlineKeyBoardButton('2', '', '/constructionsendpamameternb3')), 
                // Fourth row
                array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
            );
            $keyb = $this->telegram->buildInlineKeyBoard($option);
            $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        } catch (\Exception $e) {
            // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function sendPamameterNFText() {
        try {
            $text = 'تعداد طبقات بالای همکف را وارد نمایید';
            $option = array( 
                // First row
                array($this->telegram->buildInlineKeyBoardButton('فاقد طبقه فوقانی همکف', '', '/constructionsendpamameternf1')), 
                // Second row
                array($this->telegram->buildInlineKeyBoardButton('1', '', '/constructionsendpamameternf2')), 
                // Third row
                array($this->telegram->buildInlineKeyBoardButton('2', '', '/constructionsendpamameternf3')), 
                // Fourth row
                array($this->telegram->buildInlineKeyBoardButton('3', '', '/constructionsendpamameternf4')), 
                // Fifth row
                array($this->telegram->buildInlineKeyBoardButton('4', '', '/constructionsendpamameternf5')), 
                // Sixth row
                array($this->telegram->buildInlineKeyBoardButton('5', '', '/constructionsendpamameternf6')), 
                // Seventh row
                array($this->telegram->buildInlineKeyBoardButton('6', '', '/constructionsendpamameternf7')), 
                // Eightgh row
                array($this->telegram->buildInlineKeyBoardButton('7', '', '/constructionsendpamameternf8')), 
                // Nignth row
                array($this->telegram->buildInlineKeyBoardButton('8', '', '/constructionsendpamameternf9')), 
                // Tenth row
                array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
            );
            $keyb = $this->telegram->buildInlineKeyBoard($option);
            $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        } catch (\Exception $e) {
            // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function sendPamameterBasement1Text() {
        try {
            $text = 'درصد سطح اشغال زیر زمین اول را وارد نمایید';
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

    public function sendPamameterBasement2Text() {
        try {
            $text = 'درصد سطح اشغال زیر زمین دوم را وارد نمایید';
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

    public function sendPamameterGText() {
        try {
            $text = 'درصد سطح اشغال طبقه همکف را وارد نمایید';
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

    public function sendPamameterF1Text() {
        try {
            $text = 'درصد سطح اشغال طبقه اول را وارد نمایید';
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

    public function sendPamameterF2Text() {
        try {
            $text = 'درصد سطح اشغال طبقه دوم را وارد نمایید';
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

    public function sendPamameterF3Text() {
        try {
            $text = 'درصد سطح اشغال طبقه سوم را وارد نمایید';
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

    public function sendPamameterF4Text() {
        try {
            $text = 'درصد سطح اشغال طبقه چهارم را وارد نمایید';
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

    public function sendPamameterF5Text() {
        try {
            $text = 'درصد سطح اشغال طبقه پنجم را وارد نمایید';
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

    public function sendPamameterF6Text() {
        try {
            $text = 'درصد سطح اشغال طبقه ششم را وارد نمایید';
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

    public function sendPamameterF7Text() {
        try {
            $text = 'درصد سطح اشغال طبقه هفتم را وارد نمایید';
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

    public function sendPamameterF8Text() {
        try {
            $text = 'درصد سطح اشغال طبقه هشتم را وارد نمایید';
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

    public function sendPamameterB1Text() {
        try {
            $text = 'عرض بالکن سمت حیاط را به متر وارد نمایید';
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

    public function sendPamameterB2Text() {
        try {
            $text = 'عرض بالکن سمت کوچه اول را به متر وارد نمایید';
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

    public function sendPamameterB3Text() {
        try {
            $text = 'عرض بالکن سمت کوچه دوم (کوچه کناری) را به متر وارد نمایید';
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

    public function sendPamameterPCText() {
        try {
            $text = 'هزینه ساخت هر متر مربع را به تومان وارد نمایید';
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

    public function sendPamameterPMText() {
        try {
            $text = 'قیمت هر متر مربع زمین را وارد نمایید';
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

    public function sendPamameterPAText() {
        try {
            $text = 'قیمت فروش آپارتمان را به تومان وارد نمایید';
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

    public function sendPamameterPSText() {
        try {
            $text = 'هزینه های پروانه ساخت شهرداری را به تومان وارد نمایید';
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

    public function sendPamameterPKText() {
        try {
            $text = 'هزینه های خاص این پروژه را به تومان وارد نمایید';
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

    public function displayFinalSelection() {
        try {
            $text = 'لطفا یکی از موارد زیر را انتخاب نمایید';
            $option = array( 
                // First row
                array($this->telegram->buildInlineKeyBoardButton('🧮 محاسبه هزینه ساخت', '', '/getconstcalcexpenses')), 
                // Second row
                array($this->telegram->buildInlineKeyBoardButton('🧮 محاسبه نسبت منصفانه مشارکت در ساخت', '', '/getconstcalccollaborative')), 
                // Third row
                array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
            );
            $keyb = $this->telegram->buildInlineKeyBoard($option);
            $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        } catch (\Exception $e) {
            // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }

    public function displayConstCalcExpenseFinalResults() {

        $constructionResult = new ConstructionCalculationResult($this->telegram);

        // دریافت ورودی های کاربر
        $initialParameters = $constructionResult->getInitialParameters();

        // زیر بنا
        $area = $constructionResult->calculateArea();

        // زیر بنای قابل ساخت
        $totalAreaASK = $constructionResult->calculateTotalAreaASK();

        // مشاعات
        $totalAreaAMK = $constructionResult->calculateTotalAreaAMK();

        // مساحت مفید قابل فروش
        $totalAreaAPK = $constructionResult->calculateTotalAreaAPK();

        // محاسبه کل زیر بنا و هزینه ساخت 
        $constExpenses = $constructionResult->calculateConstExpenses();

        $text = '
            🎊 محاسبات با موفقیت انجام گردید:
        ';

        $text .= '
مساحت زمین ' . $initialParameters['a'] . '	متر مربع 
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
            array($this->telegram->buildInlineKeyBoardButton('⬇ دانلود پی دی اف محاسبات', '', '/constructiondownloadresults')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔁 محاسبه مجدد', '', '/constructionresetresults')), 
            // Third row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/start')), 
        );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);
    }

    public function displayConstCalcCollaborativeFinalResults() {

    }

    public function downloadResults() {

        // $telegram = $this->telegram;
        // $chat_id = $telegram->ChatID();

        // $beamAndBlockRoofResult = new BeamAndBlockRoofResult($this->telegram);

        // $h = $this->beamAndBlockRoof->h;

        // if($h == 25) {
        //     $data = $beamAndBlockRoofResult->calculateH25();
        // } elseif($h == 20) {
        //     $data = $beamAndBlockRoofResult->calculateH20();
        // } 

        // // Step 1: Generate the PDF content
        // $pdf = PDF::loadView('generatepdf-beam-and-block-roof', $data);

        // // Step 2: Save the generated PDF to a temporary location
        // $uniqueFileName = hexdec(uniqid());
        // $filename = $uniqueFileName . '.' . 'pdf';
        // $pdfPath = storage_path('app/public/' . $filename);
        // $pdf->save($pdfPath);

        // // Step 3: Use curl_file_create() to create a CURLFile object
        // $file = curl_file_create($pdfPath, 'application/pdf', 'calculations.pdf');

        // // Step 4: Send the file using Telegram bot
        // $content = array('chat_id' => $chat_id, 'document' => $file);
        // $result = $telegram->sendDocument($content);

        // // Step 5: Remove the temporary file
        // if (file_exists($pdfPath)) {
        //     unlink($pdfPath);
        // }
       
        // $this->saveMessageId($telegram, $result);
    }
}