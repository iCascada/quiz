<form class="pl-3 pr-3 pb-3 needs-validation" novalidate>
    <div class="form-group mb-5">
        <input
            type="text" class="form-control"
            id="name"
            name="text"
            placeholder="Введите текст вопроса"
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
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <h5 class="text-center mb-0"><b>Укажите ответы: </b></h5>
    <div class="text-center mb-3">
        <small><i>Если ответ является верным, отметьте чекбокс</i></small>
    </div>
    <div class="form-group mb-4 form-with-checkbox">
        <input
            type="text"
            class="form-control answer-input"
            name="answer_1"
            placeholder="Введите текст ответа"
            required
        >
        <input class="form-check-input" type="checkbox" name="is_right_answer_1" />
        <div class="invalid-feedback feedback-pos">
            Поле не может быть пустым
        </div>
    </div>

    <div class="form-group mb-4 form-with-checkbox">
        <input
            type="text"
            class="form-control answer-input"
            name="answer_2"
            placeholder="Введите текст ответа"
            required
        >
        <input class="form-check-input" type="checkbox" name="is_right_answer_2" />
        <div class="invalid-feedback feedback-pos">
            Поле не может быть пустым
        </div>
    </div>
</form>

