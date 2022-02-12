<?php

namespace App\Exports;

use App\Models\BaseModel;
use App\Models\Test;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;

class TestExport extends BaseExport implements FromView, ShouldAutoSize, WithStyles
{

    public function __construct(BaseModel $model)
    {
        $this->model = $model;
    }

    public function view(): View
    {
        return view('excel.test', [
            'tests' => Test::all(),
        ]);
    }

    public function setTitle(): string
    {
        return 'Test';
    }

    public function setCellRange(): array
    {
        return ['A', 'I'];
    }
}
