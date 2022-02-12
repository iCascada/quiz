@extends('layout')

@section('content')
    <div class="col-4"></div>
    <div class="col-4">
        @include('components.error')
        <div class="card">
            <div class="form">
                <form
                    method="POST"
                    action="{{ route('password.email') }}"
                >
                    <h5 class="text-center mb-5">
                        <i class="fa fa-sign-in" aria-hidden="true"></i>
                        &nbsp;
                        Введите адрес электронной почты
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

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button
                            type="submit"
                            class="btn btn-primary btn-lg w-100"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;"
                        >
                             Отправить
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="col-4"></div>
@endsection
