@extends('layout')

@section('content')
    <div class="col-4"></div>
    <div class="col-4">
        <div class="card">
            <div class="form">
                <form
                    method="POST"
                    action="{{ route('login') }}"
                    novalidate
                >

                    <h5 class="text-center mb-5">
                        <i class="fa fa-sign-in" aria-hidden="true"></i>
                        &nbsp;
                        Аккаунт
                    </h5>
                    @csrf
                    <div class="form-outline mb-4">
                        <input
                            autofocus
                            type="email"
                            id="email"
                            name="email"
                            class="form-control form-control-lg
                                @if($errors->has('email')) is-invalid @endif
                                @if($errors->any() && !$errors->has('error')) is-valid @endif
                            "
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

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check mb-0">
                            <input
                                class="form-check-input me-2"
                                type="checkbox"
                                id="remember"
                                name="remember"
                            />
                            <label class="form-check-label" for="remember">
                                Запомнить меня
                            </label>
                        </div>
                        <a href="{{ route('password.page') }}" class="text-body">Забыли пароль ?</a>
                    </div>

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button
                            type="submit"
                            class="btn btn-primary btn-lg w-100"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;"
                        >
                            Войти
                        </button>
                        <p class="small fw-bold mt-2 pt-1 mb-0">Нет аккаунта ? <a
                                href="{{ route('register') }}"
                                class="link-danger"
                            >
                                Зарегистрироваться
                            </a>
                        </p>
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
