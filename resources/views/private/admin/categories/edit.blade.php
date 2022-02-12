<form class="pl-3 pr-3 pb-3 needs-validation" novalidate>
    <div class="form-group mb-4">
        <label for="name"><b>Название категории</b></label>
        <input
            type="text" class="form-control"
            id="name"
            name="name"
            value="{{ $category->name }}"
            placeholder="Введите название категории"
            required
            autofocus
        >
        <div class="invalid-feedback feedback-pos">
            Обязательное поле
        </div>
    </div>
    <div class="form-group mb-4">
        <label for="note"><b>Краткое описание</b></label>
        <textarea class="form-control" name="note" id="note" cols="30" rows="3">{{ $category->note }}</textarea>
    </div>
    <input type="hidden" name="id" value="{{ $category->id }}">
</form>

