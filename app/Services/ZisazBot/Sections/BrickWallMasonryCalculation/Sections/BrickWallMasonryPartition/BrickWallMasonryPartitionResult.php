<?php

namespace App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPartition;

use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryPartition\BrickWallMasonryPartitionCalculation;

class BrickWallMasonryPartitionResult extends BrickWallMasonryPartitionCalculation {

    public $telegram;
    public $user;
    public $brickWallMasonryPartition;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->brickWallMasonryPartition = $this->user->actions->flatMap->brickWallMasonryPartition->first();
    }
    
    public function calculateBrickWallMasonryPartition() {
       
        // دریافت مساحت کل دیوار از دیتابیس
        $a = !empty($this->brickWallMasonryPartition->a) ? floatval($this->brickWallMasonryPartition->a) : 0;

        // دریافت عرض دیوار از دیتابیس
        $b = !empty($this->brickWallMasonryPartition->b) ? floatval($this->brickWallMasonryPartition->b) : 0;

        // دریافت طول آجر از دیتابیس 
        $r = !empty($this->brickWallMasonryPartition->r) ? floatval($this->brickWallMasonryPartition->r) : 22;

        // دریفا عرض آجر از دیتابیس
        $e = !empty($this->brickWallMasonryPartition->e) ? floatval($this->brickWallMasonryPartition->e) : 10.5;

        // دریفا ضخامت آجر از دیتابیس
        $f = !empty($this->brickWallMasonryPartition->f) ? floatval($this->brickWallMasonryPartition->f) : 5.5;

        // دریاف عیار ملات از دیتابیس
        $c = !empty($this->brickWallMasonryPartition->c) ? floatval($this->brickWallMasonryPartition->c) : 250;

        if($b == 8) {
            $p = 13;
        } elseif($b == 13) {
            $p = 8;
        }

        // تعداد رج آجر در یک متر ارتفاع
        $r2 = 100 / ($p + 1.7);

        // تبدیل ضخامت دیوار به متر
        $b1 = $b / 100;

        // تعداد آجر
        $n = $r2 * (100 / $r);

        // حجم ملات
        $v = ($b1 * 0.018 * 1 * $r2 * 1.06) * $a;

        // وزن سیمان
        $w = $v * $c;

        // وزن ماسه
        $s = $v * 2000;

        return [
            'b' => bcdiv($b, 1, 2),
            'a' => bcdiv($a, 1, 2),
            'r' => bcdiv($r, 1, 2),
            'e' => bcdiv($e, 1, 2),
            'f' => bcdiv($f, 1, 2),
            'n' => ceil($n),
            'w' => ceil($w),
            's' => ceil($s),
            'c' => $c,
        ];
    }
}