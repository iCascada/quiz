<?php

namespace App\Http\Controllers\Management;

use App\Exports\CategoryExport;
use App\Exports\QuestionExport;
use App\Exports\TestExport;
use App\Models\Category;
use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse as BinaryFileResponseAlias;

class AnalyzeController
{
    /**
     * Analyze page
     *
     * @return View
     */
    public function analyzePage(): View
    {
        return view('private.admin.analyze.index', [
            'title' => __('pages.analyze'),
            'categoryCount' => Category::count(),
            'questionCount' => Question::count(),
            'testCount' => Test::count(),
        ]);
    }

    /**
     * PDF category file
     *
     * @return Response
     */
    public function toPDFCategory(): Response
    {
        $filename = __('files.category');

        return Pdf::loadView('pdf.category',
            [
                'categories' => Category::all(),
                'filename' => $filename
            ]
        )
            ->download($filename . '.pdf');
    }

    /**
     * Excel category file
     *
     * @return BinaryFileResponseAlias
     */
    public function toExcelCategory()
    {
        return Excel::download(
            new CategoryExport(new Category()), 'category.xlsx'
        );
    }

    /**
     * PDF question file
     *
     * @return Response
     */
    public function toPDFQuestion(): Response
    {
        $filename = __('files.question');
        $categories = Category::all();
        $questions = Question::all();

        return Pdf::loadView('pdf.question',
            [
                'categories' => $categories,
                'questions' => $questions,
                'filename' => $filename,
                'info' => 800
            ]
        )
            ->setPaper('a4', 'landscape')
            ->download($filename . '.pdf');
    }

    /**
     * Excel question file
     *
     * @return BinaryFileResponseAlias
     */
    public function toExcelQuestion()
    {
        return Excel::download(
            new QuestionExport(new Question()), 'question.xlsx'
        );
    }

    /**
     * PDF test file
     *
     * @return Response
     */
    public function toPDFTest(): Response
    {
        $filename = __('files.test');

        return Pdf::loadView('pdf.test',
            [
                'tests' => Test::all(),
                'filename' => $filename,
                'info' => 800
            ]
        )
            ->setPaper('a4', 'landscape')
            ->download($filename . '.pdf');
    }

    /**
     * Excel test file
     *
     * @return BinaryFileResponseAlias
     */
    public function toExcelTest(): BinaryFileResponseAlias
    {
        return Excel::download(
            new TestExport(new Test()), 'test.xlsx'
        );
    }

    /**
     * PDF user test info file
     *
     * @return Response
     */
    public function toPDFUser(): Response
    {
        $filename = __('files.user');

        return Pdf::loadView('pdf.user',
            [
                'tests' => Test::all(),
                'filename' => $filename,
                'info' => 800
            ]
        )
            ->setPaper('a4', 'landscape')
            ->download($filename . '.pdf');
    }

    /**
     * PDF user test result file
     *
     * @return Response
     */
    public function toPDFUserTest(): Response
    {
        $filename = __('files.user');

        return Pdf::loadView('pdf.user-test',
            [
                'users' => User::all(),
                'filename' => $filename,
                'info' => 800
            ]
        )
            ->setPaper('a4', 'landscape')
            ->download($filename . '.pdf');
    }

}
