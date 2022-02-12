<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid justify-content-end nav-bar">
        <button type="button" class="btn btn-link">
            <a class="text-white" href="{{ route('login-page') }}">
                Логин
                &nbsp;
                <i class="fa fa-sign-in text-white" aria-hidden="true"></i>
            </a>
        </button>
        <button type="button" class="btn btn-link">
            <a class="text-white" href="{{ route('register-page') }}">Регистрация
                &nbsp;
                <i class="fa fa-user-plus text-white" aria-hidden="true"></i>
            </a>
        </button>
    </div>
</nav>
