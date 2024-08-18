<?php

namespace App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\DecorativeStone;

use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\DecorativeStone\DecorativeStoneCalculation;

class DecorativeStoneResult extends DecorativeStoneCalculation {

    public $telegram;
    public $user;
    public $dececorativeStone;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->dececorativeStone = $this->user->actions->flatMap->dececorativeStone->first();
    }
    
    public function calculateDecorativeStone() {
       
        // دریافت عیار سیمان از دیتابیس
        $c = !empty($this->dececorativeStone->c) ? floatval($this->dececorativeStone->c) : 0;

        // دریافت ضخامت متوسط دوغاب نما از دیتابیس
        $t = !empty($this->dececorativeStone->t) ? floatval($this->dececorativeStone->t) : 0;

        // دریافت وزن مخصوص دوغاب از دیتابیس
        $b = !empty($this->dececorativeStone->b) ? floatval($this->dececorativeStone->b) : 0;

        // دریافت متراژ کل از دیتابیس
        $a = !empty($this->dececorativeStone->a) ? floatval($this->dececorativeStone->a) : 0;

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