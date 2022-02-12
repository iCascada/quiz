<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function dashboardPage(): View
    {
        $user = Auth::user();

        return view('public.main.index', [
            'title' => app('main-title'),
            'categoryCount' => Category::count(),
            'questionCount' => Question::count(),
            'answerCount' => Answer::count(),
            'userCount' => User::count(),
            'testCount' => Test::count(),
            'passedTestCount' => $user->tests()->where('is_actual', false)->count(),
            'actualTestCount' => $user->tests()->where('is_actual', true)->count(),
        ]);
    }
}
