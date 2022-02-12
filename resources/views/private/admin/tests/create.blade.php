<div class="create-test-form">
    <form novalidate class="needs-validation">
        <div class="form-group mb-4">
            <label for="name"><b>Название</b></label>
            <input name="name" class="form-control" type="text" id="name" autofocus required>
            <div class="invalid-feedback feedback-pos">
                Введите название
            </div>
        </div>
        <div class="form-group">
            <label for="users"><b>Тестируемые сотрудники</b></label>
            <select name="user_ids[]" id="users" class="user-select2 w-100" multiple="multiple">
                @foreach($departments as $department)
                    @if($department->users->count())
                        <optgroup label="{{ $department->name }}"></optgroup>
                        @foreach($users as $user)
                            @if($user->department->id === $department->id)
                                <option value="{{ $user->id }}">{{ $user->last_name }} {{ $user->name }}</option>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="timer"><b>Время для прохождения</b></label>
            <select class="custom-select" name="timer" id="timer">
                <option value="0">Без ограничений</option>
                @foreach($timer as $minute)
                    <option value="{{ $minute }}">{{ $minute }} минут</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="attempt"><b>Количество попыток</b></label>
            <select class="custom-select" name="attempt" id="attempt">
                <option value="0">Без ограничений</option>
                @foreach($attempts as $attempt)
                    <option value="{{ $attempt }}">{{ $attempt }} раз</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="passed_value"><b>Минимальный процент</b></label>
            <select class="custom-select" name="passed_value" id="passed_value">
                @foreach($passedValues as $passedValue)
                    <option value="{{ $passedValue }}">{{ $passedValue }} %</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<style>

</style>
<script>
    $('.user-select2').select2({
        closeOnSelect: false,
    })
</script>
