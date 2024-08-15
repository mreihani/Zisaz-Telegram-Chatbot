<?php

namespace App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarWeight;

use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarWeight\RebarWeightCalculation;

class RebarWeightResult extends RebarWeightCalculation {

    public $telegram;
    public $user;
    public $rebarWeight;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->rebarWeight = $this->user->actions->flatMap->rebarWeight->first();
    }
    
    public function calculateRebarWeight() {
       
        // دریافت قطر میلگرد از دیتابیس
        $d = !empty($this->rebarWeight->d) ? floatval($this->rebarWeight->d) : 0;

        // تبدیل قطر به متر
        $d1 = $d / 1000;

        // وزن یک متر میگرد
        $w = ((3.14 * $d1 * $d1) / 4) * 7850;

        // وزن یک شاخه 12 متری میلگرد
        $w1 = $w * 12;

        return [
            'd' => $d,
            'w' => bcdiv($w, 1, 2),
            'w1' => bcdiv($w1, 1, 2),
        ];
    }
}