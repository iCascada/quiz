@extends('layout')

@section('content')
    <div class="col-4"></div>
    <div class="col-4">
        <div class="card">
            <div id="errors" class="d-none">{{ $errors }}</div>
            <div class="form">
                <form
                    method="POST"
                    action={{ route('register') }}
                    novalidate
                >
                    <h5 class="text-center mb-3">
                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                        &nbsp;
                        Регистрация в системе
                    </h5>
                    <div class="form-outline mb-5">
                        <input
                            name="email"
                            type="email"
                            id="email"
                            class="form-control form-control-lg
                                @if($errors->has('email')) is-invalid @endif
                                @if($errors->any() && !$errors->has('error')) is-valid @endif
                            "
                            autofocus
                            value="{{ $email ?? old('email') }}"
                        />
                        <label class="form-label" for="email">Электронная почта</label>
                        @if($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->get('email')[0] }}</div>
                        @elseif($errors->any() && !$errors->has('error'))
                            <div class="valid-feedback w-100 d-flex justify-content-end">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </div>
                        @endif
                    </div>

                    <div class="form-outline mb-5">
                        <input
                            name="name"
                            type="text"
                            id="name"
                            value="{{ old('name') }}"
                            class="form-control form-control-lg
                                @if($errors->has('name')) is-invalid @endif
                                @if($errors->any() && !$errors->has('error')) is-valid @endif
                            "
                        />
                        <label class="form-label" for="name">Имя</label>
                        @if($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->get('name')[0] }}</div>
                        @elseif($errors->any() && !$errors->has('error'))
                            <div class="valid-feedback w-100 d-flex justify-content-end">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </div>
                        @endif
                    </div>

                    <div class="form-outline mb-5">
                        <input
                            name="last_name"
                            type="text"
                            id="last_name"
                            value="{{ old('last_name') }}"
                            class="form-control form-control-lg
                                @if($errors->has('last_name')) is-invalid @endif
                                @if($errors->any() && !$errors->has('error')) is-valid @endif
                            "
                        />
                        <label class="form-label" for="last_name">Фамилия</label>
                        @if($errors->has('last_name'))
                            <div class="invalid-feedback">{{ $errors->get('last_name')[0] }}</div>
                        @elseif($errors->any() && !$errors->has('error'))
                            <div class="valid-feedback w-100 d-flex justify-content-end">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </div>
                        @endif
                    </div>

                    <div class="form-outline mb-5">
                        <input
                            name="password"
                            type="password"
                            id="password"
                            class="form-control form-control-lg
                                @if($errors->has('password')) is-invalid @endif
                                @if($errors->any() && !$errors->has('error')) is-valid @endif
                            "
                        />
                        <label class="form-label" for="password">Пароль</label>
                        @if($errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->get('password')[0] }}</div>
                        @elseif($errors->any() && !$errors->has('error'))
                            <div class="valid-feedback w-100 d-flex justify-content-end">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </div>
                        @endif
                    </div>

                    <div class="form-outline mb-5">
                        <input
                            name="confirm_password"
                            type="password"
                            id="confirm_password"
                            class="form-control form-control-lg
                                @if($errors->has('confirm_password')) is-invalid @endif
                                @if($errors->any() && !$errors->has('error')) is-valid @endif
                            "
                        />
                        <label class="form-label" for="confirm_password">Повторите пароль</label>
                        @if($errors->has('confirm_password'))
                            <div class="invalid-feedback">{{ $errors->get('confirm_password')[0] }}</div>
                        @elseif($errors->any() && !$errors->has('error'))
                            <div class="valid-feedback w-100 d-flex justify-content-end">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </div>
                        @endif
                    </div>

                    <select
                            id="department"
                            name="department_id"
                            class="browser-default custom-select @if($errors->any() && !$errors->has('error')) is-valid @endif"
                        >
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                    </select>

                    @csrf
                    <div class="text-center text-lg-start mt-5 pt-2">
                        <button
                            type="submit"
                            class="btn btn-primary btn-lg w-100"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;"
                        >
                            Регистрация
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="col-4"></div>
@endsection

@push('css')
<style>
    .invalid-feedback {
        margin-top: 3px !important;
    }
</style>
