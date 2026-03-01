<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'colocation_id',
        'user_id',
        'title',
        'amount',
        'date',
        'category_id'
    ];

    public function colocation() {
        return $this->belongsTo(Colocation::class);
    }

    public function payeur() {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function participants() {
        return $this->belongsToMany(User::class);
    }


}
