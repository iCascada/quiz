<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Test;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Management category page
     *
     * @return View
     */
    public function managementCategoryPage(): View
    {
        return view('private.admin.categories.index', [
            'title' => __('pages.categories'),
            'categories' => Category::all()
        ]);
    }

    /**
     * Show add category modal
     *
     * @return View
     */
    public function addCategoryModal(): View
    {
        return view('private.admin.categories.create');
    }

    /**
     * Show edit category modal
     *
     * @param Request $request
     * @return View
     */
    public function editCategoryModal(Request $request): View
    {
        $id = $request->get('id');

        return view('private.admin.categories.edit', [
            'category' => Category::find($id),
        ]);
    }

    /**
     * Create new category
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addCategoryRequest(Request $request)
    {
        $category = new Category();

        $category->name = $request->get('name');
        $category->note = $request->get('note');
        $category->created_by = Auth::user()->id;
        $category->save();

        $request->session()->flash('status', __('custom_session_message.flash.add_category'));
        return response()->json();
    }

    /**
     * Update category
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateCategoryRequest(Request $request)
    {
        $category = Category::find($request->get('id'));
        $category->name = $request->get('name');
        $category->note = $request->get('note');
        $category->updated_by = Auth::user()->id;
        $category->save();

        $request->session()->flash('status', __('custom_session_message.flash.apply'));
        return response()->json();
    }

    /**
     * Delete selected category
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteCategoryRequest(Request $request)
    {
        $category = Category::find($request->get('id'));
        $this->deleteRelationship($category);

        $request->session()->flash('status', __('custom_session_message.flash.delete_category'));
        return response()->json();
    }

    public function deleteAllCategoryRequest(Request $request)
    {
        $ids = $request->get('ids');
        $categories = Category::whereIn('id', $ids)->get();

        foreach ($categories as $category) {
            $this->deleteRelationship($category);
        }

        $request->session()->flash('status', __('custom_session_message.flash.delete_categories'));
        return response()->json();
    }

    private function deleteRelationship(Category $category)
    {
        $questions = $category->questions;

        if ($questions->count()) {
            foreach ($questions as $question) {
                foreach ($question->answers as $answer) {
                    $answer->delete();
                }
                $question->answers()->detach();
                $question->tests()->detach();
            }
        }

        $category->delete();

        foreach (Test::all() as $test) {
            if (!$test->questions->count()) {
                $test->users()->detach();
                $test->delete();
            }
        }
    }
}
