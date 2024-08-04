<?php

namespace App\Services\ZisazBot\Sections\ConstructionCalculation;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\Construction\Construction;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionValidation;
use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionBotResponse;

class ConstructionCalculation extends ZisazBot {

    public $telegram;
    public $user;

    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->saveMessageIdUserPrompt($telegram);
    }

    public function displayItem() {
       
        $text = '
در این بخش محاسبات محاسبات زیربنا، هزینه و مشارکت در ساخت انجام می شود. 
            
اطلاعات مورد نیاز:
1- نام شهر
2- موقعیت قرارگیری ملک
3- مساحت زمین       
4- عرض متوسط ملک       
5- تعداد طبقات       
6- درصد سطح اشغال طبقات       
7- عرض بالکن       
8- قیمت ها       
⤵
        ';
        
        $option = array( 
            // First row
            array($this->telegram->buildInlineKeyBoardButton('☑ ادامه', '', '/constructionsendpamameteratext')), 
            // Second row
            array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/getmenu')), 
        );
        
        // $option = array( 
        //     // First row
        //     array($this->telegram->buildInlineKeyBoardButton('محاسبه هزینه ساخت', '', '/getconstcalcexpenses')), 
        //     // Second row
        //     array($this->telegram->buildInlineKeyBoardButton('نسبت منصفانه مشارکت در ساخت', '', '/getconstcalccollaborative')), 
        //     // Fourth row
        //     array($this->telegram->buildInlineKeyBoardButton('🔙 بازگشت', '', '/getmenu')), 
        // );

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $this->sendMessageWithInlineKeyBoard($this->telegram, $keyb, $text);

        // set and update action
        $this->initializeAction(new Construction());
    }

    public function getUserPrompts() {
        
        try {
            $text = $this->telegram->Text();

            $latestAction = $this->getLastActionObject($this->telegram);

            $construction = $latestAction->construction->first();

            $constructionBotResponse = new ConstructionBotResponse($this->telegram);
            $constructionValidation = new ConstructionValidation($this->telegram);

            // نام شهر
            if(empty($construction) || is_null($construction->c)) {
               
                $constructionObj = $latestAction->construction()->create([
                    'c' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $constructionObj->id,
                ]);

                $constructionBotResponse->sendPamameterMText();
               
            // موقعیت قرارگیری ملک
            } elseif(is_null($construction->m)) {

                if($text == '/constructionsendpamameterm1') {
                    $text = 1;
                } elseif($text == '/constructionsendpamameterm2') {
                    $text = 2;
                } elseif($text == '/constructionsendpamameterm3') {
                    $text = 3;
                } elseif($text == '/constructionsendpamameterm4') {
                    $text = 4;
                } elseif($text == '/constructionsendpamameterm5') {
                    $text = 5;
                } else {
                    $text = 1;
                }

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'm');
                // user input validation for positive integer values
                $constructionValidation->isPositiveInteger($text, 'm');
                // user input validation for not zero
                $constructionValidation->isNotZero($text, 'm');

                $latestAction->construction()->update([
                    'm' => !empty($text) ? $text : null,
                ]);

                $constructionBotResponse->sendPamameterAText();

            // مساحت زمین    
            } elseif(is_null($construction->a)) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'a');
                // user input validation for positive integer values
                $constructionValidation->isPositiveInteger($text, 'a');
                // user input validation for not zero
                $constructionValidation->isNotZero($text, 'a');

                $latestAction->construction()->update([
                    'a' => !empty($text) ? $text : null,
                ]);

                $constructionBotResponse->sendPamameterBText();

            // عرض متوسط ملک    
            } elseif(is_null($construction->b)) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'b');
                // user input validation for positive integer values
                $constructionValidation->isPositiveInteger($text, 'b');
                // user input validation for not zero
                $constructionValidation->isNotZero($text, 'b');

                $latestAction->construction()->update([
                    'b' => !empty($text) ? $text : 10,
                ]);

                $constructionBotResponse->sendPamameterNBText();

            // تعداد طبقات زیر زمین    
            } elseif(is_null($construction->nb)) {

                if($text == '/constructionsendpamameternb1') {
                    $text = 0;
                } elseif($text == '/constructionsendpamameternb2') {
                    $text = 1;
                } elseif($text == '/constructionsendpamameternb3') {
                    $text = 2;
                } else {
                    $text = 0;
                }

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'nb');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'nb', [0, 2]); 

                $latestAction->construction()->update([
                    'nb' => !empty($text) ? $text : 0,
                ]);

                $constructionBotResponse->sendPamameterNFText();

            // تعداد طبقات بالای همکف    
            } elseif(is_null($construction->nf)) {

                if($text == '/constructionsendpamameternf1') {
                    $text = 0;
                } elseif($text == '/constructionsendpamameternf2') {
                    $text = 1;
                } elseif($text == '/constructionsendpamameternf3') {
                    $text = 2;
                } elseif($text == '/constructionsendpamameternf4') {
                    $text = 3;
                } elseif($text == '/constructionsendpamameternf5') {
                    $text = 4;
                } elseif($text == '/constructionsendpamameternf6') {
                    $text = 5;
                } elseif($text == '/constructionsendpamameternf7') {
                    $text = 6;
                } elseif($text == '/constructionsendpamameternf8') {
                    $text = 7;
                } elseif($text == '/constructionsendpamameternf9') {
                    $text = 8;
                } else {
                    $text = 0;
                }

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'nf');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'nf', [0, 8]);

                $latestAction->construction()->update([
                    'nf' => !empty($text) ? $text : 0,
                ]);
                
                if($construction->nb == 0) {
                    // در صورت نداشتن زیر زمین
                    $constructionBotResponse->sendPamameterGText();
                } else {
                    $constructionBotResponse->sendPamameterBasement1Text();
                }

            // درصد سطح اشغال زیر زمین اول در صورت وجود یک زیر زمین    
            } elseif($construction->nb == 1 && (empty($construction->constructionBasements) || is_null($construction->constructionBasements->b1))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'nb1');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'nb1', [0, 100]);

                $construction->constructionBasements()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b1' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterGText();

            // درصد سطح اشغال زیر زمین در صورت وجود دو زیر زمین    
            } elseif($construction->nb == 2 && (empty($construction->constructionBasements) || is_null($construction->constructionBasements->b1))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'nb1');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'nb1', [0, 100]);

                $construction->constructionBasements()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b1' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterBasement2Text();

            // درصد سطح اشغال زیر زمین دوم در صورت وجود دو زیر زمین    
            } elseif($construction->nb == 2 && (empty($construction->constructionBasements) || is_null($construction->constructionBasements->b2))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'nb2');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'nb2', [0, 100]);

                $construction->constructionBasements()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b2' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterGText();

            // درصد  سطح اشغال طبقه همکف
            } elseif(empty($construction->constructionFloors) || is_null($construction->constructionFloors->g)) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'g');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'g', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'g' => !empty($text) ? $text : 0,
                    ]
                );

                if($construction->nf == 0) {
                    // در صورت نداشتن هیچ طبقه ای بالای همکف
                    $constructionBotResponse->sendPamameterB1Text();
                } else {
                    $constructionBotResponse->sendPamameterF1Text();
                }

            // درصد سطح اشغال طبقه اول در صورت وجود یک طبقه بالای همکف
            } elseif($construction->nf == 1 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f1');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f1', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterB1Text();

            // درصد سطح اشغال طبقه اول در صورت وجود دو طبقه بالای همکف
            } elseif($construction->nf == 2 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f1');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f1', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF2Text();

            // درصد سطح اشغال طبقه دوم در صورت وجود دو طبقه بالای همکف
            } elseif($construction->nf == 2 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f2))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f2');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f2', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f2' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterB1Text();

            // درصد سطح اشغال طبقه اول در صورت وجود سه طبقه بالای همکف
            } elseif($construction->nf == 3 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f1');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f1', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF2Text();

            // درصد سطح اشغال طبقه دوم در صورت وجود سه طبقه بالای همکف
            } elseif($construction->nf == 3 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f2))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f2');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f2', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f2' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF3Text();

            // درصد سطح اشغال طبقه سوم در صورت وجود سه طبقه بالای همکف
            } elseif($construction->nf == 3 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f3))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f3');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f3', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f3' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterB1Text();

            // درصد سطح اشغال طبقه اول در صورت وجود چهار طبقه بالای همکف
            } elseif($construction->nf == 4 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f1');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f1', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF2Text();

            // درصد سطح اشغال طبقه دوم در صورت وجود چهار طبقه بالای همکف
            } elseif($construction->nf == 4 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f2))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f2');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f2', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f2' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF3Text();

            // درصد سطح اشغال طبقه سوم در صورت وجود چهار طبقه بالای همکف
            } elseif($construction->nf == 4 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f3))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f3');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f3', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f3' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF4Text();

            // درصد سطح اشغال طبقه چهارم در صورت وجود چهار طبقه بالای همکف
            } elseif($construction->nf == 4 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f4))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f4');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f4', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f4' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterB1Text();

            // درصد سطح اشغال طبقه اول در صورت وجود پنج طبقه بالای همکف
            } elseif($construction->nf == 5 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f1');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f1', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF2Text();

            // درصد سطح اشغال طبقه دوم در صورت وجود پنج طبقه بالای همکف
            } elseif($construction->nf == 5 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f2))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f2');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f2', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f2' => !empty($text) ? $text : 0,
                    ]
                );
                $constructionBotResponse->sendPamameterF3Text();

            // درصد سطح اشغال طبقه سوم در صورت وجود پنج طبقه بالای همکف
            } elseif($construction->nf == 5 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f3))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f3');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f3', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f3' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF4Text();

            // درصد سطح اشغال طبقه چهارم در صورت وجود پنج طبقه بالای همکف
            } elseif($construction->nf == 5 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f4))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f4');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f4', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f4' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF5Text();

            // درصد سطح اشغال طبقه پنجم در صورت وجود پنج طبقه بالای همکف
            } elseif($construction->nf == 5 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f5))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f5');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f5', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f5' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterB1Text();

            // درصد سطح اشغال طبقه اول در صورت وجود شش طبقه بالای همکف
            } elseif($construction->nf == 6 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f1');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f1', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF2Text();

            // درصد سطح اشغال طبقه دوم در صورت وجود شش طبقه بالای همکف
            } elseif($construction->nf == 6 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f2))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f2');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f2', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f2' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF3Text();

            // درصد سطح اشغال طبقه سوم در صورت وجود شش طبقه بالای همکف
            } elseif($construction->nf == 6 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f3))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f3');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f3', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f3' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF4Text();

            // درصد سطح اشغال طبقه چهارم در صورت وجود شش طبقه بالای همکف
            } elseif($construction->nf == 6 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f4))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f4');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f4', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f4' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF5Text();

            // درصد سطح اشغال طبقه پنجم در صورت وجود شش طبقه بالای همکف
            } elseif($construction->nf == 6 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f5))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f5');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f5', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f5' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF6Text();

            // درصد سطح اشغال طبقه ششم در صورت وجود شش طبقه بالای همکف
            } elseif($construction->nf == 6 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f6))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f6');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f6', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f6' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterB1Text();

            // درصد سطح اشغال طبقه اول در صورت وجود هفت طبقه بالای همکف
            } elseif($construction->nf == 7 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f1');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f1', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF2Text();

            // درصد سطح اشغال طبقه دوم در صورت وجود هفت طبقه بالای همکف
            } elseif($construction->nf == 7 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f2))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f2');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f2', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f2' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF3Text();

            // درصد سطح اشغال طبقه سوم در صورت وجود هفت طبقه بالای همکف
            } elseif($construction->nf == 7 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f3))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f3');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f3', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f3' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF4Text();

            // درصد سطح اشغال طبقه چهارم در صورت وجود هفت طبقه بالای همکف
            } elseif($construction->nf == 7 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f4))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f4');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f4', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f4' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF5Text();

            // درصد سطح اشغال طبقه پنجم در صورت وجود هفت طبقه بالای همکف
            } elseif($construction->nf == 7 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f5))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f5');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f5', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f5' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF6Text();

            // درصد سطح اشغال طبقه ششم در صورت وجود هفت طبقه بالای همکف
            } elseif($construction->nf == 7 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f6))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f6');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f6', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f6' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF7Text();

            // درصد سطح اشغال طبقه هفتم در صورت وجود هفت طبقه بالای همکف
            } elseif($construction->nf == 7 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f7))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f7');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f7', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f7' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterB1Text();

            // درصد سطح اشغال طبقه اول در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f1))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f1');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f1', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF2Text();

            // درصد سطح اشغال طبقه دوم در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f2))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f2');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f2', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f2' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF3Text();

            // درصد سطح اشغال طبقه سوم در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f3))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f3');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f3', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f3' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF4Text();

            // درصد سطح اشغال طبقه چهارم در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f4))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f4');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f4', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f4' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF5Text();

            // درصد سطح اشغال طبقه پنجم در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f5))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f5');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f5', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f5' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF6Text();

            // درصد سطح اشغال طبقه ششم در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f6))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f6');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f6', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f6' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF7Text();

            // درصد سطح اشغال طبقه هفتم در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f7))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f7');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f7', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f7' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterF8Text();

            // درصد سطح اشغال طبقه هشتم در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && (empty($construction->constructionFloors) || is_null($construction->constructionFloors->f8))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'f8');
                // user input validation for specific number span
                $constructionValidation->isBetween($text, 'f8', [0, 100]);

                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f8' => !empty($text) ? $text : 0,
                    ]
                );
                $constructionBotResponse->sendPamameterB1Text();

            // بخش بالکن ها
            // موقعیت قرار گیری ملک درب از حیاط است    
            } elseif($construction->m == 1 && (empty($construction->constructionBalconies) || is_null($construction->constructionBalconies->b1))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'b1');
                // user input validation for positive integer values
                $constructionValidation->isPositiveInteger($text, 'b1');

                $construction->constructionBalconies()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b1' => !empty($text) ? $text : 0,
                        'b2' => null,
                        'b3' => null,
                    ]
                );

                $constructionBotResponse->sendPamameterPCText();

            // موقعیت قرار گیری ملک درب از ساختمان است    
            } elseif($construction->m == 2 && (empty($construction->constructionBalconies) || is_null($construction->constructionBalconies->b1))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'b1');
                // user input validation for positive integer values
                $constructionValidation->isPositiveInteger($text, 'b1');

                $construction->constructionBalconies()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b1' => !empty($text) ? $text : 0,
                        'b3' => null,
                    ]
                );

                $constructionBotResponse->sendPamameterB2Text();

            // موقعیت قرار گیری ملک درب از ساختمان است    
            } elseif($construction->m == 2 && (empty($construction->constructionBalconies) || is_null($construction->constructionBalconies->b2))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'b2');
                // user input validation for positive integer values
                $constructionValidation->isPositiveInteger($text, 'b2');

                $construction->constructionBalconies()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b2' => !empty($text) ? $text : 0,
                        'b3' => null,
                    ]
                );

                $constructionBotResponse->sendPamameterPCText();

            // موقعیت قرار گیری ملک دو بر یا سر نبش است 
            } elseif($construction->m > 2 && (empty($construction->constructionBalconies) || is_null($construction->constructionBalconies->b1))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'b1');
                // user input validation for positive integer values
                $constructionValidation->isPositiveInteger($text, 'b1');

                $construction->constructionBalconies()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b1' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterB2Text();

            } elseif($construction->m > 2 && (empty($construction->constructionBalconies) || is_null($construction->constructionBalconies->b2))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'b2');
                // user input validation for positive integer values
                $constructionValidation->isPositiveInteger($text, 'b2');

                $construction->constructionBalconies()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b2' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterB3Text();

            } elseif($construction->m > 2 && (empty($construction->constructionBalconies) || is_null($construction->constructionBalconies->b3))) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'b3');
                // user input validation for positive integer values
                $constructionValidation->isPositiveInteger($text, 'b3');

                $construction->constructionBalconies()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b3' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterPCText();

            // هزینه ساخت هر متر مربع
            } elseif(empty($construction->constructionPrices) || is_null($construction->constructionPrices->pc)) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'pc');
                // user input validation for positive integer values
                $constructionValidation->isPositiveInteger($text, 'pc');
                // user input validation for not zero
                $constructionValidation->isNotZero($text, 'pc');

                $construction->constructionPrices()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'pc' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterPMText();

            // قیمت هر متر مربع زمین
            } elseif(empty($construction->constructionPrices) || is_null($construction->constructionPrices->pm)) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'pm');
                // user input validation for positive integer values
                $constructionValidation->isPositiveInteger($text, 'pm');
                // user input validation for not zero
                $constructionValidation->isNotZero($text, 'pm');

                $construction->constructionPrices()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'pm' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterPAText();

            // قیمت فروش آپارتمان
            } elseif(empty($construction->constructionPrices) || is_null($construction->constructionPrices->pa)) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'pa');
                // user input validation for positive integer values
                $constructionValidation->isPositiveInteger($text, 'pa');
                // user input validation for not zero
                $constructionValidation->isNotZero($text, 'pa');

                $construction->constructionPrices()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'pa' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterPSText();

            // هزینه های پروانه ساخت شهرداری
            } elseif(empty($construction->constructionPrices) || is_null($construction->constructionPrices->ps)) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'ps');
                // user input validation for positive integer values
                $constructionValidation->isPositiveInteger($text, 'ps');

                $construction->constructionPrices()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'ps' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->sendPamameterPKText();

            // هزینه های خاص پروژه
            } elseif(empty($construction->constructionPrices) || is_null($construction->constructionPrices->pk)) {

                // user input validation for numeric values
                $constructionValidation->isNumericValidation($text, 'pk');
                // user input validation for positive integer values
                $constructionValidation->isPositiveInteger($text, 'pk');


                $construction->constructionPrices()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'pk' => !empty($text) ? $text : 0,
                    ]
                );

                $constructionBotResponse->displayFinalResults();
            }   

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }
} 