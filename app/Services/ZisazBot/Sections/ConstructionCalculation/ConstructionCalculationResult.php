<?php

namespace App\Services\ZisazBot\Sections\ConstructionCalculation;

use App\Services\ZisazBot\Sections\ConstructionCalculation\ConstructionCalculation;

class ConstructionCalculationResult extends ConstructionCalculation {

    public $telegram;
    public $user;
    public $construction;
   
    public function __construct($telegram) {
        $this->telegram = $telegram;
        $this->user = $this->getUser($telegram);
        $this->construction = $this->user->actions->flatMap->construction->first();
    }
    
    // دریافت پارامتر های اولیه وارد شده توسط کاربر
    public function getInitialParameters() {

        // نام شهر
        $c = !empty($this->construction->c) ? $this->construction->c : 0;

        // موقعیت قرارگیری ملک
        $m = !empty($this->construction->m) ? intval($this->construction->m) : 0;

        // مساحت زمین
        $a = !empty($this->construction->a) ? floatval($this->construction->a) : 0;

        // عرض متوسط ملک
        $b = !empty($this->construction->b) ? floatval($this->construction->b) : 0;

        // تعداد طبقات زیر زمین
        $nb = !empty($this->construction->nb) ? intval($this->construction->nb) : 0;

        // ضریب طبقه همکف
        $ng = !empty($this->construction->ng) ? intval($this->construction->ng) : 0;

        // تعداد طبقات بالای همکف
        $nf = !empty($this->construction->nf) ? intval($this->construction->nf) : 0;

        // ضریب طبقه سرپله
        $ns = !empty($this->construction->ns) ? intval($this->construction->ns) : 0;

        // درصد اشغال زیر زمین اول
        $basement1 = !empty($this->construction->constructionBasements->b1) ? intval($this->construction->constructionBasements->b1) : 0;
        
        // درصد اشغال زیر زمین دوم
        $basement2 = !empty($this->construction->constructionBasements->b2) ? intval($this->construction->constructionBasements->b2) : 0;

        // درصد اشغال طبقه همکف
        $g = !empty($this->construction->constructionFloors->g) ? intval($this->construction->constructionFloors->g) : 0;
        
        // درصد اشغال طبقه اول تا هشتم
        $f1 = !empty($this->construction->constructionFloors->f1) ? intval($this->construction->constructionFloors->f1) : 0;
        $f2 = !empty($this->construction->constructionFloors->f2) ? intval($this->construction->constructionFloors->f2) : 0;
        $f3 = !empty($this->construction->constructionFloors->f3) ? intval($this->construction->constructionFloors->f3) : 0;
        $f4 = !empty($this->construction->constructionFloors->f4) ? intval($this->construction->constructionFloors->f4) : 0;
        $f5 = !empty($this->construction->constructionFloors->f5) ? intval($this->construction->constructionFloors->f5) : 0;
        $f6 = !empty($this->construction->constructionFloors->f6) ? intval($this->construction->constructionFloors->f6) : 0;
        $f7 = !empty($this->construction->constructionFloors->f7) ? intval($this->construction->constructionFloors->f7) : 0;
        $f8 = !empty($this->construction->constructionFloors->f8) ? intval($this->construction->constructionFloors->f8) : 0;

        // عرض بالکن سمت حیاط
        $balcony1 = !empty($this->construction->constructionBalconies->b1) ? floatval($this->construction->constructionBalconies->b1) : 0;
        $balcony2 = !empty($this->construction->constructionBalconies->b2) ? floatval($this->construction->constructionBalconies->b2) : 0;
        $balcony3 = !empty($this->construction->constructionBalconies->b3) ? floatval($this->construction->constructionBalconies->b3) : 0;
        
        // هزینه ساخت هر متر مربع
        $pc = !empty($this->construction->constructionPrices->pc) ? intval($this->construction->constructionPrices->pc) : 0;

        // قیمت هر متر مربع زمین
        $pm = !empty($this->construction->constructionPrices->pm) ? intval($this->construction->constructionPrices->pm) : 0;

        // قیمت فروش آپارتمان
        $pa = !empty($this->construction->constructionPrices->pa) ? intval($this->construction->constructionPrices->pa) : 0;

        // هزینه های پروانه ساخت شهرداری
        $ps = !empty($this->construction->constructionPrices->ps) ? intval($this->construction->constructionPrices->ps) : 0;

        // هزینه های خاص پروژه
        $pk = !empty($this->construction->constructionPrices->pk) ? intval($this->construction->constructionPrices->pk) : 0;

        return [
            'c' => $c,
            'm' => $m,
            'a' => $a,
            'b' => $b,
            'nb' => $nb,
            'ng' => $ng,
            'nf' => $nf,
            'ns' => $ns,
            'basement1' => $basement1,
            'basement2' => $basement2,
            'g' => $g,
            'f1' => $f1,
            'f2' => $f2,
            'f3' => $f3,
            'f4' => $f4,
            'f5' => $f5,
            'f6' => $f6,
            'f7' => $f7,
            'f8' => $f8,
            'balcony1' => $balcony1,
            'balcony2' => $balcony2,
            'balcony3' => $balcony3,
            'pc' => $pc,
            'pm' => $pm,
            'pa' => $pa,
            'ps' => $ps,
            'pk' => $pk
        ];
    }

    // محاسبات زیر بنا
    public function calculateArea() {

        // دریافت ورودی های کاربر
        $initialParameters = $this->getInitialParameters();

        // طول متوسط زمین
        $l = $initialParameters['a'] / $initialParameters['b'];

        // طول پیشروی طبقه همکف
        $lg = $initialParameters['g'] * $l;

        // طول پیشروی طبقه اول الی هشتم
        $lf1 = $initialParameters['f1'] * $l;
        $lf2 = $initialParameters['f2'] * $l;
        $lf3 = $initialParameters['f3'] * $l;
        $lf4 = $initialParameters['f4'] * $l;
        $lf5 = $initialParameters['f5'] * $l;
        $lf6 = $initialParameters['f6'] * $l;
        $lf7 = $initialParameters['f7'] * $l;
        $lf8 = $initialParameters['f8'] * $l;

        // محاسبه سطح اشغال زیر زمین اول
        $ab1 = $initialParameters['basement1'] * $initialParameters['a'] / 100;
        $ab2 = $initialParameters['basement2'] * $initialParameters['a'] / 100;

        // محاسبه سطح اشغال طبقه همکف
        $ag = $initialParameters['g'] * $initialParameters['a'] / 100;

        // محاسبه سطح اشغال طبقه اول الی هشتم
        $af1 = $initialParameters['f1'] * $initialParameters['a'] / 100;
        $af2 = $initialParameters['f2'] * $initialParameters['a'] / 100;
        $af3 = $initialParameters['f3'] * $initialParameters['a'] / 100;
        $af4 = $initialParameters['f4'] * $initialParameters['a'] / 100;
        $af5 = $initialParameters['f5'] * $initialParameters['a'] / 100;
        $af6 = $initialParameters['f6'] * $initialParameters['a'] / 100;
        $af7 = $initialParameters['f7'] * $initialParameters['a'] / 100;
        $af8 = $initialParameters['f8'] * $initialParameters['a'] / 100;

        // محاسبه سطح اشغال سرپله
        $as = 25;

        // محاسبه زیر بنای مشاعات
        // مساحت لابی
        $al = ($initialParameters['a'] < 300) ? 0 : 20;

        // محاسبه زیر بنای مشاعات
        // مساحت راه پله و آسانسور
        $ap = 22;

        // محاسبه زیر بنای مشاعات
        // مساحت نورگیر در طبقات
        if ($initialParameters['a'] < 300 && $initialParameters['m'] == 1) {
            $an = 10;
        } elseif ($initialParameters['a'] < 500 && $initialParameters['m'] > 1) {
            $an = 0;
        } elseif ($initialParameters['a'] >= 500 && $initialParameters['m'] > 1) {
            $an = 10;
        } elseif ($initialParameters['a'] > 300 && $initialParameters['a'] < 500 && $initialParameters['m'] == 1) {
            $an = 20;
        } elseif ($initialParameters['a'] > 500 && $initialParameters['a'] < 1000 && $initialParameters['m'] == 1) {
            $an = 30;
        } else {
            $an = 0;
        }

        // محاسبه بالکن طبقه همکف
        $abb0 = $initialParameters['b'] * ($initialParameters['balcony1'] + $initialParameters['balcony2']) + ($lg * $initialParameters['balcony3']);
        
        // محاسبه بالکن طبقه اول الی هشتم
        $abb = $initialParameters['b'] * ($initialParameters['balcony1'] + $initialParameters['balcony2']) + ($lf1 * $initialParameters['balcony3']);

        return [
            'l' => $l,
            'lg' => $lg,
            'lf1' => $lf1,
            'lf2' => $lf2,
            'lf3' => $lf3,
            'lf4' => $lf4,
            'lf5' => $lf5,
            'lf6' => $lf6,
            'lf7' => $lf7,
            'lf8' => $lf8,
            'ab1' => $ab1,
            'ab2' => $ab2,
            'ag'=> $ag,
            'af1'=> $af1,
            'af2'=> $af2,
            'af3'=> $af3,
            'af4'=> $af4,
            'af5'=> $af5,
            'af6'=> $af6,
            'af7'=> $af7,
            'af8'=> $af8,
            'as' => $as,
            'al' => $al,
            'ap' => $ap,
            'an' => $an,
            'abb0' => $abb0,
            'abb' => $abb
        ];
    }

    // محاسبه زیر بنای قابل ساخت
    public function calculateTotalAreaASK() {

        // دریافت زیر بنا
        $area = $this->calculateArea();

        // کل زیر بنای زیر زمین اول و دوم
        $abk1 = $area['ab1'];
        $abk2 = $area['ab2'];

        // کل زیر بنای طبقه همکف
        $agk = $area['ag'] + $area['abb0'];

        // کل زیر بنای طبقه اول الی هشتم
        $afk1 = ($area['af1'] != 0) ? ($area['af1'] + $area['abb']) : 0;
        $afk2 = ($area['af2'] != 0) ? ($area['af2'] + $area['abb']) : 0;
        $afk3 = ($area['af3'] != 0) ? ($area['af3'] + $area['abb']) : 0;
        $afk4 = ($area['af4'] != 0) ? ($area['af4'] + $area['abb']) : 0;
        $afk5 = ($area['af5'] != 0) ? ($area['af5'] + $area['abb']) : 0;
        $afk6 = ($area['af6'] != 0) ? ($area['af6'] + $area['abb']) : 0;
        $afk7 = ($area['af7'] != 0) ? ($area['af7'] + $area['abb']) : 0;
        $afk8 = ($area['af8'] != 0) ? ($area['af8'] + $area['abb']) : 0;

        // جمع کل زیر بنای قابل ساخت
        $ask = $abk1 + $abk2 + $agk + $afk1 + $afk2 + $afk3 + $afk4 + $afk5 + $afk6 + $afk7 + $afk8 + $area['as'];

        return [
            'abk1' => $abk1,
            'abk2' => $abk2,
            'agk' => $agk,
            'afk1' => $afk1,
            'afk2' => $afk2,
            'afk3' => $afk3,
            'afk4' => $afk4,
            'afk5' => $afk5,
            'afk6' => $afk6,
            'afk7' => $afk7,
            'afk8' => $afk8,
            'ask' => $ask,
        ];
    }

    // محاسبه مشاعات
    public function calculateTotalAreaAMK() {

        // دریافت زیر بنا
        $area = $this->calculateArea();

        // مشاعات زیر زمین اول و دوم
        $amb1 = ($area['ab1'] != 0) ? (0.4 * $area['ab1'] + $area['ap']) : 0;
        $amb2 = ($area['ab2'] != 0) ? (0.4 * $area['ab2'] + $area['ap']) : 0;

        // محاسبه مشاعات طبقه همکف
        $amg = $area['al'] + $area['ap'] + $area['an'];
        
        // محاسبه مشاعات طبقه اول الی هشتم
        $amf1 = ($area['af1'] != 0) ? ($area['al'] + $area['ap'] + $area['an']) : 0;
        $amf2 = ($area['af2'] != 0) ? ($area['al'] + $area['ap'] + $area['an']) : 0;
        $amf3 = ($area['af3'] != 0) ? ($area['al'] + $area['ap'] + $area['an']) : 0;
        $amf4 = ($area['af4'] != 0) ? ($area['al'] + $area['ap'] + $area['an']) : 0;
        $amf5 = ($area['af5'] != 0) ? ($area['al'] + $area['ap'] + $area['an']) : 0;
        $amf6 = ($area['af6'] != 0) ? ($area['al'] + $area['ap'] + $area['an']) : 0;
        $amf7 = ($area['af7'] != 0) ? ($area['al'] + $area['ap'] + $area['an']) : 0;
        $amf8 = ($area['af8'] != 0) ? ($area['al'] + $area['ap'] + $area['an']) : 0;

        $amk = $amb1 + $amb2 + $amg + $amf1 + $amf2 + $amf3 + $amf4 + $amf5 + $amf6 + $amf7 + $amf8 + $area['as'];

        return [
            'amb1' => $amb1,
            'amb2' => $amb2,
            'amg' => $amg,
            'amf1' => $amf1,
            'amf2' => $amf2,
            'amf3' => $amf3,
            'amf4' => $amf4,
            'amf5' => $amf5,
            'amf6' => $amf6,
            'amf7' => $amf7,
            'amf8' => $amf8,
            'amk' => $amk,
        ];
    }

    // محاسبه مساحت مفید قابل فروش
    public function calculateTotalAreaAPK() {

        // دریافت زیر بنا
        $area = $this->calculateArea();

        // زیر بنای قابل ساخت
        $totalAreaASK = $this->calculateTotalAreaASK();

        // مشاعات
        $totalAreaAMK = $this->calculateTotalAreaAMK();
        
        // محاسبه زیر بنای مفید زیر زمین اول و دوم
        $apb1 = ($area['ab1'] != 0) ? ($totalAreaASK['abk1'] - $totalAreaAMK['amb1']) : 0;
        $apb2 = ($area['ab2'] != 0) ? ($totalAreaASK['abk2'] - $totalAreaAMK['amb2']) : 0;
       
        // محاسبه زیر بنای مفید طبقه همکف
        $apg = $totalAreaASK['agk'] - $totalAreaAMK['amg'] - $area['abb0'];
        
        // محاسبه زیر بنای مفید طبقه اول الی هشتم
        $apf1 = ($area['af1'] != 0) ? ($totalAreaASK['afk1'] - $totalAreaAMK['amf1']) : 0;
        $apf2 = ($area['af2'] != 0) ? ($totalAreaASK['afk2'] - $totalAreaAMK['amf2']) : 0;
        $apf3 = ($area['af3'] != 0) ? ($totalAreaASK['afk3'] - $totalAreaAMK['amf3']) : 0;
        $apf4 = ($area['af4'] != 0) ? ($totalAreaASK['afk4'] - $totalAreaAMK['amf4']) : 0;
        $apf5 = ($area['af5'] != 0) ? ($totalAreaASK['afk5'] - $totalAreaAMK['amf5']) : 0;
        $apf6 = ($area['af6'] != 0) ? ($totalAreaASK['afk6'] - $totalAreaAMK['amf6']) : 0;
        $apf7 = ($area['af7'] != 0) ? ($totalAreaASK['afk7'] - $totalAreaAMK['amf7']) : 0;
        $apf8 = ($area['af8'] != 0) ? ($totalAreaASK['afk8'] - $totalAreaAMK['amf8']) : 0;

        $apk = $apb1 + $apb2 + $apg + $apf1 + $apf2 + $apf3 + $apf4 + $apf5 + $apf6 + $apf7 + $apf8;

        return [
            'apb1' => $apb1,
            'apb2' => $apb2,
            'apg' => $apg,
            'apf1' => $apf1,
            'apf2' => $apf2,
            'apf3' => $apf3,
            'apf4' => $apf4,
            'apf5' => $apf5,
            'apf6' => $apf6,
            'apf7' => $apf7,
            'apf8' => $apf8,
            'apk' => $apk,
        ];
    }
    
    // محاسبه کل زیر بنا و هزینه ساخت 
    public function calculateConstExpenses() {
       
        // دریافت ورودی های کاربر
        $initialParameters = $this->getInitialParameters();

        // زیر بنا
        $area = $this->calculateArea();

        // زیر بنای قابل ساخت
        $totalAreaASK = $this->calculateTotalAreaASK();

        // مشاعات
        $totalAreaAMK = $this->calculateTotalAreaAMK();

        // مساحت مفید قابل فروش
        $totalAreaAPK = $this->calculateTotalAreaAPK();

        // جمع کل هزینه ساخت
        $ack = ($totalAreaASK['ask'] * $initialParameters['pc']) + $initialParameters['ps'] + $initialParameters['pk'];

        // جمع کل قیمت زمین
        $pmk = $initialParameters['pm'] * $initialParameters['a'];

        // جمع کل سرمایه گذاری
        $zsk = $ack + $pmk;

        return [
            'ack' => ceil($ack),
            'pmk' => ceil($pmk),
            'zsk' => ceil($zsk)
        ];
    }

    // نسبت منصفانه مشارکت در ساخت 
    public function calculateConstCollaborative() {
        
        // دریافت ورودی های کاربر
        $initialParameters = $this->getInitialParameters();

        // زیر بنا
        $area = $this->calculateArea();

        // زیر بنای قابل ساخت
        $totalAreaASK = $this->calculateTotalAreaASK();

        // مشاعات
        $totalAreaAMK = $this->calculateTotalAreaAMK();

        // مساحت مفید قابل فروش
        $totalAreaAPK = $this->calculateTotalAreaAPK();

        // محاسبه کل زیر بنا و هزینه ساخت 
        $constExpenses = $this->calculateConstExpenses();

        // درصد سهم شراکت منصفانه سازنده
        $sm = ($constExpenses['ack'] / $constExpenses['zsk']) * 100;

        // درصد سهم شراکت منصفانه مالک زمین 
        $zm = ($constExpenses['pmk'] / $constExpenses['zsk']) * 100;

        // کل متراژ مفید آپارتمان قابل فروش برای سازنده 
        $apsk = $totalAreaAPK['apk'] * $sm / 100;

        // کل متراژ مفید آپارتمان قابل فروش برای مالک
        $apzk = $totalAreaAPK['apk'] * $zm / 100;

        return [
            'sm' => $sm,
            'zm' => $zm,
            'apsk' => $apsk,
            'apzk' => $apzk
        ];
    }
}