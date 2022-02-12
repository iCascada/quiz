<div class="p-3 test-view">
    <div class="d-flex justify-content-end">
        @if($test->is_actual)
            <div class="text-success"><b>Актуальный</b></div>
        @else
            <div class="text-danger"><b>Тест пройден</b></div>
        @endif
    </div>
    <h4 class="text-center font-weight-bold mb-3">{{ $test->name }}</h4>

    <div class="timer d-flex justify-content-end">
        @if($test->timer)
            <div class="font-weight-bold">
                Время : {{ $test->timer }}&nbsp;<i class="fa fa-clock-o" aria-hidden="true"></i></div>
        @else
            <div class="font-weight-bold">
                Время : не ограничено&nbsp;<i class="fa fa-times-circle-o" aria-hidden="true"></i>
            </div>
        @endif
    </div>
    <div class="d-flex justify-content-end">
        <div class="font-weight-bold">
            Минимальный балл : {{ $test->passed_value }}&nbsp;<i class="fa fa-percent" aria-hidden="true"></i>
        </div>
    </div>
    <div class="timer d-flex justify-content-end">
        @if($test->attempt)
            <div class="font-weight-bold">
                Попытки : {{ $test->attempt }}&nbsp;<i class="fa fa-ticket" aria-hidden="true"></i></div>
        @else
            <div class="font-weight-bold">
                Попытки : не ограничено&nbsp;<i class="fa fa-times-circle-o" aria-hidden="true"></i>
            </div>
        @endif
    </div>

    @foreach($test->questions as $question)
        <div class="mb-3">
            <h6 class="mb-3 mt-3"><i><b>{{ $question->text }} ?</b></i></h6>
            <hr>
            @foreach($question->answers as $answer)
                <div class="form-group mb-2 form-check">
                    <input
                        class="form-check-input me-2" @if($answer->is_right) checked @endif type="checkbox"
                        id="{{ $answer->id }}" disabled
                    >
                    &nbsp;
                    <label for="{{ $answer->id }}">{{ $answer->text }}</label>
                </div>
            @endforeach
        </div>
    @endforeach
    <h5 class="text-center font-weight-bold">Список сотрудников для прохождения тестирования:</h5>
    @if($test->users->count())
        <div class="test-user-list">
            <table class="table table-striped table-sm">
                <thead class="thead-dark">
                <tr>
                    <th class="font-weight-bold" scope="col">#</th>
                    <th class="font-weight-bold" scope="col">Имя сотрудника</th>
                    <th class="font-weight-bold" scope="col">Результат</th>
                </tr>
                </thead>
                <tbody>
                @php $counter = 1 @endphp
                @foreach($test->users as $key => $user)
                    <tr>
                        <th scope="row">{{ $counter++ }}</th>
                        <td>
                            {{ $user->last_name . ' ' . $user->name . ' (' . $user->department->name . ')' }}
                        </td>
                        <td>
                            @if($user->pivot->result) <b>{{ $user->pivot->result }}%</b> @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-warning show-always w-100" role="alert">
            <p class="mb-0 text-center">
                За текущим тестом не закреплен ни один сотрудник
            </p>
        </div>
    @endif
</div>
