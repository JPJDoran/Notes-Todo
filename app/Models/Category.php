<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'user_id',
        'title',
        'active'
    ];

    public function User() {
        return $this->belongsTo('App\Models\User');
    }

    public function Lists() {
        return $this->hasMany('App\Models\TodoList', 'category_id', 'id');
    }

    public function Items() {
        return $this->hasManyThrough(
            'App\Models\TodoItem',
            'App\Models\TodoList',
            'category_id', // Foreign key on lists table
            'list_id', // Foreign key on items table
            'id', // Local key on categories table
            'id' // Local key on lists table
        );
    }
}
