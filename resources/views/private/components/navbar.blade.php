<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <div class="dropdown">
                <button
                    class="btn btn-link text-dark dropdown-toggle"
                    type="button"
                    data-mdb-toggle="dropdown"
                    aria-expanded="false"
                >
                    <i class="fa fa-user-circle fz-1-1" aria-hidden="true"></i>
                </button>
                <ul id="dashboard-dropdown" class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('account-page') }}">
                            <i class="fa fa-cogs" aria-hidden="true"></i>
                            &nbsp;
                            Настройка аккаунта
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                            &nbsp;
                            Выход
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>
