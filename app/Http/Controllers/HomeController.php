<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

// Models
use App\Models\User;
use App\Models\Category;
use App\Models\TodoList;
use App\Models\TodoItem;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->user = User::find(Auth::id());
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($categoryId = null) {
        $categories = $this->user->Categories;
        $category = $categories->first();
        $lists = null;

        if (!is_null($categoryId)) {
            $category = $categories->find($categoryId);
        }

        if (is_null($category)) {
            abort('404');
        }

        $lists = $category->Lists;
        $listId = $category->Lists->first() ? $category->Lists->first()->id : 0;
        $chosenCategory = $categoryId ?? $categories->first()->id ?? 0;
        $listsPartial = view('todo/partials/categoryLists', compact('lists', 'listId'))->render();

        return view('index', compact('categories', 'listsPartial', 'chosenCategory'));
    }

    /**
     * Toggle an item's status
     * @param  Request $request [The HTTP request]
     * @return string
     */
    public function toggleItemCompleteStatus(Request $request) {
        if (!IS_AJAX) {
            abort('404');
        }

        // No id passed
        if (is_null($id = $request->id) || is_null($done = $request->done)) {
            return response()->json(['error' => true]);
        }

        // No item found
        if (is_null($item = TodoItem::find($id))) {
            return response()->json(['error' => true]);
        }

        // Update the item
        $item->done = $done;
        $item->save();

        return response()->json(['error' => false]);
    }

    /**
     * Add a new item
     * @param  Request $request [The HTTP request]
     * @return string
     */
    public function addItem(Request $request) {
        if (!IS_AJAX) {
            abort('404');
        }

        // Validate the data
        $validate = Validator::make($request->all(), [
            'listId'    => 'required',
            'title'     => 'required|string|max:255',
        ]);

        // Validation has failed so return error
        if (!$validate->passes()) {
			return response()->json(['error' => true, 'validation' => $validate->errors(), 'message' => __('todo.form-error')]);
        }

        $insertId = TodoItem::create([
            'list_id' => $request->listId,
            'title' => $request->title
        ])->id;

        return response()->json(['error' => $insertId <= 0]);
    }

    /**
     * Get all items for a list
     * @param  Request $request [The HTTP request]
     * @return string
     */
    public function getListItems(Request $request) {
        if (!IS_AJAX) {
            abort('404');
        }

        // No id passed
        if (is_null($listId = $request->listId)) {
            return response()->json(['error' => true]);
        }

        // No list found
        if (is_null($list = ToDoList::find($listId))) {
            return response()->json(['error' => true]);
        }

        return response()->json(['error' => false, 'html' => view('todo/partials/listItems', compact('list'))->render()]);
    }

    /**
     * Add a new list
     * @param  Request $request [The HTTP request]
     * @return string
     */
    public function addList(Request $request) {
        if (!IS_AJAX) {
            abort('404');
        }

        // Validate the data
        $validate = Validator::make($request->all(), [
            'title'         => 'required|string|max:255',
            'categoryId'    => 'required',
        ]);

        // Validation has failed so return error
        if (!$validate->passes()) {
			return response()->json(['error' => true, 'validation' => $validate->errors(), 'message' => __('todo.form-error')]);
        }

        $insertId = TodoList::create([
            'category_id' => $request->categoryId,
            'title' => $request->title
        ])->id;

        return response()->json(['error' => $insertId <= 0, 'id' => $insertId]);
    }

    public function getCategoryLists(Request $request) {
        if (!IS_AJAX) {
            abort('404');
        }

        // No id passed
        if (is_null($categoryId = $request->categoryId)) {
            return response()->json(['error' => true]);
        }

        // No category found
        if (is_null($category = Category::find($categoryId))) {
            return response()->json(['error' => true]);
        }

        // Set default list if not passed
        if (is_null($listId = $request->listId)) {
            $listId = $category->Lists->first()->id;
        }

        $lists = $category->Lists;

        return response()->json(['error' => false, 'html' => view('todo/partials/categoryLists', compact('lists', 'listId'))->render()]);
    }

    /**
     * Add a new category
     * @param  Request $request [The HTTP request]
     * @return string
     */
    public function addCategory(Request $request) {
        if (!IS_AJAX) {
            abort('404');
        }

        // Validate the data
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        // Validation has failed so return error
        if (!$validate->passes()) {
			return response()->json(['error' => true, 'validation' => $validate->errors(), 'message' => __('todo.form-error')]);
        }

        $insertId = Category::create([
            'title' => $request->title,
            'user_id' => $this->user->id,
        ])->id;

        return response()->json(['error' => $insertId <= 0, 'id' => $insertId]);
    }
}
