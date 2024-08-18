<?php

namespace App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\Ceramic;

use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\Ceramic\CeramicCalculation;

class CeramicResult extends CeramicCalculation {

    public $telegram;
    public $user;
    public $ceramic;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->ceramic = $this->user->actions->flatMap->ceramic->first();
    }
    
    public function calculateCeramic() {
       
        // دریافت عیار سیمان از دیتابیس
        $c = !empty($this->ceramic->c) ? floatval($this->ceramic->c) : 0;

        // دریافت ضخامت متوسط ملات سرامیک کف از دیتابیس
        $t = !empty($this->ceramic->t) ? floatval($this->ceramic->t) : 0;

        // دریافت وزن مخصوص دوغاب از دیتابیس
        $b = !empty($this->ceramic->b) ? floatval($this->ceramic->b) : 0;

        // دریافت متراژ کل از دیتابیس
        $a = !empty($this->ceramic->a) ? floatval($this->ceramic->a) : 0;

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