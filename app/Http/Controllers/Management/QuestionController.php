<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuestionController extends Controller
{
    /**
     * Show question management page
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function managementQuestionPage(Request $request): View|RedirectResponse
    {
        $categories = Category::all();
        $questions = Question::all();

        if (!$categories->count()) {
            $request->session()->flash('error', __('custom_session_message.flash.not_found_categories'));

            return redirect()->route('categories');
        }

        return view('private.admin.questions.index', [
            'title' => __('pages.questions'),
            'categories' => $categories,
            'questions' => $questions
        ]);
    }

    /**
     * Show create question modal
     *
     * @return View
     */
    public function addQuestionModal(): View
    {
        return view('private.admin.questions.create', [
            'categories' => Category::all(),
        ]);
    }

    /**
     * Show edit question modal
     *
     * @param Request $request
     * @return View
     */
    public function editQuestionModal(Request $request): View
    {
        $question = Question::find($request->get('id'));

        return view('private.admin.questions.edit', [
            'categories' => Category::all(),
            'question' => $question
        ]);
    }

    /**
     * Create new question for quiz
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addQuestionRequest(Request $request): JsonResponse
    {
        $question = new Question();
        $this->saveQuestion($question, $request);
        $this->createAnswers($question, $request);
        $request->session()->flash('status', __('custom_session_message.flash.new_question_added'));

        return response()->json();
    }

    /**
     * Create new question for quiz
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateQuestionRequest(Request $request): JsonResponse
    {
        $question = Question::find($request->get('id'));
        $this->saveQuestion($question, $request);

        $question->updated_by = Auth::user()->id;
        $question->save();

        foreach ($question->answers as $answer){
            $answer->delete();
            $question->answers()->detach($answer);
        }

        $this->createAnswers($question, $request);

        $request->session()->flash('status', __('custom_session_message.flash.apply'));

        return response()->json();
    }

    /**
     *  Delete question
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteQuestionRequest(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $question = Question::find($id);
        $this->deleteQuestionAndRelationship($question);

        $request->session()->flash('status', __('custom_session_message.flash.delete_question'));

        return response()->json();
    }

    /**
     * Delete selected questions
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAllQuestionRequest(Request $request): JsonResponse
    {
        $ids = $request->get('ids');
        $questions = Question::whereIn('id', $ids)->get();

        foreach ($questions as $question) {
            $this->deleteQuestionAndRelationship($question);
        }

        $request->session()->flash('status', __('custom_session_message.flash.delete_questions'));

        return response()->json();
    }

    private function deleteQuestionAndRelationship(Question $question)
    {
        foreach ($question->answers as $answer) {
            $answer->delete();
        }
        $question->tests()->detach();
        $question->answers()->detach();
        $question->delete();

        foreach (Test::all() as $test) {
            if (!$test->questions->count()) {
                $test->users()->detach();
                $test->delete();
            }
        }
    }

    private function saveQuestion(Question $question, Request $request)
    {
        $question->text = $request->get('text');
        $question->created_by = Auth::user()->id;
        $question->category_id = $request->get('category_id');

        $question->save();
    }

    private function createAnswers(Question $question, Request $request)
    {
        foreach ($request->all() as $key => $param) {
            if (preg_match('#^answer_\d#', $key)) {
                $isRight = false;
                if ($request->has('is_right_' . $key)) {
                    $isRight = true;
                }

                $answer = new Answer();
                $answer->text = $param;
                $answer->is_right = $isRight;

                $answer->save();
                $question->answers()->attach($answer);
            }
        }
    }
}
