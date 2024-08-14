<?php

namespace App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampLength;

use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampLength\RampLengthCalculation;

class RampLengthResult extends RampLengthCalculation {

    public $telegram;
    public $user;
    public $rampLength;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->rampLength = $this->user->actions->flatMap->rampLength->first();
    }
    
    public function calculateRampLength() {
       
        // دریافت ارتفاع رمپ از دیتابیس
        $h = !empty($this->rampLength->h) ? floatval($this->rampLength->h) : 0;

        // دریافت شیب رمپ از دیتابیس
        $s = !empty($this->rampLength->s) ? floatval($this->rampLength->s) : 0;

        // محاسبه شیب رمپ
        $l = ($h / $s) * 100;

        return [
            'h' => bcdiv($h, 1, 2),
            's' => bcdiv($s, 1, 2),
            'l' => bcdiv($l, 1, 2),
        ];
    }
}