<?php

namespace App\Models\Action\Construction;

use App\Models\User;
use App\Models\Action\Action;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Database\Eloquent\Model;
use App\Models\Action\Construction\ConstructionFloor;
use App\Models\Action\Construction\ConstructionPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Action\Construction\ConstructionBalcony;
use App\Models\Action\Construction\ConstructionBasement;

class Construction extends Model
{
    protected $guarded = [];

    // Mutator to trim all columns before saving
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = Purify::clean(trim($value));
    }

    public function action() {
        return $this->belongsTo(Action::class);
    }

    public function constructionBasements() {
        return $this->hasOne(ConstructionBasement::class);
    }

    public function constructionFloors() {
        return $this->hasOne(ConstructionFloor::class);
    }

    public function constructionBalconies() {
        return $this->hasOne(ConstructionBalcony::class);
    }

    public function constructionPrices() {
        return $this->hasOne(ConstructionPrice::class);
    }
}
