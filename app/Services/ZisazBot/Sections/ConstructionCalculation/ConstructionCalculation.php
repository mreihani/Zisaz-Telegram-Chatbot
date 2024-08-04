<?php

namespace App\Services\ZisazBot\Sections\ConstructionCalculation;

use App\Models\User;
use App\Services\ZisazBot\ZisazBot;
use App\Models\Action\Construction\Construction;
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

            // نام شهر
            if(empty($construction->c)) {
                
                // validation
               
                $constructionObj = $latestAction->construction()->create([
                    'c' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $constructionObj->id,
                ]);

                $constructionBotResponse->sendPamameterMText();
               
            // موقعیت قرارگیری ملک
            } elseif(empty($construction->m)) {

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

                $latestAction->construction()->update([
                    'm' => !empty($text) ? $text : null,
                ]);

                $constructionBotResponse->sendPamameterAText();

            // مساحت زمین    
            } elseif(empty($construction->a)) {
                $latestAction->construction()->update([
                    'a' => !empty($text) ? $text : null,
                ]);

                $constructionBotResponse->sendPamameterBText();

            // عرض متوسط ملک    
            } elseif(empty($construction->b)) {
                $latestAction->construction()->update([
                    'b' => !empty($text) ? $text : 10,
                ]);

                $constructionBotResponse->sendPamameterNBText();

            // تعداد طبقات زیر زمین    
            } elseif(is_null($construction->nb)) {
                $latestAction->construction()->update([
                    'nb' => !empty($text) ? $text : 0,
                ]);

                $constructionBotResponse->sendPamameterNFText();

            // تعداد طبقات بالای همکف    
            } elseif(is_null($construction->nf)) {
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
            } elseif($construction->nb == 1 && empty($construction->constructionBasements->b1)) {
                $construction->constructionBasements()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b1' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterGText();

            // درصد سطح اشغال زیر زمین در صورت وجود دو زیر زمین    
            } elseif($construction->nb == 2 && empty($construction->constructionBasements->b1)) {
                $construction->constructionBasements()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b1' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterBasement2Text();

            // درصد سطح اشغال زیر زمین دوم در صورت وجود دو زیر زمین    
            } elseif($construction->nb == 2 && empty($construction->constructionBasements->b2)) {
                $construction->constructionBasements()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b2' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterGText();

            // درصد  سطح اشغال طبقه همکف
            } elseif(empty($construction->constructionFloors->g)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'g' => !empty($text) ? $text : null,
                    ]
                );

                if($construction->nf == 0) {
                    // در صورت نداشتن هیچ طبقه ای بالای همکف
                    $constructionBotResponse->sendPamameterB1Text();
                } else {
                    $constructionBotResponse->sendPamameterF1Text();
                }

            // درصد سطح اشغال طبقه اول در صورت وجود یک طبقه بالای همکف
            } elseif($construction->nf == 1 && empty($construction->constructionFloors->f1)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterB1Text();

            // درصد سطح اشغال طبقه اول در صورت وجود دو طبقه بالای همکف
            } elseif($construction->nf == 2 && empty($construction->constructionFloors->f1)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF2Text();

            // درصد سطح اشغال طبقه دوم در صورت وجود دو طبقه بالای همکف
            } elseif($construction->nf == 2 && empty($construction->constructionFloors->f2)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f2' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterB1Text();

            // درصد سطح اشغال طبقه اول در صورت وجود سه طبقه بالای همکف
            } elseif($construction->nf == 3 && empty($construction->constructionFloors->f1)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF2Text();

            // درصد سطح اشغال طبقه دوم در صورت وجود سه طبقه بالای همکف
            } elseif($construction->nf == 3 && empty($construction->constructionFloors->f2)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f2' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF3Text();

            // درصد سطح اشغال طبقه سوم در صورت وجود سه طبقه بالای همکف
            } elseif($construction->nf == 3 && empty($construction->constructionFloors->f3)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f3' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterB1Text();

            // درصد سطح اشغال طبقه اول در صورت وجود چهار طبقه بالای همکف
            } elseif($construction->nf == 4 && empty($construction->constructionFloors->f1)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF2Text();

            // درصد سطح اشغال طبقه دوم در صورت وجود چهار طبقه بالای همکف
            } elseif($construction->nf == 4 && empty($construction->constructionFloors->f2)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f2' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF3Text();

            // درصد سطح اشغال طبقه سوم در صورت وجود چهار طبقه بالای همکف
            } elseif($construction->nf == 4 && empty($construction->constructionFloors->f3)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f3' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF4Text();

            // درصد سطح اشغال طبقه چهارم در صورت وجود چهار طبقه بالای همکف
            } elseif($construction->nf == 4 && empty($construction->constructionFloors->f4)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f4' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterB1Text();

            // درصد سطح اشغال طبقه اول در صورت وجود پنج طبقه بالای همکف
            } elseif($construction->nf == 5 && empty($construction->constructionFloors->f1)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF2Text();

            // درصد سطح اشغال طبقه دوم در صورت وجود پنج طبقه بالای همکف
            } elseif($construction->nf == 5 && empty($construction->constructionFloors->f2)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f2' => !empty($text) ? $text : null,
                    ]
                );
                $constructionBotResponse->sendPamameterF3Text();

            // درصد سطح اشغال طبقه سوم در صورت وجود پنج طبقه بالای همکف
            } elseif($construction->nf == 5 && empty($construction->constructionFloors->f3)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f3' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF4Text();

            // درصد سطح اشغال طبقه چهارم در صورت وجود پنج طبقه بالای همکف
            } elseif($construction->nf == 5 && empty($construction->constructionFloors->f4)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f4' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF5Text();

            // درصد سطح اشغال طبقه پنجم در صورت وجود پنج طبقه بالای همکف
            } elseif($construction->nf == 5 && empty($construction->constructionFloors->f5)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f5' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterB1Text();

            // درصد سطح اشغال طبقه اول در صورت وجود شش طبقه بالای همکف
            } elseif($construction->nf == 6 && empty($construction->constructionFloors->f1)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF2Text();

            // درصد سطح اشغال طبقه دوم در صورت وجود شش طبقه بالای همکف
            } elseif($construction->nf == 6 && empty($construction->constructionFloors->f2)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f2' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF3Text();

            // درصد سطح اشغال طبقه سوم در صورت وجود شش طبقه بالای همکف
            } elseif($construction->nf == 6 && empty($construction->constructionFloors->f3)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f3' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF4Text();

            // درصد سطح اشغال طبقه چهارم در صورت وجود شش طبقه بالای همکف
            } elseif($construction->nf == 6 && empty($construction->constructionFloors->f4)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f4' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF5Text();

            // درصد سطح اشغال طبقه پنجم در صورت وجود شش طبقه بالای همکف
            } elseif($construction->nf == 6 && empty($construction->constructionFloors->f5)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f5' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF6Text();

            // درصد سطح اشغال طبقه ششم در صورت وجود شش طبقه بالای همکف
            } elseif($construction->nf == 6 && empty($construction->constructionFloors->f6)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f6' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterB1Text();

            // درصد سطح اشغال طبقه اول در صورت وجود هفت طبقه بالای همکف
            } elseif($construction->nf == 7 && empty($construction->constructionFloors->f1)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF2Text();

            // درصد سطح اشغال طبقه دوم در صورت وجود هفت طبقه بالای همکف
            } elseif($construction->nf == 7 && empty($construction->constructionFloors->f2)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f2' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF3Text();

            // درصد سطح اشغال طبقه سوم در صورت وجود هفت طبقه بالای همکف
            } elseif($construction->nf == 7 && empty($construction->constructionFloors->f3)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f3' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF4Text();

            // درصد سطح اشغال طبقه چهارم در صورت وجود هفت طبقه بالای همکف
            } elseif($construction->nf == 7 && empty($construction->constructionFloors->f4)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f4' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF5Text();

            // درصد سطح اشغال طبقه پنجم در صورت وجود هفت طبقه بالای همکف
            } elseif($construction->nf == 7 && empty($construction->constructionFloors->f5)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f5' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF6Text();

            // درصد سطح اشغال طبقه ششم در صورت وجود هفت طبقه بالای همکف
            } elseif($construction->nf == 7 && empty($construction->constructionFloors->f6)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f6' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF7Text();

            // درصد سطح اشغال طبقه هفتم در صورت وجود هفت طبقه بالای همکف
            } elseif($construction->nf == 7 && empty($construction->constructionFloors->f7)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f7' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterB1Text();

            // درصد سطح اشغال طبقه اول در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && empty($construction->constructionFloors->f1)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f1' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF2Text();

            // درصد سطح اشغال طبقه دوم در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && empty($construction->constructionFloors->f2)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f2' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF3Text();

            // درصد سطح اشغال طبقه سوم در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && empty($construction->constructionFloors->f3)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f3' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF4Text();

            // درصد سطح اشغال طبقه چهارم در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && empty($construction->constructionFloors->f4)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f4' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF5Text();

            // درصد سطح اشغال طبقه پنجم در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && empty($construction->constructionFloors->f5)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f5' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF6Text();

            // درصد سطح اشغال طبقه ششم در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && empty($construction->constructionFloors->f6)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f6' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF7Text();

            // درصد سطح اشغال طبقه هفتم در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && empty($construction->constructionFloors->f7)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f7' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterF8Text();

            // درصد سطح اشغال طبقه هشتم در صورت وجود هشت طبقه بالای همکف
            } elseif($construction->nf == 8 && empty($construction->constructionFloors->f8)) {
                $construction->constructionFloors()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'f8' => !empty($text) ? $text : null,
                    ]
                );
                $constructionBotResponse->sendPamameterB1Text();

            // موقعیت قرار گیری ملک درب از حیاط است    
            } elseif($construction->m == 1 && empty($construction->constructionBalconies->b1)) {
                $construction->constructionBalconies()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b1' => !empty($text) ? $text : null,
                        'b2' => null,
                        'b3' => null,
                    ]
                );

                $constructionBotResponse->sendPamameterPCText();

            // موقعیت قرار گیری ملک درب از ساختمان است    
            } elseif($construction->m == 2 && empty($construction->constructionBalconies->b1)) {
                $construction->constructionBalconies()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b1' => !empty($text) ? $text : null,
                        'b3' => null,
                    ]
                );

                $constructionBotResponse->sendPamameterB2Text();

            // موقعیت قرار گیری ملک درب از ساختمان است    
            } elseif($construction->m == 2 && empty($construction->constructionBalconies->b2)) {
                $construction->constructionBalconies()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b2' => !empty($text) ? $text : null,
                        'b3' => null,
                    ]
                );

                $constructionBotResponse->sendPamameterPCText();

            // موقعیت قرار گیری ملک دو بر یا سر نبش است 
            } elseif($construction->m > 2 && empty($construction->constructionBalconies->b1)) {
                $construction->constructionBalconies()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b1' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterB2Text();

            } elseif($construction->m > 2 && empty($construction->constructionBalconies->b2)) {
                $construction->constructionBalconies()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b2' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterB3Text();

            } elseif($construction->m > 2 && empty($construction->constructionBalconies->b3)) {
                $construction->constructionBalconies()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'b3' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterPCText();

            // هزینه ساخت هر متر مربع
            } elseif(empty($construction->constructionPrices->pc)) {
                $construction->constructionPrices()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'pc' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterPMText();

            // قیمت هر متر مربع زمین
            } elseif(empty($construction->constructionPrices->pm)) {
                $construction->constructionPrices()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'pm' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterPAText();

            // قیمت فروش آپارتمان
            } elseif(empty($construction->constructionPrices->pa)) {
                $construction->constructionPrices()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'pa' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterPSText();

            // هزینه های پروانه ساخت شهرداری
            } elseif(empty($construction->constructionPrices->ps)) {
                $construction->constructionPrices()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'ps' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->sendPamameterPKText();

            // هزینه های خاص پروژه
            } elseif(empty($construction->constructionPrices->pk)) {
                $construction->constructionPrices()->updateOrCreate(
                    [
                        'construction_id' => $construction->id
                    ],
                    [
                        'pk' => !empty($text) ? $text : null,
                    ]
                );

                $constructionBotResponse->displayFinalResults();
            }   

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }
} 