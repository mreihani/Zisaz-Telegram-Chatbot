<?php

namespace App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\Concreting;

use App\Services\ZisazBot\Sections\ConcretingMatrialsCalculation\Sections\Concreting\ConcretingCalculation;

class ConcretingResult extends ConcretingCalculation {

    public $telegram;
    public $user;
    public $concreting;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->concreting = $this->user->actions->flatMap->concreting->first();
    }
    
    public function calculateConcreting() {
       
        // دریافت حجم بتن از دیتابیس
        $v = !empty($this->concreting->v) ? floatval($this->concreting->v) : 0;

        // دریافت عیار سیمان از دیتابیس
        $c = !empty($this->concreting->c) ? floatval($this->concreting->c) : 0;

        $w1 = $c * $v;
        $w2 = $v * 0.55 * 1900;
        $w3 = $v * 0.45 * 1900;
        $v1 = 0.5 * $w1;

        return [
            'w1' => ceil($w1),
            'w2' => ceil($w2),
            'w3' => ceil($w3),
            'v' => ceil($v),
        ];
    }
}