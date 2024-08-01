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

            if(empty($construction->c)) {
                
                // validation
               

                $constructionObj = $latestAction->construction()->create([
                    'c' => !empty($text) ? $text : null,
                ]);

                $latestAction->update([
                    'subaction_id' => $constructionObj->id,
                ]);

                $constructionBotResponse->sendPamameterCText();
                \Log::info($construction->c);
            } elseif(empty($construction->m)) {

                if($text == '/beamandblockroofsendpamameterh20' || $text == 20) {
                    $text = 20;
                } elseif($text == '/beamandblockroofsendpamameterh25' || $text == 25) {
                    $text = 25;
                } else {
                    $text = 20;
                }
            }

        } catch (\Exception $e) {
           // \Log::info('An error occurred: ' . $e->getMessage());
        }
    }
} 