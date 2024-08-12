<?php

namespace App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPressedBrick;

use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPressedBrick\BrickWallMasonryPressedBrickCalculation;

class BrickWallMasonryPressedBrickResult extends BrickWallMasonryPressedBrickCalculation {

    public $telegram;
    public $user;
    public $brickWallMasonryPressedBrick;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->brickWallMasonryPressedBrick = $this->user->actions->flatMap->brickWallMasonryPressedBrick->first();
    }
    
    public function calculateBrickWallMasonryPressedBrick() {
       
        // دریافت مساحت کل دیوار از دیتابیس
        $a = !empty($this->brickWallMasonryPressedBrick->a) ? floatval($this->brickWallMasonryPressedBrick->a) : 0;

        // دریافت عرض دیوار از دیتابیس
        $b = !empty($this->brickWallMasonryPressedBrick->b) ? floatval($this->brickWallMasonryPressedBrick->b) : 0;

        // دریافت طول آجر از دیتابیس 
        $r = !empty($this->brickWallMasonryPressedBrick->r) ? floatval($this->brickWallMasonryPressedBrick->r) : 22;

        // دریفا عرض آجر از دیتابیس
        $e = !empty($this->brickWallMasonryPressedBrick->e) ? floatval($this->brickWallMasonryPressedBrick->e) : 10.5;

        // دریفا ضخامت آجر از دیتابیس
        $f = !empty($this->brickWallMasonryPressedBrick->f) ? floatval($this->brickWallMasonryPressedBrick->f) : 5.5;

        // دریاف عیار ملات از دیتابیس
        $c = !empty($this->brickWallMasonryPressedBrick->c) ? floatval($this->brickWallMasonryPressedBrick->c) : 250;

        // مساحت آجر
        $a4 = $r * $f;

        // تعداد رج آجر در یک متر ارتفاع
        $r2 = 100 / ($f + 1.7);

        // تبدیل ضخامت دیوار به متر
        $b1 = $b / 100 ;

        // تعداد آجر
        $n = $r2 * ($b1 * 1) / $a4;

        // حجم ملات
        $v = ($b1 * 0.018 * 1 * $r2 * 1.06 ) * $a;

        // وزن سیمان
        $w = $v * $c;

        // وزن ماسه
        $s = $v * 2000;
        
        return [
            'b' => bcdiv($b, 1, 2),
            'a' => bcdiv($a, 1, 2),
            'n' => ceil($n),
            'w' => ceil($w),
            's' => ceil($s),
            'r' => bcdiv($r, 1, 2),
            'e' => bcdiv($e, 1, 2),
            'f' => bcdiv($f, 1, 2),
            'c' => $c,
        ];
    }
}