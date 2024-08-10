<?php

namespace App\Models\Action;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Action\Construction\Construction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Action\BeamAndBlockRoof\BeamAndBlockRoof;
use App\Models\Action\BrickWallMasonry\BrickWallMasonryGarden;
use App\Models\Action\BrickWallMasonry\BrickWallMasonryPartition;
use App\Models\Action\BrickWallMasonry\BrickWallMasonryPressedBrick;
use App\Models\Action\BrickWallMasonry\BrickWallMasonryApartmentBlock;


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

    public function brickWallMasonryApartmentBlock() {
        return $this->hasMany(BrickWallMasonryApartmentBlock::class);
    }

    public function brickWallMasonryGarden() {
        return $this->hasMany(BrickWallMasonryGarden::class);
    }

    public function brickWallMasonryPartition() {
        return $this->hasMany(BrickWallMasonryPartition::class);
    }

    public function brickWallMasonryPressedBrick() {
        return $this->hasMany(BrickWallMasonryPressedBrick::class);
    }
}
