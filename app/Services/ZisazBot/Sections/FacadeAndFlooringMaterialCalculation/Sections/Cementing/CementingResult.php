<?php

namespace App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\Cementing;

use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\Cementing\CementingCalculation;

class CementingResult extends CementingCalculation {

    public $telegram;
    public $user;
    public $cementing;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->cementing = $this->user->actions->flatMap->cementing->first();
    }
    
    public function calculateCementing() {
       
        // دریافت عیار سیمان از دیتابیس
        $c = !empty($this->cementing->c) ? floatval($this->cementing->c) : 0;

        // دریافت ضخامت متوسط سیمان کاری از دیتابیس
        $t = !empty($this->cementing->t) ? floatval($this->cementing->t) : 0;

        // دریافت وزن مخصوص دوغاب از دیتابیس
        $b = !empty($this->cementing->b) ? floatval($this->cementing->b) : 0;

        // دریافت متراژ کل از دیتابیس
        $a = !empty($this->cementing->a) ? floatval($this->cementing->a) : 0;

        // حجم بتن
        $v = ($a * $t) / 100;

        // وزن سیمان
        $w1 = $v * $c * 1.05;

        // وزن ماسه
        $w2 = $v * ($b - $c) * 1.05;

        return [
            'a' => $a,
            'w1' => ceil($w1),
            'w2' => ceil($w2),
        ];
    }
}