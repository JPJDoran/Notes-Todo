<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoItem extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'list_id',
        'title',
        'done'
    ];

    public function TodoList() {
        return $this->belongsTo('App\Models\TodoList', 'list_id', 'id');
    }
}
