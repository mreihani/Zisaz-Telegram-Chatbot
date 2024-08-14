<?php

namespace App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\ExpansionJoint;

use App\Services\ZisazBot\Sections\RampAndExpansionJointCalculation\Sections\ExpansionJoint\ExpansionJointCalculation;

class ExpansionJointResult extends ExpansionJointCalculation {

    public $telegram;
    public $user;
    public $expansionJoint;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->expansionJoint = $this->user->actions->flatMap->expansionJoint->first();
    }
    
    public function calculateExpansionJoint() {
       
        // دریافت ارتفاع ساختمان از دیتابیس
        $h = !empty($this->expansionJoint->h) ? floatval($this->expansionJoint->h) : 0;

        // محاسبه درز انقطاع
        $b = $h * 0.005 * 100;

        return [
            'b' => bcdiv($b, 1, 2),
        ];
    }
}