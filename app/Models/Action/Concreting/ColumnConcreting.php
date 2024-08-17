<?php

namespace App\Models\Action\Concreting;

use App\Models\Action\Action;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Database\Eloquent\Model;

class ColumnConcreting extends Model
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
