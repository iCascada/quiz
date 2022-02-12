<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class ChartController extends Controller
{
    public function chartPage(): View
    {
        return view('private.admin.chart.index', [
            'title' => __('pages.chart'),
            'departments' => Department::all(),
        ]);
    }

    public function showChartsByUserId($id): JsonResponse
    {
        $user = User::find($id);
        $result = [];

        foreach ($user->tests as $test) {
            $result[$test->id][] = $test->name;
            $result[$test->id][] = $test->pivot->result;
        }

        return Response::json(['data' => $result]);
    }
}
