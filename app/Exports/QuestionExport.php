<?php

namespace App\Exports;

use App\Models\BaseModel;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Contracts\View\View;
use JetBrains\PhpStorm\ArrayShape;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;

class QuestionExport extends BaseExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
{

    public function __construct(BaseModel $model)
    {
        $this->model = $model;
    }

    public function view(): View
    {
        return view('excel.question', [
            'categories' => Category::all(),
            'questions' => Question::all()
        ]);
    }

    public function setTitle(): string
    {
        return 'Question';
    }

    public function setCellRange(): array
    {
        return ['A', 'H'];
    }

    #[ArrayShape(['A' => "int", 'B' => "int", 'C' => "int", 'D' => "int"])]
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,
            'D' => 50
        ];
    }

    public function applyStyle($sheet, $cell)
    {
        $sheet->getStyle($this->getRange($cell))->applyFromArray($this->styled);
        if ($cell !== 1) {
            $sheet->getRowDimension($cell)->setRowHeight(125);
        }
    }
}
