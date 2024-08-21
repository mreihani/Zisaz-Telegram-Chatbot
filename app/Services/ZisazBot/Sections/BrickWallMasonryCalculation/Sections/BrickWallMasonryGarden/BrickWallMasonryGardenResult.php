<?php

namespace App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryGarden;

use App\Services\ZisazBot\Sections\BrickWallMasonryCalculation\Sections\BrickWallMasonryGarden\BrickWallMasonryGardenCalculation;

class BrickWallMasonryGardenResult extends BrickWallMasonryGardenCalculation {

    public $telegram;
    public $user;
    public $brickWallMasonryGarden;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->brickWallMasonryGarden = $this->user->actions->flatMap->brickWallMasonryGarden->first();
    }

    private function retriveInitialValues() {

        // دریافت طول دیوار از دیتابیس
        $l = !empty($this->brickWallMasonryGarden->l) ? $this->brickWallMasonryGarden->l : 0;

        // دریافت ارتفاع دیوار از دیتابیس
        $h = !empty($this->brickWallMasonryGarden->h) ? $this->brickWallMasonryGarden->h : 0;

        // دریافت عرض دیوار چینی از دیتابیس
        $b = !empty($this->brickWallMasonryGarden->b) ? $this->brickWallMasonryGarden->b : 0;

        // دریافت حالت انتخاب شده توسط کاربر از دیتابیس
        $type = !empty($this->brickWallMasonryGarden->type) ? $this->brickWallMasonryGarden->type : 'a';

        // دریافت عمق پی از دیتابیس
        $d = !empty($this->brickWallMasonryGarden->d) ? $this->brickWallMasonryGarden->d : 0;

        return [
            'l' => $l,
            'h' => $h,
            'b' => $b,
            'type' => $type,
            'd' => $d
        ];
    }

    public function calculateBrickWallMasonryGarden() {
       
        // دریافت پارامتر های ورودی کاربر
        $initialValues = $this->retriveInitialValues();

        if($initialValues['type'] == 'a') {
            return $this->calculateTypeA();
        } else {
            return $this->calculateTypeB();
        }
    }

    // محاسبه حالت اول
    private function calculateTypeA() {

        // دریافت پارامتر های ورودی کاربر
        $initialValues = $this->retriveInitialValues();

        // محاسبه تعداد بلوک
        $n = ($initialValues['l'] * $initialValues['h'] * 13) + ($initialValues['l'] / 4 * $initialValues['h'] / 0.2) + ($initialValues['l'] * $initialValues['d'] * 25);

        // محاسبه حجم ملات
        $v = ($n * 0.2 * 0.2 * 0.4) * 0.6 * 1.06;

        // وزن سیمان
        $w = $v * 250;

        // وزن ماسه
        $s = $v * 2000;

        // طول پی کنی
        $l = $initialValues['l'];

        return [
            'n' => ceil($n),
            'd' => bcdiv($initialValues['d'], 1, 2),
            'v' => bcdiv($v, 1, 2),
            'w' => bcdiv($w, 1, 2),
            's' => bcdiv($s, 1, 2),
            'l' => bcdiv($l, 1, 2),
            'type' => $initialValues['type']
        ];
    }

    // محاسبه حالت دوم
    private function calculateTypeB() {

        // دریافت پارامتر های ورودی کاربر
        $initialValues = $this->retriveInitialValues();

        // محاسبه تعداد بلوک
        $n = ($initialValues['l'] * $initialValues['h'] * 13) + ($initialValues['l'] * 5);

        // محاسبه حجم ملات
        $v = ($n * 0.2 * 0.2 * 0.4) * 0.6 * 1.06;

        // محاسبه تعداد شناژ های عمودی
        $nc = $initialValues['l'] / 4;

        // محاسبه حجم ملات دیوار چینی
        $v1 = $n * 0.2 * 0.2 * 0.4 * 0.6 * 1.06;

        // محاسبه حجم بتن شناژ ها
        $v2 = ($initialValues['l'] * 0.4 * 0.3) + ($nc * 0.04 * 0.4 * $initialValues['h']);

        // وزن سیمان
        $w = ($v1 * 250) + ($v2 * 350);

        // وزن ماسه
        $s = ($v1 + $v2) * 2000;

        // طول پی کنی
        $l = $initialValues['l'];

        // وزن میلگرد 14
        $w1 = (($l * 4 * 1.06) + ($nc * 4 * ($initialValues['h'] + 0.6))) * 1.21; 

        // تعداد خاموت
        $nk = ($l / 0.23) + ($nc * ($initialValues['h'] + 0.3) / 0.23);

        // وزن خاموت 8
        $w2 = ($nk * (((0.2 + 0.3)) * 2) + 0.1) * 0.4;

        return [
            'n' => ceil($n),
            'nc' => ceil($nc),
            'v1' => bcdiv($v1, 1, 2),
            'v2' => bcdiv($v2, 1, 2),
            'w' => bcdiv($w, 1, 2),
            's' => bcdiv($s, 1, 2),
            'l' => bcdiv($l, 1, 2),
            'w1' => bcdiv($w1, 1, 2),
            'nk' => bcdiv($nk, 1, 2),
            'w2' => bcdiv($w2, 1, 2),
            'd' => bcdiv($initialValues['d'], 1, 2),
            'type' => $initialValues['type']
        ];
    }
}