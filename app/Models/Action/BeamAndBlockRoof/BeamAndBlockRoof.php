<?php

namespace App\Models\Action\BeamAndBlockRoof;

use App\Models\User;
use App\Models\Action\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BeamAndBlockRoof extends Model
{
    protected $guarded = [];

    public function action() {
        return $this->belongsTo(Action::class);
    }
}
