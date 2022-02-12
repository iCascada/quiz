@extends('dashboard')

@section('content')
    <div class="d-flex">
        <div class="col-3"></div>
        <div class="card col-6 mt-5">
            <div class="row">
                <div class="form col">
                    <form
                        method="POST"
                        action="{{ route('auth.reset.password.request') }}"
                        novalidate
                    >
                        <h5 class="text-center mb-5">
                            <i class="fa fa-key" aria-hidden="true"></i>
                            &nbsp;
                            Укажите новый пароль
                        </h5>
                        @csrf
                        <div class="form-outline mb-5">
                            <input
                                autofocus
                                type="password"
                                id="current_password"
                                name="current_password"
                                class="form-control form-control-lg"
                            />
                            <label class="form-label" for="current_password">Текущий пароль</label>
                        </div>

                        <div class="form-outline mb-5">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control form-control-lg"
                            />
                            <label class="form-label" for="password">Новый пароль</label>
                        </div>

                        <div class="form-outline mb-5">
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control form-control-lg"
                            />
                            <label class="form-label" for="password_confirmation">Посмотрите новый пароль</label>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button
                                type="submit"
                                class="btn btn-danger btn-lg w-100"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;"
                            >
                                Сохранить изменения
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-3"></div>
    </div>

@endsection
