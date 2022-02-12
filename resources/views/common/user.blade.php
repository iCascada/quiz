@php use \Illuminate\Support\Facades\Auth @endphp
@extends('dashboard')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fa fa-cogs" aria-hidden="true"></i>
                        {{ __('pages.user-management') }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-page') }}">{{ app('main-title') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('pages.user-management') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-dark card-outline">
                        <div class="card-body box-profile">
                            <h3 class="profile-username text-center">
                                <i class="fa fa-user-circle" aria-hidden="true"></i>
                                &nbsp;
                                {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                            </h3>
                            <p class="text-muted text-center">
                                {{ Auth::user()->department->name }}
                            </p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Электронная почта</b>
                                    <a class="float-right text-dark">{{ Auth::user()->email }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Дата регистрации</b>
                                    <a class="float-right text-dark">
                                        {{ \Illuminate\Support\Carbon::parse(Auth::user()->created_at )->format('d.m.Y') }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Дата последних изменений</b>
                                    <a class="float-right text-dark">
                                        {{ \Illuminate\Support\Carbon::parse(Auth::user()->updated_at )->format('d.m.Y') }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    @php
                                        $verify = Auth::user()->email_verified_at;
                                    @endphp
                                    <b class="@if($verify) text-success @else text-danger @endif">Подтвержденная
                                        запись</b>
                                    <a
                                        href="{{ route('auth.verify.email') }}"
                                        class="float-right @if($verify) text-success @else text-danger @endif"
                                    >
                                        @if($verify)
                                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                        @else
                                            <button class="btn btn-dark w-140">
                                                Подтвердить
                                            </button>
                                        @endif
                                    </a>
                                </li>

                                <li class="list-group-item">
                                    <b>Изменить пароль</b>
                                    <a class="float-right" href="{{ route('auth.reset.password') }}">
                                        <button class="btn btn-dark w-140">Изменить</button>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div id="settings">
                                <form
                                    method="POST"
                                    action="{{ route('auth.update.info') }}"
                                    class="form-horizontal"
                                    novalidate
                                >
                                    @csrf
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-2 col-form-label">Электронная почта</label>
                                        <div class="col-sm-10">
                                            <input
                                                name="email" type="text" class="form-control" id="email"
                                                value="{{ Auth::user()->email }}"
                                            >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Имя</label>
                                        <div class="col-sm-10">
                                            <input
                                                name="name" type="text" class="form-control" id="name"
                                                value="{{ Auth::user()->name }}"
                                            >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="last_name" class="col-sm-2 col-form-label">Фамилия</label>
                                        <div class="col-sm-10">
                                            <input
                                                name="last_name" type="text" class="form-control" id="last_name"
                                                value="{{ Auth::user()->last_name }}"
                                            >
                                        </div>
                                    </div>

                                    @if(Auth::user()->isAdmin())
                                        <select
                                            class="browser-default custom-select mb-3"
                                            id="role"
                                            name="role_id"
                                        >
                                            @foreach($roles as $role)
                                                <option
                                                    @if($role->id === Auth::user()->role->id) selected
                                                    @endif value="{{ $role->id }}"
                                                >
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif


                                    <select
                                        id="department"
                                        class="browser-default custom-select mb-3"
                                        name="department_id"
                                    >
                                        @foreach($departments as $department)
                                            <option
                                                @if($department->id === Auth::user()->department->id) selected
                                                @endif value="{{ $department->id }}"
                                            >{{ $department->name }}</option>
                                        @endforeach
                                    </select>


                                    <button type="submit" class="btn btn-dark w-100">Сохранить изменения</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
