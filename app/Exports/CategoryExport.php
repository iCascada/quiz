<?php

namespace App\Exports;

use App\Models\BaseModel;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;

class CategoryExport extends BaseExport implements FromView, ShouldAutoSize, WithStyles
{

    public function __construct(BaseModel $model)
    {
        $this->model = $model;
    }

    public function view(): View
    {
        return view('excel.category', [
            'categories' => Category::all()
        ]);
    }

    public function setTitle(): string
    {
        return 'Category';
    }

    public function setCellRange(): array
    {
        return ['A', 'F'];
    }
}
