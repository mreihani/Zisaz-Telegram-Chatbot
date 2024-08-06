<?php

namespace App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation;

use App\Services\ZisazBot\Sections\BeamAndBlockRoofCalculation\BeamAndBlockRoofCalculation;

class BeamAndBlockRoofResult extends BeamAndBlockRoofCalculation {

    public $telegram;
    public $user;
    public $beamAndBlockRoof;
    public $a;
    public $h;
    public $c;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->beamAndBlockRoof = $this->user->actions->pluck('beamAndBlockRoof')->flatten()->filter()->first();
        $this->a = $this->beamAndBlockRoof->a;
        $this->h = $this->beamAndBlockRoof->h;
        $this->c = $this->beamAndBlockRoof->c;
    }
    
    // محاسبات برای تیرچه H=25
    public function calculateH25() {
        // تعداد فوم 
        $n = $this->a * 0.68;

        // متراژ تیرچه
        $l = $this->a * 1.5;

        // حجم بتون 
        $v = $this->a * 0.155;

        // وزن سیمان 
        $w = $v * $this->c;

        // وزن شن و ماسه 
        $s = $v * 1900;

        // وزن میلگرد حرارتی 
        $wi = $this->a * 1.06;
        
        return [
            'a' => bcdiv($this->a, 1, 2),
            'h' => bcdiv($this->h, 1, 2),
            'n' => ceil($this->a * 0.68),
            'l' => bcdiv($l, 1, 2),
            'v' => bcdiv($v, 1, 2),
            'w' => bcdiv($w, 1, 2),
            's' => bcdiv($s, 1, 2),
            'wi' => bcdiv($wi, 1, 2),
        ];
    }

    public function calculateH20() {
        // تعداد فوم 
        $n = $this->a * 0.68;

        // متراژ تیرچه
        $l = $this->a * 1.5;

        // حجم بتون 
        $v = $this->a * 0.18;

        // وزن سیمان 
        $w = $v * $this->c;

        // وزن شن و ماسه 
        $s = $v * 1900;

        // وزن میلگرد حرارتی 
        $wi = $this->a * 1.06;

        return [
            'a' => bcdiv($this->a, 1, 2),
            'h' => bcdiv($this->h, 1, 2),
            'n' => ceil($this->a * 0.68),
            'l' => bcdiv($l, 1, 2),
            'v' => bcdiv($v, 1, 2),
            'w' => bcdiv($w, 1, 2),
            's' => bcdiv($s, 1, 2),
            'wi' => bcdiv($wi, 1, 2),
        ];
    }
}