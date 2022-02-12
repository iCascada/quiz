<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-center">
            <div class="text-center">
                <h6 class="text-white">
                    <i class="fa fa-user-circle" aria-hidden="true"></i>
                    {{ $user->name }} {{ $user->last_name }}
                </h6>
                <span class="text-white">
                        Роль: <b class="text-danger">{{ $user->role->name }}</b>
                </span>
            </div>
        </div>

        <nav class="mt-2">

            @if($user->isAdmin())
                <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                    <li class="nav-header text-white">
                        Администрирование
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users-page') }}" class="nav-link">
                            <i class="nav-icon fa fa-users" aria-hidden="true"></i>
                            {{ __('pages.users-management') }}
                        </a>
                    </li>
                </ul>
            @endif

            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                <li class="nav-header text-white">
                    Управление
                </li>
                <li class="nav-item">
                    <a href="{{ route('dashboard-page') }}" class="nav-link">
                        <i class="nav-icon fa fa-th"></i>
                        {{ app('main-title') }}
                    </a>
                </li>
            </ul>

            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                <li class="nav-header text-white">
                    Тестирование
                </li>
                <li class="nav-item">
                    <a href="{{ route('tests-actual') }}" class="nav-link">
                        <i class="nav-icon fa fa-calendar-check-o" aria-hidden="true"></i>
                        {{ __('pages.tests-actual') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tests-passed') }}" class="nav-link">
                        <i class="nav-icon fa fa-calendar-times-o" aria-hidden="true"></i>
                        {{ __('pages.tests-passed') }}
                    </a>
                </li>
            </ul>

            @if($user->isAdmin() || $user->isModerator() )
                <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                    <li class="nav-header text-white">
                        Конструктор тестов
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('categories') }}" class="nav-link">
                            <i class="nav-icon fa fa-suitcase" aria-hidden="true"></i>
                            {{ __('pages.categories') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('questions') }}" class="nav-link">
                            <i class="nav-icon fa fa-suitcase" aria-hidden="true"></i>
                            {{ __('pages.questions') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('management-test') }}" class="nav-link">
                            <i class="nav-icon fa fa-suitcase" aria-hidden="true"></i>
                            {{ __('pages.tests-management') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tests') }}" class="nav-link">
                            <i class="nav-icon fa fa-plus" aria-hidden="true"></i>
                            {{ __('pages.tests-create') }}
                        </a>
                    </li>
                </ul>
            @endif

            @if($user->isAdmin())
                <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                    <li class="nav-header text-white">
                        Аналитика
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('analyze-page') }}" class="nav-link">
                            <i class="nav-icon fa fa-files-o" aria-hidden="true"></i>
                            {{ __('pages.analyze') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('chart-page') }}" class="nav-link">
                            <i class="nav-icon fa fa-pie-chart" aria-hidden="true"></i>
                            {{ __('pages.chart') }}
                        </a>
                    </li>
                </ul>
            @endif

            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                <li class="nav-header text-white">
                    Настройка
                </li>
                <li class="nav-item">
                    <a href="{{ route('account-page') }}" class="nav-link">
                        <i class="nav-icon fa fa-cogs" aria-hidden="true"></i>
                        {{ __('pages.user-management') }}
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
