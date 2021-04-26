<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
    use HasFactory;

    protected $table = 'lists';

    protected $fillable = [
        'category_id',
        'title',
        'active'
    ];

    public function Category() {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }

    public function Items() {
        return $this->hasMany('App\Models\TodoItem', 'list_id', 'id');
    }
}
