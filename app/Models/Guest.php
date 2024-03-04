<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'guest_slug',
        'companions',
        'total_companions',
        'is_attend'
    ];

    public function comment() {
        return $this->hasMany(Comment::class);
    }
}
