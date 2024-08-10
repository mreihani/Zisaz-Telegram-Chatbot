<?php

namespace App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryApartmentBlock;

use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryApartmentBlock\BrickWallMasonryApartmentBlockCalculation;

class BrickWallMasonryApartmentBlockResult extends BrickWallMasonryApartmentBlockCalculation {

    public $telegram;
    public $user;
    public $brickWallMasonryApartmentBlock;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->brickWallMasonryApartmentBlock = $this->user->actions->flatMap->brickWallMasonryApartmentBlock->first();
    }
    
    public function calculateBrickWallMasonryApartmentBlock() {
       
        // دریافت مساحت کل دیوار از دیتابیس
        $a = !empty($this->brickWallMasonryApartmentBlock->a) ? $this->brickWallMasonryApartmentBlock->a : 0;

        // دریافت عرض دیوار از دیتابیس
        $b = !empty($this->brickWallMasonryApartmentBlock->b) ? $this->brickWallMasonryApartmentBlock->b : 0;

        // دریاف عیار ملات از دیتابیس
        $c = !empty($this->brickWallMasonryApartmentBlock->c) ? $this->brickWallMasonryApartmentBlock->c : 0;

        // محاسبه تعداد بلوک
        $n = $a * 13;

        // محاسبه حجم ملات
        $v = ((5 * $b * 0.02) / 100 * $a) * 1.06;

        // محاسبه وزن سیمان
        $w = $v * $c;

        // محاسبه وزن ماسه
        $s = $v * 2000;
        
        return [
            'n' => ceil($n),
            'v' => bcdiv($v, 1, 2),
            'w' => bcdiv($w, 1, 2),
            's' => bcdiv($s, 1, 2),
            'b' => $b,
            'a' => $a,
        ];
    }
}