<?php

namespace App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\BodyTile;

use App\Services\ZisazBot\Sections\FacadeAndFlooringMaterialCalculation\Sections\BodyTile\BodyTileCalculation;

class BodyTileResult extends BodyTileCalculation {

    public $telegram;
    public $user;
    public $bodyTile;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->bodyTile = $this->user->actions->flatMap->bodyTile->first();
    }
    
    public function calculateBodyTile() {
       
        // دریافت عیار سیمان از دیتابیس
        $c = !empty($this->bodyTile->c) ? floatval($this->bodyTile->c) : 0;

        // دریافت ضخامت متوسط دوغاب کاشی بدنه از دیتابیس
        $t = !empty($this->bodyTile->t) ? floatval($this->bodyTile->t) : 0;

        // دریافت وزن مخصوص دوغاب از دیتابیس
        $b = !empty($this->bodyTile->b) ? floatval($this->bodyTile->b) : 0;

        // دریافت متراژ کل از دیتابیس
        $a = !empty($this->bodyTile->a) ? floatval($this->bodyTile->a) : 0;

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