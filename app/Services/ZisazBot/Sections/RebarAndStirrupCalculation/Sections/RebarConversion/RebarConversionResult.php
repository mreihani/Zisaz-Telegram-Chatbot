<?php

namespace App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarConversion;

use App\Services\ZisazBot\Sections\RebarAndStirrupCalculation\Sections\RebarConversion\RebarConversionCalculation;

class RebarConversionResult extends RebarConversionCalculation {

    public $telegram;
    public $user;
    public $rebarConversion;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->rebarConversion = $this->user->actions->flatMap->rebarConversion->first();
    }
    
    public function calculateRebarConversion() {
       
        // دریافت قطر میلگرد نقشه از دیتابیس
        $d1 = !empty($this->rebarConversion->d1) ? floatval($this->rebarConversion->d1) : 0;

        // دریافت تعداد میلگرد نقشه از دیتابیس
        $n = !empty($this->rebarConversion->n) ? floatval($this->rebarConversion->n) : 0;

        // دریافت قطر میلگرد موجود جدید از دیتابیس
        $d2 = !empty($this->rebarConversion->d2) ? floatval($this->rebarConversion->d2) : 0;

        // محاسبه مساحت میلگرد نقشه
        $a1 = (3.14 * $d1 * $d1 / 4) * $n;

        // محاسبه مساحت میلگرد موجود جدید
        $a2 = (3.14 * $d2 * $d2 / 4);

        // محاسبه تعداد میلگرد معادل سازی شده
        $n1 = $a1 / $a2;

        return [
            'd1' => $d1,
            'n' => $n,
            'd2' => $d2,
            'a1' => bcdiv($a1, 1, 2),
            'a2' => bcdiv($a2, 1, 2),
            'n1' => ceil($n1),
        ];
    }
}