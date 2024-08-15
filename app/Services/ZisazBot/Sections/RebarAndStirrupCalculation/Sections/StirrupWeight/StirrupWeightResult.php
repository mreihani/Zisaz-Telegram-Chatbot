<?php

namespace App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\StirrupWeight;
use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\StirrupWeight\StirrupWeightCalculation;

class StirrupWeightResult extends StirrupWeightCalculation {

    public $telegram;
    public $user;
    public $stirrupWeight;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->stirrupWeight = $this->user->actions->flatMap->stirrupWeight->first();
    }
    
    public function calculateStirrupWeight() {
       
        // دریافت قطر میلگرد خاموت از دیتابیس
        $d = !empty($this->stirrupWeight->d) ? floatval($this->stirrupWeight->d) : 0;

        // دریافت طول خاموت از دیتابیس
        $l = !empty($this->stirrupWeight->l) ? floatval($this->stirrupWeight->l) : 0;

        // دریافت عرض خاموت از دیتابیس
        $b = !empty($this->stirrupWeight->b) ? floatval($this->stirrupWeight->b) : 0;

        // دریافت تعداد خاموت از دیتابیس
        $n = !empty($this->stirrupWeight->n) ? floatval($this->stirrupWeight->n) : 0;

        // تبدیل قطر به متر
        $d1 = $d / 1000;

        // وزن یک متر میلگرد
        $w = ((3.14 * $d1 * $d1) / 4) * 7850;

        // وزن یک عدد خاموت
        $w1 = $w * ($b + $l + 8) * 2 / 100;

        // وزن کل خاموت
        $w2 = $w1 + $n;

        return [
            'd' => $d,
            'l' => $l,
            'b' => $b,
            'n' => $n,
            'w' => bcdiv($w, 1, 2),
            'w1' => bcdiv($w1, 1, 2),
            'w2' => bcdiv($w2, 1, 2),
        ];
    }
}