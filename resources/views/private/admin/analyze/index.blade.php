@extends('dashboard')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="nav-icon fa fa-files-o" aria-hidden="true"></i>
                        {{ __('pages.analyze') }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a
                                href="{{ route('analyze-page') }}"
                            >
                                {{ app('main-title') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('pages.analyze') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card p-3">
            <div class="container-fluid">


                @if($categoryCount)
                    <div class="d-flex justify-content-center spacing-15">
                        <h6>
                            Отчеты по категориям
                        </h6>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-start flex-wrap spacing-35">
                        <div class="analyze">
                            <div>
                                <a href="{{ route('to-pdf-category') }}">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                </a>
                                <span class="inner-text">
                                    <b>
                                        Категории.pdf
                                    </b>
                                </span>
                            </div>
                        </div>
                        <div class="analyze">
                            <div>
                                <a href="{{ route('to-excel-category') }}">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                </a>
                                <span class="inner-text">
                                    <b>
                                        Категории.xls
                                    </b>
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
                @if($questionCount)
                    <div class="mt-5 d-flex justify-content-center spacing-15">
                        <h6>
                            Отчеты по тестовым вопросам
                        </h6>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-start flex-wrap spacing-35">
                        <div class="analyze">
                            <div>
                                <a href="{{ route('to-pdf-question') }}">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    <br>
                                </a>
                                <span class="inner-text">
                                    <b>
                                        Вопросы.pdf
                                    </b>
                                </span>
                            </div>
                        </div>
                        <div class="analyze">
                            <div>
                                <a href="{{ route('to-excel-question') }}">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                </a>
                                <span class="inner-text">
                                    <b>
                                        Вопросы.xsl
                                    </b>
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                @if($testCount)
                    <div class="mt-5 d-flex justify-content-center spacing-15">
                        <h6>
                            Отчеты по тестам
                        </h6>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-start flex-wrap spacing-35">
                        <div class="analyze">
                            <div>
                                <a href="{{ route('to-pdf-test') }}">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    <br>
                                </a>
                                <span class="inner-text">
                                    <b>
                                        Тесты.pdf
                                    </b>
                                </span>
                            </div>
                        </div>
                        <div class="analyze">
                            <div>
                                <a href="{{ route('to-excel-test') }}">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                </a>
                                <span class="inner-text">
                                    <b>
                                        Тесты.xsl
                                    </b>
                                </span>
                            </div>
                        </div>
                        <div class="analyze">
                            <div>
                                <a href="{{ route('to-pdf-user') }}">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    <br>
                                </a>
                                <span class="inner-text">
                                    <b>
                                         Тесты пользователей.pdf
                                    </b>
                                </span>
                            </div>
                        </div>
                        <div class="analyze">
                            <div>
                                <a href="{{ route('to-pdf-user-test') }}">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    <br>
                                </a>
                                <span class="inner-text">
                                    <b>
                                        Результаты.pdf
                                    </b>
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
