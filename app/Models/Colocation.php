<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model {

    public function memberships() {
        return $this->hasMany(Membership::class);
    }

    public function owner() {
        return $this->belongsTo(User::class , 'owner_id');
    }

    protected $fillable = [
        'title',
        'description',
        'owner_id',
        'status'
    ];

    public function invitation() {
        return $this->hasMany(Invitation::class);
    }

}
