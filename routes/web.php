<?php

use App\Http\Controllers\Management\AnalyzeController;
use App\Http\Controllers\Management\CategoryController;
use App\Http\Controllers\Management\ChartController;
use App\Http\Controllers\Management\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Management\QuestionController;
use App\Http\Controllers\Management\TestController;
use App\Http\Controllers\Management\UserController;
use App\Http\Controllers\Management\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServicePАrovider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * AuthRoutes
 */
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login-page');
    });

    Route::get('/login', [AuthController::class, 'loginPage'])
        ->name('login-page');

    Route::get('/register', [AuthController::class, 'registerPage'])
        ->name('register-page');

    Route::get('/forgot-password', [AuthController::class, 'forgotPasswordPage'])
        ->name('password.page');

    Route::post('/forgot-password', [AuthController::class, 'forgotPasswordRequest'])
        ->name('password.email');

    Route::get('/reset-password/{email}/{token}', [AuthController::class, 'forgotPasswordReset'])
        ->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'forgotPasswordUpdate'])
        ->name('password.update');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/email/verify', [AuthController::class, 'noticeEmail'])
        ->name('verification.notice');

    Route::middleware('signed')
        ->get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->name('verification.verify');
});


/**
 * ADMIN
 */
Route::middleware('auth')
    ->prefix('management')
    ->group(callback: function () {

        Route::get('dashboard', [DashboardController::class, 'dashboardPage'])
            ->name('dashboard-page');

        Route::prefix('user')->group(callback: function () {
            Route::get('/', [UserController::class, 'accountManagementPage'])
                ->name('account-page');
            Route::get('/verify-email', [UserController::class, 'verifyAuthUserEmail'])
                ->name('auth.verify.email');
            Route::get('/reset-password', [UserController::class, 'resetPassword'])
                ->name('auth.reset.password');
            Route::post('/reset-password', [UserController::class, 'resetPasswordConfirm'])
                ->name('auth.reset.password.request');
            Route::post('/update-info', [UserController::class, 'updateUserInfo'])
                ->name('auth.update.info');
        });

        Route::middleware(['only_admin'])->group(callback: function () {
            Route::prefix('users')->group(callback: function () {
                Route::get('/', [UsersController::class, 'UsersManagementPage'])
                    ->name('users-page');
                Route::post('/edit-modal', [UsersController::class, 'EditPage'])
                    ->name('users-edit-page');
                Route::post('/update', [UsersController::class, 'update'])
                    ->name('users-update');
                Route::post('/access', [UsersController::class, 'accessManagement'])
                    ->name('access-management');
            });

            Route::prefix('analyze')->group(function() {
                Route::get('/', [AnalyzeController::class, 'analyzePage'])
                    ->name('analyze-page');
                Route::get('/to-pdf-category', [AnalyzeController::class, 'toPDFCategory'])
                    ->name('to-pdf-category');
                Route::get('/to-excel-category', [AnalyzeController::class, 'toExcelCategory'])
                    ->name('to-excel-category');
                Route::get('/to-pdf-question', [AnalyzeController::class, 'toPDFQuestion'])
                    ->name('to-pdf-question');
                Route::get('/to-excel-question', [AnalyzeController::class, 'toExcelQuestion'])
                    ->name('to-excel-question');
                Route::get('/to-pdf-test', [AnalyzeController::class, 'toPDFTest'])
                    ->name('to-pdf-test');
                Route::get('/to-excel-test', [AnalyzeController::class, 'toExcelTest'])
                    ->name('to-excel-test');
                Route::get('/to-pdf-user', [AnalyzeController::class, 'toPDFUser'])
                    ->name('to-pdf-user');
                Route::get('/to-pdf-user-test', [AnalyzeController::class, 'toPDFUserTest'])
                    ->name('to-pdf-user-test');
            });

            Route::prefix('chart')->group(callback: function() {
                Route::get('/', [ChartController::class, 'chartPage'])
                    ->name('chart-page');
                Route::get('/show-charts-by-user-id/{id?}', [ChartController::class, 'showChartsByUserId'])
                    ->name('show-charts-by-user-id');
            });
        });

        Route::prefix('test')
            ->group(callback: function () {
                Route::middleware(['private'])->group(function() {
                    Route::get('management-category', [CategoryController::class, 'managementCategoryPage'])
                        ->name('categories');
                    Route::post('add-category-modal', [CategoryController::class, 'addCategoryModal'])
                        ->name('add-category-modal');
                    Route::post('add-category-request', [CategoryController::class, 'addCategoryRequest'])
                        ->name('add-category-request');
                    Route::post('edit-category-modal', [CategoryController::class, 'editCategoryModal'])
                        ->name('edit-category-modal');
                    Route::patch('update-category-request', [CategoryController::class, 'updateCategoryRequest'])
                        ->name('update-category-request');
                    Route::delete('delete-category-request', [CategoryController::class, 'deleteCategoryRequest'])
                        ->name('delete-category-request');
                    Route::delete('delete-all-category-request', [CategoryController::class, 'deleteAllCategoryRequest'])
                        ->name('delete-all-category-request');

                    Route::get('management-question', [QuestionController::class, 'managementQuestionPage'])
                        ->name('questions');
                    Route::post('add-question-modal', [QuestionController::class, 'addQuestionModal'])
                        ->name('add-question-modal');
                    Route::post('add-question-request', [QuestionController::class, 'addQuestionRequest'])
                        ->name('add-question-request');
                    Route::post('edit-question-modal', [QuestionController::class, 'editQuestionModal'])
                        ->name('edit-question-modal');
                    Route::patch('update-question-request', [QuestionController::class, 'updateQuestionRequest'])
                        ->name('update-question-request');
                    Route::delete('delete-question-request', [QuestionController::class, 'deleteQuestionRequest'])
                        ->name('delete-question-request');
                    Route::delete('delete-all-question-request', [QuestionController::class, 'deleteAllQuestionRequest'])
                        ->name('delete-all-question-request');

                    Route::get('/', [TestController::class, 'testCreatePage'])
                        ->name('tests');
                    Route::get('question/{id}', [TestController::class, 'getQuestionForPreview'])
                        ->name('get-question');
                    Route::post('create-test-modal', [TestController::class, 'createModal'])
                        ->name('create-test-modal');
                    Route::post('save-test', [TestController::class, 'saveTest'])
                        ->name('save-test');
                    Route::get('management-test', [TestController::class, 'testManagementPage'])
                        ->name('management-test');
                    Route::post('show-test-modal', [TestController::class, 'showModal'])
                        ->name('show-test-modal');
                    Route::post('edit-test-modal', [TestController::class, 'editModal'])
                        ->name('edit-test-modal');
                    Route::delete('delete-test', [TestController::class, 'deleteTest'])
                        ->name('delete-test');
                    Route::patch('update-test', [TestController::class, 'updateTest'])
                        ->name('update-test-request');
                    Route::delete('delete-all-test', [TestController::class, 'deleteAllTests'])
                        ->name('delete-all-tests');
                    Route::post('unlock-test', [TestController::class, 'unlockTest'])
                        ->name('unlock-test');
                    Route::post('lock-test', [TestController::class, 'lockTest'])
                        ->name('lock-test');
                });

                Route::get('passed', [TestController::class, 'testPassedPage'])
                    ->name('tests-passed');
                Route::get('passed-result', [TestController::class, 'passedResultModal'])
                    ->name('passed-result-modal');
                Route::get('process-test', [TestController::class, 'processTestModal'])
                    ->name('process-test-modal');
                Route::get('actual', [TestController::class, 'testActualPage'])
                    ->name('tests-actual');
                Route::patch('actual-attempt-inc', [TestController::class, 'incAttempt'])
                    ->name('actual-attempt-inc');
                Route::post('check-test', [TestController::class, 'checkTest'])
                    ->name('check-test');
            });
    });
