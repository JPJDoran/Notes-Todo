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

    /**
     * Get a category by a given id
     * @param  string $id [The id of the category]
     * @return string
     */
    public function getCategory($id) {
        if (!IS_AJAX) {
            abort('404');
        }

        if (is_null($category = $this->user->Categories->find($id))) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        return response()->json(['error' => false, 'category' => $category]);
    }

    /**
     * Edit a category
     * @param  Request $request [The HTTP request]
     * @return string
     */
    public function editCategory(Request $request) {
        if (!IS_AJAX) {
            abort('404');
        }

        // Validate the data
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'categoryId' => 'required',
        ]);

        // Validation has failed so return error
        if (!$validate->passes()) {
			return response()->json(['error' => true, 'validation' => $validate->errors(), 'message' => __('todo.form-error')]);
        }

        if (is_null($category = $this->user->Categories->find($request->categoryId))) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        $category->title = $request->title;
        $category->save();

        return response()->json(['error' => false]);
    }

    /**
     * Get a list by a given id
     * @param  string $id [The id of the list]
     * @return string
     */
    public function getList($id) {
        if (!IS_AJAX) {
            abort('404');
        }

        if (is_null($list = $this->user->Lists->find($id))) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        return response()->json(['error' => false, 'list' => $list]);
    }

    /**
     * Edit a list
     * @param  Request $request [The HTTP request]
     * @return string
     */
    public function editList(Request $request) {
        if (!IS_AJAX) {
            abort('404');
        }

        // Validate the data
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'listId' => 'required',
        ]);

        // Validation has failed so return error
        if (!$validate->passes()) {
			return response()->json(['error' => true, 'validation' => $validate->errors(), 'message' => __('todo.form-error')]);
        }

        if (is_null($list = $this->user->Lists->find($request->listId))) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        $list->title = $request->title;
        $list->save();

        return response()->json(['error' => false]);
    }

    /**
     * Get an item by a given id
     * @param  string $id [The id of the item]
     * @return string
     */
    public function getItem($id) {
        if (!IS_AJAX) {
            abort('404');
        }

        if (is_null($item = TodoItem::find($id))) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        return response()->json(['error' => false, 'item' => $item]);
    }

    /**
     * Edit an item
     * @param  Request $request [The HTTP request]
     * @return string
     */
    public function editItem(Request $request) {
        if (!IS_AJAX) {
            abort('404');
        }

        // Validate the data
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'itemId' => 'required',
        ]);

        // Validation has failed so return error
        if (!$validate->passes()) {
			return response()->json(['error' => true, 'validation' => $validate->errors(), 'message' => __('todo.form-error')]);
        }

        if (is_null($item = TodoItem::find($request->itemId))) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        $item->title = $request->title;
        $item->save();

        return response()->json(['error' => false]);
    }

    /**
     * Delete a category
     * @param  Request $request [The HTTP request]
     * @return string
     */
    public function deleteCategory(Request $request) {
        if (!IS_AJAX) {
            abort('404');
        }

        if (is_null($categoryId = $request->categoryId)) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        if (is_null($category = $this->user->Categories->find($categoryId))) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        // Delete the category
        Category::destroy($categoryId);

        // Get all lists that belonged to that category
        $lists = TodoList::where('category_id', $categoryId);

        // Delete all items that belonged to the lists
        foreach ($lists as $list) {
            TodoItem::where('list_id', $list->id)->delete();
        }

        // Delete all the lists
        $lists->delete();

        return response()->json(['error' => false]);
    }

    /**
     * Delete a list
     * @param  Request $request [The HTTP request]
     * @return string
     */
    public function deleteList(Request $request) {
        if (!IS_AJAX) {
            abort('404');
        }

        if (is_null($listId = $request->listId)) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        if (is_null($list = $this->user->Lists->find($listId))) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        // Delete the list
        TodoList::destroy($listId);

        // Delete the items that belonged to the list
        TodoItem::where('list_id', $listId)->delete();

        return response()->json(['error' => false]);
    }

    /**
     * Delete an item
     * @param  Request $request [The HTTP request]
     * @return string
     */
    public function deleteItem(Request $request) {
        if (!IS_AJAX) {
            abort('404');
        }

        if (is_null($itemId = $request->itemId)) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        if (is_null($item = TodoItem::find($itemId))) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        if (is_null($list = $item->TodoList)) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        if (is_null($category = $list->Category)) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        if ($category->user_id != $this->user->id) {
            return response()->json(['error' => true, 'message' => __('todo.global-error')]);
        }

        // Delete the list
        TodoItem::destroy($itemId);

        return response()->json(['error' => false]);
    }
}
