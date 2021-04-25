<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileImage extends Model
{
    use HasFactory;

    protected $table = 'profile_images';

    protected $fillable = [
        'user_id',
        'img'
    ];

    public function User() {
        return $this->belongsTo('App\Models\User');
    }
}
