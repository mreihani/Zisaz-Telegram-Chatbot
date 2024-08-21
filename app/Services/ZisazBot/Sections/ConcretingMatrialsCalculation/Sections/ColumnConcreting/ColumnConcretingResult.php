<?php

namespace App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\ColumnConcreting;

use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\ColumnConcreting\ColumnConcretingCalculation;

class ColumnConcretingResult extends ColumnConcretingCalculation {

    public $telegram;
    public $user;
    public $columnConcreting;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->columnConcreting = $this->user->actions->flatMap->columnConcreting->first();
    }
    
    public function calculateColumnConcreting() {
       
        // دریافت تعداد ستون از دیتابیس
        $v = !empty($this->columnConcreting->v) ? floatval($this->columnConcreting->v) : 0;

        // دریافت طول ستون از دیتابیس
        $l = !empty($this->columnConcreting->l) ? floatval($this->columnConcreting->l) : 0;

        // دریافت عرض ستون از دیتابیس
        $b = !empty($this->columnConcreting->b) ? floatval($this->columnConcreting->b) : 0;

        // دریافت ارتفاع ستون از دیتابیس
        $h = !empty($this->columnConcreting->h) ? floatval($this->columnConcreting->h) : 0;

        // دریافت عیار سیمان از دیتابیس
        $c = !empty($this->columnConcreting->c) ? floatval($this->columnConcreting->c) : 0;

        // حجم بتن ریزی
        $v3 = ($l * $b * $h) / 1000000 * $v;

        // وزن سیمان مصرفی
        $w1 = $c * $v3;

        // وزن سیمان مصرفی     
        $w2 = 0.55 * 1900 * $v3;

        // وزن شن نخودی و بادامی
        $w3 = 0.45 * 1900 * $v3;

        // حجم آب
        $v1 = 0.5 * $w1;
        
        return [
            'l' => $l,
            'b' => $b,
            'h' => $h,
            'v' => $v,
            'v3' => bcdiv($v3, 1, 2),
            'w1' => ceil($w1),
            'w2' => ceil($w2),
            'w3' => ceil($w3),
            'v1' => bcdiv($v1, 1, 2),
        ];
    }
}