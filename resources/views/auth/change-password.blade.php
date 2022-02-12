@extends('layout')

@section('content')
    <div class="col-4"></div>
    <div class="col-4">
        @include('components.error')
        <div class="card">
            <div class="form">
                <form
                    method="POST"
                    action="{{ route('password.update') }}"
                >
                    <h5 class="text-center mb-5">
                        <i class="fa fa-key" aria-hidden="true"></i>
                        &nbsp;
                        Укажите новый пароль
                    </h5>
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">
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
                            name="password_confirmation"
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

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button
                            type="submit"
                            class="btn btn-primary btn-lg w-100"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;"
                        >
                            Обновить
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="col-4"></div>
@endsection
