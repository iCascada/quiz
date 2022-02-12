<form class="pl-3 pr-3 pb-3 needs-validation" novalidate>
    <input type="hidden" name="id" value="{{ $question->id }}">
    <div class="form-group mb-5">
        <input
            type="text" class="form-control"
            id="name"
            name="text"
            placeholder="Введите текст вопроса"
            value="{{ $question->text }}"
            required
            autofocus
        >
        <div class="invalid-feedback feedback-pos">
            Введите вопрос
        </div>
    </div>

    <div class="form-group mb-4">
        <select
            id="category"
            name="category_id"
            class="browser-default custom-select"
        >
            @foreach($categories as $category)
                <option @if($question->category->id === $category->id) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <h5 class="text-center mb-0"><b>Укажите ответы: </b></h5>
    <div class="text-center mb-3">
        <small><i>Если ответ является верным, отметьте чекбокс</i></small>
    </div>

    @php $counter = 1 @endphp
    @foreach($question->answers as $answer)
        <div class="form-group mb-4 form-with-checkbox">
            <input
                type="text"
                class="form-control answer-input"
                name="answer_{{ $counter }}"
                placeholder="Введите текст ответа"
                value="{{ $answer->text }}"
                required
            >
            <input
                @if($answer->is_right) checked @endif
                class="form-check-input"
                type="checkbox"
                name="is_right_answer_{{ $counter++ }}"
            />
            <div class="invalid-feedback feedback-pos">
                Поле не может быть пустым
            </div>
        </div>
    @endforeach
</form>

