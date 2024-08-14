<?php

namespace App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampSteep;

use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\RampSteep\RampSteepCalculation;

class RampSteepResult extends RampSteepCalculation {

    public $telegram;
    public $user;
    public $rampSteep;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->rampSteep = $this->user->actions->flatMap->rampSteep->first();
    }
    
    public function calculateRampSteep() {
       
        // دریافت ارتفاع رمپ از دیتابیس
        $h = !empty($this->rampSteep->h) ? floatval($this->rampSteep->h) : 0;

        // دریافت طول رمپ از دیتابیس
        $l = !empty($this->rampSteep->l) ? floatval($this->rampSteep->l) : 0;

        // محاسبه شیب رمپ
        $s = ($h / $l) * 100;

        return [
            'h' => bcdiv($h, 1, 2),
            'l' => bcdiv($l, 1, 2),
            's' => bcdiv($s, 1, 2),
        ];
    }
}