<link rel="stylesheet" href="/css/admin/select2.min.css">

<div class="p-3 test-view">
    <form novalidate class="needs-validation">
        <div class="form-group mb-5">
            <label for="name"><b>Название теста</b></label>
            <input
                type="text"
                class="form-control text-center font-weight-bold"
                id="name"
                name="name"
                placeholder="Введите название теста"
                required
                value="{{ $test->name }}"
                autofocus
            >
            <div class="invalid-feedback feedback-pos">
                Введите вопрос
            </div>
        </div>
        <div class="form-group mb-5">
            <label for="timer"><b>Время для прохождения</b></label>
            <select class="custom-select" name="timer" id="timer">
                <option value="0">Без ограничений</option>
                @foreach($timer as $minute)
                    <option
                        @if($minute == $test->timer) selected @endif
                        value="{{ $minute }}"
                    >
                        {{ $minute }} минут
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-5">
            <label for="attempt"><b>Количество попыток</b></label>
            <select class="custom-select" name="attempt" id="attempt">
                <option value="0">Без ограничений</option>
                @foreach($attempts as $attempt)
                    <option
                        @if($attempt == $test->attempt) selected @endif value="{{ $attempt }}"
                    >
                        {{ $attempt }} раз
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-5">
            <label for="passed_value"><b>Минимальный процент</b></label>
            <select class="custom-select" name="passed_value" id="passed_value">
                @foreach($passedValues as $passedValue)
                    <option
                        @if($passedValue == $test->passed_value) selected @endif value="{{ $passedValue }}"
                    >
                        {{ $passedValue }} %
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-5" id="categoryContainer">
            <label for="users"><b>Вопросы для тестирования</b></label>
            <select name="question_ids[]" id="questions" class="w-100" multiple="multiple">
                @foreach($categories as $category)
                    <optgroup label="{{ $category->text }}"></optgroup>
                    @foreach($category->questions as $question)
                        <option
                            @if(in_array($question->id, $test->questions->pluck('id')->toArray())) selected @endif
                            value="{{ $question->id }}">{{ $question->text }}
                        </option>
                    @endforeach
                @endforeach
            </select>
        </div>
        <h5 class="text-center font-weight-bold">Тестируемые сотрудники</h5>
        <div class="form-group mb-5" id="userContainer">
            <label for="users"><b>Тестируемые сотрудники</b></label>
            <select name="user_ids[]" id="users" class="user-select2 w-100" multiple="multiple">
                @foreach($departments as $department)
                    @if($department->users->count())
                        <optgroup label="{{ $department->name }}"></optgroup>
                        @foreach($users as $user)
                            @if($user->department->id === $department->id)
                                <option
                                    @if(in_array($user->id, $test->users->pluck('id')->toArray())) selected @endif
                                    value="{{ $user->id }}">{{ $user->last_name }} {{ $user->name }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </select>
        </div>
        <input type="hidden" name="id" value="{{ $test->id }}">
    </form>
</div>

<script src="/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        const $categoryContainer = $('#categoryContainer')

        $('#questions')
            .select2({
                closeOnSelect: false,
            })
            .on('select2:unselect', function () {
                const questionCount = $categoryContainer.find('.select2-selection__choice').length
                const $questionSelect2  = $categoryContainer.find('.select2-selection')

                if (!questionCount) {
                    $questionSelect2.addClass('is-invalid')

                    if (!$categoryContainer.find('.danger-feedback').length) {
                        const $questionFeedback = $('<div>')
                            .addClass('danger-feedback')
                            .text('Необходимо указать хотя бы один вопрос')

                        $categoryContainer.append($questionFeedback)
                    }
                }
            })
            .on('select2:select', function () {
                $categoryContainer
                    .find('.danger-feedback')
                    .remove()
                $categoryContainer
                    .find('.select2-selection')
                    .removeClass('is-invalid')
            })


        $('#users').select2({
            closeOnSelect: false,
        })

    });
</script>
