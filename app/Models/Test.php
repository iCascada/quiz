<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Test extends BaseModel
{
    public static array $timer = [15, 30, 45, 60];
    public static array $attempt = [1, 3, 5, 10];
    public static array $passedValue = [50, 25, 75, 100];
    private int $result = 0;
    private float $average = 0;
    private array $loadedMap = [];

    protected $guarded = [];

    public function getResult(): int
    {
        return $this->result;
    }

    public function loadMap(): self
    {
        $resultMap = [];
        $questions = $this->questions;

        foreach ($questions as $question) {
            foreach ($question->answers as $answer) {
                if ($answer->is_right) {
                    $resultMap[$question->id][] = $answer->id;
                }
            }
        }

        $this->loadedMap = $resultMap;
        $this->average = 100 / $questions->count();

        return $this;
    }

    public static function getActualTest()
    {
        $user = Auth::user();
        $result = collect();
        $tests = $user->tests;

        foreach ($tests as $test) {
            if ($test->is_actual &&
                $test->pivot->attempt < $test->attempt &&
                $test->pivot->result < $test->passed_value
            ) {
                $result->push($test);
            }
        }

        return $result;
    }

    public static function getPassedTest()
    {
        $user = Auth::user();
        $result = collect();
        $tests = $user->tests;

        foreach ($tests as $test) {
            if (!$test->is_actual ||
                $test->pivot->attempt >= $test->attempt ||
                $test->pivot->result >= $test->passed_value
            ) {
                $result->push($test);
            }
        }

        return $result;
    }

    public function calculateResult(array $dataset): self
    {
        $preparedResult = $this->prepareDataset($dataset);
        $tempResult = 0.0;

        foreach ($preparedResult as $questionId => $answers) {
            if (count($this->loadedMap[$questionId])) {
                $diffFromLoadedMap = array_diff(
                    $this->loadedMap[$questionId],
                    $answers
                );

                if (count($diffFromLoadedMap)) {
                    continue;
                }

                $diffFromUserResult = array_diff(
                    $answers,
                    $this->loadedMap[$questionId]
                );

                if (count($diffFromUserResult)) {
                    continue;
                }

                $tempResult += $this->average;
            }
        }

        $this->result = (int)round($tempResult, 0);

        if ($this->result > 100) {
            $this->result = 100;
        }

        return $this;
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('result');
    }

    public function user()
    {
        return ($this->belongsToMany(User::class)
            ->wherePivot('user_id', Auth::user()->id)
            ->withPivot('attempt','result'))
            ->first();
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function prepareDataset(array $dataset): array
    {
        $result = [];

        foreach ($dataset as $key => $data) {
            $explode = explode('-', $key);

            $questionId = $explode[0];
            $answerId = $explode[1];

            $result[$questionId][] = $answerId;
        }

        return $result;
    }
}
