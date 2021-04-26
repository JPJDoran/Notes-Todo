<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

// Models
use App\Models\Category;
use App\Models\TodoList;
use App\Models\TodoItem;

class GenerateExampleList
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $categoryId = Category::create([
            'user_id' => $event->user->id,
            'title' => __('categories.first-category-title'),
        ])->id;

        $listId = TodoList::create([
            'category_id' => $categoryId,
            'title' => __('lists.first-list')
        ])->id;

        TodoItem::create([
            'list_id' => $listId,
            'title' => __('items.first-item')
        ]);
    }
}
