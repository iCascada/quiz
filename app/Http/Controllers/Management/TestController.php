<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use LogicException;

class TestController extends Controller
{
    /**
     * Management test page
     *
     * @return View|RedirectResponse
     */
    public function testCreatePage(Request $request): View|RedirectResponse
    {
        $categories = Category::count();

        if (!$categories) {
            $request->session()->flash('error', __('custom_session_message.flash.test_not_found_categories'));

            return redirect()->route('categories');
        }

        $questions = Question::count();

        if (!$questions) {
            $request->session()->flash('error', __('custom_session_message.flash.test_not_found_questions'));

            return redirect()->route('categories');
        }


        return view('private.admin.tests.index', [
            'categories' => Category::all(),
            'title' => __('pages.tests-create')
        ]);
    }

    /**
     * Get question with answer for preview
     *
     * @param $id
     * @return JsonResponse
     */
    public function getQuestionForPreview($id): JsonResponse
    {
        $question = Question::find($id);
        $answersArray = [];
        foreach ($question->answers as $answer) {
            $answersArray[] = $answer->text;
        }

        return response()->json([
            'questionId' =>  $question->id,
            'questionText' => $question->text,
            'answers' => $answersArray
        ]);
    }

    /**
     * Show create modal
     *
     * @return View
     */
    public function createModal(): View
    {
        return view('private.admin.tests.create', [
            'users' => User::all(),
            'departments' => Department::all(),
            'timer' => Test::$timer,
            'attempts' => Test::$attempt,
            'passedValues' => Test::$passedValue
        ]);
    }

    /**
     * Store new test
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveTest(Request $request): JsonResponse
    {
        $test = new Test();
        $user = Auth::user();
        $timer = $request->get('timer');
        $attempt = $request->get('attempt');
        $passedValue = $request->get('passed_value');

        $test->name = trim($request->get('name'));
        $test->created_by = $user->id;

        if ($timer) {
            $test->timer = $timer;
        }

        if ($attempt) {
            $test->attempt = $attempt;
        }

        if ($passedValue) {
            $test->passed_value = $passedValue;
        }

        $test->save();
        $test->questions()->attach(
            $request->get('question_ids')
        );
        $test->users()->attach(
            $request->get('user_ids')
        );

        $request->session()->flash('status', __('custom_session_message.flash.test_saved'));
        return response()->json();
    }

    /**
     * Management test page
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function testManagementPage(Request $request): View|RedirectResponse
    {
        $categories = Category::count();

        if (!$categories) {
            $request->session()->flash('error', __('custom_session_message.flash.test_not_found_categories'));

            return redirect()->route('categories');
        }

        $questions = Question::count();

        if (!$questions) {
            $request->session()->flash('error', __('custom_session_message.flash.test_not_found_questions'));

            return redirect()->route('categories');
        }

        return view('private.admin.tests.test', [
            'title' => __('pages.tests-management'),
            'tests' => Test::all()
        ]);
    }

    /**
     * Show test modal
     *
     * @param Request $request
     * @return View
     */
    public function showModal(Request $request): View
    {
        $id = $request->get('id');
        $test = Test::find($id);

        return view('private.admin.tests.show', [
            'test' => $test
        ]);
    }

    /**
     * Edit test modal
     *
     * @param Request $request
     * @return View
     */
    public function editModal(Request $request): View
    {
        $id = $request->get('id');
        $test = Test::find($id);

        return view('private.admin.tests.edit', [
            'test' => $test,
            'timer' => Test::$timer,
            'departments' => Department::all(),
            'categories' => Category::all(),
            'users' => User::all(),
            'attempts' => Test::$attempt,
            'passedValues' => Test::$passedValue
        ]);
    }

    /**
     * Delete test
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteTest(Request $request)
    {
        $id = $request->get('id');
        $test = Test::find($id);
        $this->removeTest($test);
        $request->session()->flash('status', __('custom_session_message.flash.apply'));

        return response()->json();
    }

    /**
     * Delete selected tests
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAllTests(Request $request)
    {
        $ids = $request->get('ids');
        $tests = Test::whereIn('id', $ids)->get();
        foreach ($tests as $test) {
            $this->removeTest($test);
        }

        $request->session()->flash('status', __('custom_session_message.flash.apply'));

        return response()->json();
    }

    public function updateTest(Request $request)
    {
        $test = Test::find($request->id);
        $user = Auth::user();
        $timer = (int)$request->get('timer');
        $attempt = (int)$request->get('attempt');
        $passedValue = (int)$request->get('passed_value');

        $test->name = trim($request->get('name'));
        $test->updated_by = $user->id;
        $test->timer = $timer ?: null;
        $test->attempt = $attempt ?: null;
        $test->passed_value = $passedValue ?: null;

        $test->save();

        $test->questions()->sync(
            $request->get('question_ids')
        );
        $test->users()->sync(
            $request->get('user_ids')
        );

        $request->session()->flash('status', __('custom_session_message.flash.apply'));
        return response()->json();
    }

    public function testActualPage()
    {
        $user = Auth::user();

        return view('public.tests.actual', [
            'title' => __('pages.tests-actual'),
            'actualTests' => Test::getActualTest()
        ]);
    }

    public function testPassedPage()
    {
        $user = Auth::user();

        return view('public.tests.passed', [
            'title' => __('pages.tests-passed'),
            'passedTests' => Test::getPassedTest()
        ]);
    }

    /**
     *  Lock test
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function lockTest(Request $request)
    {
        $id = $request->get('id');
        $test = Test::find($id);
        $test->is_actual = false;
        $test->save();

        $request->session()->flash('status', __('custom_session_message.flash.apply'));
        return response()->json();
    }

    /**
     * Unlock test
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function unlockTest(Request $request)
    {
        $id = $request->get('id');
        $test = Test::find($id);
        $test->is_actual = true;
        $test->save();

        $request->session()->flash('status', __('custom_session_message.flash.apply'));
        return response()->json();
    }

    public function passedResultModal(Request $request)
    {
        $id = $request->get('id');
        $user = Auth::user();
        $test = $user->tests()->where('tests.id', $id)->first();

        return view('public.tests.result', [
            'test' => $test
        ]);
    }

    public function processTestModal(Request $request)
    {
        $id = $request->get('id');
        $test = Test::find($id);

        return view('public.tests.process', [
            'test' => $test
        ]);
    }

    public function incAttempt(Request $request)
    {
        $id = $request->get('id');
        $test = Test::find($id);

        $userId = Auth::user()->id;
        $currentAttempts = $test->user()->pivot->attempt;

        if ($currentAttempts == $test->attempt) {
            throw new LogicException("Attempts is over for current test");
        }

        if (is_null($currentAttempts)) {
            $userAttempt = 1;
        }else{
            $userAttempt = ++$currentAttempts;
        }

        $test->users()->sync([$userId => ['attempt' => $userAttempt]]);
    }

    public function checkTest(Request $request): JsonResponse
    {
        /** @var Test $test */
        $test = Test::find($request->get('id'));

        $test
            ->loadMap()
            ->calculateResult(
                $request->except('id', '_token')
            );

        $pastResult = $test->user()->pivot->result;
        $currentResult = $test->getResult();

        if (!$pastResult || $pastResult < $currentResult) {
            $test->users()->sync([Auth::user()->id => ['result' => $currentResult]]);
        }

        return Response::json();
    }

    /**
     * Remove relationships
     *
     * @param Test $test
     * @return void
     */
    private function removeTest(Test $test)
    {
        $test->users()->detach();
        $test->questions()->detach();
        $test->delete();
    }
}
