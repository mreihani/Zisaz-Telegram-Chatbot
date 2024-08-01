<?php

namespace App\Models\Action\Construction;

use App\Models\User;
use App\Models\Action\Action;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Database\Eloquent\Model;
use App\Models\Action\Construction\Construction;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConstructionFloor extends Model
{
    protected $guarded = [];

    // Mutator to trim all columns before saving
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = Purify::clean(trim($value));
    }

    public function construction() {
        return $this->belongsTo(Construction::class);
    }
}
