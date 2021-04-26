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
    public function index() {
        $categories = $this->user->Categories;
        return view('index', compact('categories'));
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
     * Toggle an item's status
     * @param  Request $request [The HTTP request]
     * @return string
     */
    public function addItem(Request $request) {
        if (!IS_AJAX) {
            abort('404');
        }

        // Validate the data
        $validate = Validator::make($request->all(), [
            'listId'    => 'required|max:255|string',
            'title'     => 'required'
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
}
