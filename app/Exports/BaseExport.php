<?php

namespace App\Exports;

use App\Models\BaseModel;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class BaseExport
{
    protected BaseModel $model;

    public abstract function setCellRange(): array;
    public abstract function setTitle(): string;

    public array $styled = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'grey']
            ],
        ]
    ];

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle($this->setTitle());
        $sheet
            ->getStyle($this->getRange())
            ->getFont()
            ->setBold(true);

        $count = $this->model->count();

        if ($count) {
            if ($count == 1) {
                $cellCount = 2;
            }else {
                $cellCount = $count + 1;
            }
            $cells = range(1, $cellCount);
            foreach ($cells as $cell) {
                $this->applyStyle($sheet, $cell);
            }
        }
    }

    protected function applyStyle(Worksheet $sheet, $cell)
    {
        $sheet->getStyle($this->getRange($cell))->applyFromArray($this->styled);
    }

    /**
     * @param int|null $number
     * @return string
     */
    protected function getRange(?int $number = null): string
    {
        $number = $number ?: 1;
        $range = $this->setCellRange();
        $result = '';

        foreach ($range as $cell) {
            $result .= "$cell" . $number . ":";
        }

        return substr($result, 0 , -1);
    }

}
