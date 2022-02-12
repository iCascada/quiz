<form class="pl-3 pr-3 pb-3 needs-validation" novalidate>
    <h6 class="user-status">
        @if(!$user->is_blocked)
            <b class="text-success">Действующий</b>
        @else
            <b class="text-danger">Заблокирован</b>
        @endif
    </h6>
    <input type="hidden" value="{{ $user->id }}" name="id">
    <div class="form-group mb-4">
        <label for="email"><b>Электронная почта</b></label>
        <input
            type="email" class="form-control"
            id="email"
            name="email"
            placeholder="Введите адрес электронной почты"
            value="{{ $user->email }}"
            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
            required
        >
        <div class="invalid-feedback feedback-pos">
            Введите корректный email
        </div>
    </div>
    <div class="form-group mb-4">
        <label for="name"><b>Имя</b></label>
        <input
            type="text"
            class="form-control"
            id="name"
            name="name"
            placeholder="Введите имя пользователя"
            value="{{ $user->name }}"
            pattern="[А-Яа-яёйЙ]{3,}$"
            required
        >
        <div class="invalid-feedback feedback-pos">
            Введите корретное имя
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="last_name"><b>Фамилия</b></label>
        <input
            type="text"
            class="form-control"
            id="last_name"
            name="last_name"
            placeholder="Введите фамилию пользователя"
            value="{{ $user->last_name }}"
            pattern="[А-Яа-яёйЙ]{3,}$"
            required
        >
        <div class="invalid-feedback feedback-pos">
            Введите корретную фамилию
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="department"><b>Отдел</b></label>
        <select class="custom-select" name="department_id" id="department">
            @foreach($departments as $department)
                <option
                    @if($department->id === $user->department->id) selected @endif
                    value="{{ $department->id }}">{{ $department->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-4">
        <label for="role"><b>Права</b></label>
        <select class="custom-select" name="role_id" id="role">
            @foreach($roles as $role)
                <option
                    @if($role->id === $user->role->id) selected @endif
                value="{{ $role->id }}">{{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>
</form>
