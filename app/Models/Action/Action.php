<?php

namespace App\Models\Action;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Action\Ramp\RampSteep;
use App\Models\Action\Ramp\RampLength;
use Illuminate\Database\Eloquent\Model;
use App\Models\Action\Ramp\ExpansionJoint;
use App\Models\Action\Concreting\Concreting;
use App\Models\Action\Construction\Construction;
use App\Models\Action\Concreting\ColumnConcreting;
use App\Models\Action\RebarAndStirrup\RebarWeight;
use App\Models\Action\RebarAndStirrup\StirrupWeight;
use App\Models\Action\RebarAndStirrup\RebarConversion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Action\FacadeAndFlooringMaterial\Mosaic;
use App\Models\Action\BeamAndBlockRoof\BeamAndBlockRoof;
use App\Models\Action\FacadeAndFlooringMaterial\Ceramic;
use App\Models\Action\FacadeAndFlooringMaterial\BodyTile;
use App\Models\Action\FacadeAndFlooringMaterial\Cementing;
use App\Models\Action\BrickWallMasonry\BrickWallMasonryGarden;
use App\Models\Action\FacadeAndFlooringMaterial\DecorativeStone;
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

    public function rampSteep() {
        return $this->hasMany(RampSteep::class);
    }

    public function rampLength() {
        return $this->hasMany(RampLength::class);
    }

    public function expansionJoint() {
        return $this->hasMany(ExpansionJoint::class);
    }

    public function rebarWeight() {
        return $this->hasMany(RebarWeight::class);
    }

    public function stirrupWeight() {
        return $this->hasMany(StirrupWeight::class);
    }

    public function rebarConversion() {
        return $this->hasMany(RebarConversion::class);
    }

    public function concreting() {
        return $this->hasMany(Concreting::class);
    }

    public function columnConcreting() {
        return $this->hasMany(ColumnConcreting::class);
    }

    public function dececorativeStone() {
        return $this->hasMany(DecorativeStone::class);
    }

    public function bodyTile() {
        return $this->hasMany(BodyTile::class);
    }
   
    public function ceramic() {
        return $this->hasMany(Ceramic::class);
    }

    public function mosaic() {
        return $this->hasMany(Mosaic::class);
    }

    public function cementing() {
        return $this->hasMany(Cementing::class);
    }
}
