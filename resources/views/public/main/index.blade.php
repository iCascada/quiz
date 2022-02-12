@php
    $user = \Illuminate\Support\Facades\Auth::user();
@endphp

@extends('dashboard')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fa fa-suitcase" aria-hidden="true"></i>
                        {{ __('pages.dashboard') }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            {{ app('main-title') }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card p-3">
            <div class="container-fluid">
                @if($user->isAdmin() || $user->isModerator())
                <div class="row">
                    <h3 class="mb-3">
                        <i class="fa fa-folder text-primary" aria-hidden="true"></i>
                         Конструктор тестов
                    </h3>
                    <div class="col-3">
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h3>{{ $categoryCount }}</h3>
                                <p>Категории</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-folder-o" aria-hidden="true"></i>
                            </div>
                            <a
                                href="{{ route('categories') }}"
                                class="small-box-footer"
                            >
                                {{ __('pages.categories') }} <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h3>{{ $questionCount }} / {{ $answerCount }}</h3>
                                <p>Вопросы / ответы</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-folder-o" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('questions') }}" class="small-box-footer">{{ __('pages.questions') }} <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h3>{{ $testCount }}</h3>
                                <p>Тесты</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('management-test') }}" class="small-box-footer">{{ __('pages.tests-management') }} <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                @endif

                @if($user->isAdmin())
                <div class="row">
                    <h3 class="mb-3 mt-5">
                        <i class="fa fa-folder text-danger" aria-hidden="true"></i>
                         Администрирование
                    </h3>
                    <div class="col-3">
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h3>{{ $userCount }}</h3>
                                <p>Пользователей</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('users-page') }}" class="small-box-footer">{{ __('pages.users-management') }} <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <h3 class="mb-3 mt-5">
                        <i class="fa fa-folder" aria-hidden="true"></i>
                        Тестирование
                    </h3>
                    <div class="col-3">
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h3>
                                    {{ $actualTestCount }}
                                </h3>
                                <p>{{ __('pages.tests-actual') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('tests-actual') }}" class="small-box-footer">Перейти <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h3>
                                    {{ $passedTestCount }}
                                </h3>
                                <p>{{ __('pages.tests-passed') }}</p>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fa fa-calendar-times-o" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('tests-passed') }}" class="small-box-footer">Перейти <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <h3 class="mb-3 mt-5">
                        <i class="fa fa-folder" aria-hidden="true"></i>
                        Настройка
                    </h3>
                    <div class="col-3">
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h3>
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </h3>
                                <p>Аккаунт</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cogs" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('account-page') }}" class="small-box-footer">{{ __('pages.user-management') }} <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
