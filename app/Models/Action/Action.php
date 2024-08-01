<?php

namespace App\Models\Action;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Action\Construction\Construction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Action\BeamAndBlockRoof\BeamAndBlockRoof;

class Action extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function subaction() {
        return $this->morphTo();
    }

    public function construction() {
        return $this->hasMany(Construction::class);
    }

    public function beamAndBlockRoof() {
        return $this->hasMany(BeamAndBlockRoof::class);
    }
}
