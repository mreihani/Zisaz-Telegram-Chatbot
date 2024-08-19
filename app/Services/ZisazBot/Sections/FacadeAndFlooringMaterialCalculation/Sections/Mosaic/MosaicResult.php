<?php

namespace App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\Mosaic;

use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\Mosaic\MosaicCalculation;

class MosaicResult extends MosaicCalculation {

    public $telegram;
    public $user;
    public $mosaic;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->mosaic = $this->user->actions->flatMap->mosaic->first();
    }
    
    public function calculateMosaic() {
       
        // دریافت عیار سیمان از دیتابیس
        $c = !empty($this->mosaic->c) ? floatval($this->mosaic->c) : 0;

        // دریافت ضخامت متوسط ملات موزائیک کف از دیتابیس
        $t = !empty($this->mosaic->t) ? floatval($this->mosaic->t) : 0;

        // دریافت وزن مخصوص دوغاب از دیتابیس
        $b = !empty($this->mosaic->b) ? floatval($this->mosaic->b) : 0;

        // دریافت متراژ کل از دیتابیس
        $a = !empty($this->mosaic->a) ? floatval($this->mosaic->a) : 0;

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