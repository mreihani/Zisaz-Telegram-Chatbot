<?php

namespace App\Models\Action\BrickWallMasonry;

use App\Models\User;
use App\Models\Action\Action;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BrickWallMasonryPressedBrick extends Model
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
}