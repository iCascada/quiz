@extends('dashboard')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fa fa-suitcase" aria-hidden="true"></i>
                        {{ __('pages.questions') }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a
                                href="{{ route('dashboard-page') }}"
                            >
                                {{ app('main-title') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('pages.questions') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card p-3">
            <div class="container-fluid">
                <div class="d-flex justify-content-end option-panel">
                    <button class="btn btn-link text-dark addButton">
                        @if(!$questions->count())
                            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                        @else
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        @endif
                        &nbsp;
                        Добавить вопрос
                    </button>
                </div>
                <hr>
                @if($questions->count())
                    <table id="table" class="table table-sm stripted">
                        <thead class="thead-dark">
                        <tr>
                            <th></th>
                            <th class="th-sm text-center">Идентификатор</th>
                            <th class="th-sm text-center">Категория вопроса</th>
                            <th class="th-sm text-center w-350px">Текст</th>
                            <th class="th-sm text-center w-450px">Ответы</th>
                            <th class="th-sm text-center">Дата создания</th>
                            <th class="th-sm text-center">Кем создано</th>
                            <th class="th-sm text-center">Дата обновления</th>
                            <th class="th-sm text-center">Кем обновлено</th>
                            <th class="th-sm text-center">Операции</th>
                        </tr>
                        </thead>
                        <tbody
                            class="table-striped"
                        >
                        @foreach($questions as $question)
                            <tr>
                                <td class="position-relative"></td>
                                <td class="text-center id">{{ $question->id }}</td>
                                <td class="text-center id">{{ $question->category->name }}</td>
                                <td class="text-center">{{ $question->text }}</td>
                                <td>
                                   @php $counter = 1 @endphp
                                   @foreach($question->answers as $answer)
                                       <span @if($answer->is_right) class="text-success font-weight-bold"  @endif>
                                           {{ $counter++ }}. {{ $answer->text }}
                                       </span>
                                        <br>
                                   @endforeach
                                </td>
                                <td class="text-center">
                                    {{\Illuminate\Support\Carbon::make($question->created_at)->format('d.m.Y') }}
                                </td>
                                <td class="text-center">
                                    {{ $question->createdBy->name }}

                                    {{ $question->createdBy->last_name }}
                                </td>
                                <td class="text-center">
                                    {{\Illuminate\Support\Carbon::make($question->updated_at)->format('d.m.Y') }}
                                </td>
                                <td class="text-center">
                                    @if($question->updatedBy)
                                        {{ $question->updatedBy->name }}

                                        {{ $question->updatedBy->last_name }}
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex operations justify-content-around">
                                        <button class="btn btn-link text-dark editButton">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-link text-dark deleteButton">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning show-always w-100" role="alert">
                        <h4 class="alert-heading text-center">Предупреждение!</h4>
                        <hr>
                        <p class="mb-0 text-center">
                            Необходимо добавить вопросы с вариантам ответов для дальнейшего включения в тестовое задание.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('css')
    <link rel="stylesheet" href="/css/admin/datatables.min.css">
@endpush

@push('js')
    <script src="/js/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            String.prototype.hash = function () {
                return '#' + this
            }

            const $tableId = 'table'
            const countAnswer = 6
            let selected = new Set()

            $($tableId.hash()).DataTable({
                language: {
                    url: '/js/datatable-rus.json'
                },
                pagingType: "numbers",
                columnDefs: [ {
                    orderable: false,
                    className: 'select-checkbox',
                    targets:   0
                } ],
                select: {
                    style:    'os',
                    selector: 'td:first-child'
                },
                order: [[ 1, 'desc' ]]
            });

            $($tableId.hash() + ' .select-checkbox').click(function() {
                const $tr = $(this).parents('tr')
                const id = parseInt($tr.find('.id').text())

                $tr.toggleClass('selected')

                if($tr.hasClass('selected')) {
                    selected.add(id)
                }else{
                    selected.delete(id)
                }

                const $panel = $('.option-panel')

                if (selected.size) {
                    if (!$panel.children('.deleteSelected').length){
                        $panel.prepend(
                            $('<button>')
                                .addClass('deleteSelected btn btn-link text-dark')
                                .html('&nbsp;Удалить выбранные элементы')
                                .prepend($('<i>').addClass('fa fa-trash'))
                        )
                    }
                }else{
                    $panel.children('.deleteSelected').remove()
                }
            })

            const $editButton = $('.editButton')
            const $addButton = $('.addButton')
            const $deleteButton = $('.deleteButton')
            const $body = $('body')

            const checkData = (data) => {
                const pattern = /^is_right_answer_\d/
                let isChecked = false

                data.map(function(item) {
                    if (!isChecked) {
                        if (pattern.test(item.name)){
                            isChecked = true
                        }
                    }
                })

                return isChecked
            }

            const createWarning = (confirm, message) => {
                if (!confirm.$content.find('.alert').length) {
                    confirm.$content.prepend(
                        window.message(message)
                    )
                }
            }

            const getRemoveQuestionButton = {
                text: 'Удалить последний ответ',
                btnClass: 'btn-danger rounded-0',
                action: function() {
                    const self = this
                    let group = self.$content.find('.form-with-checkbox')

                    group[group.length - 1].remove()
                    group = self.$content.find('.form-with-checkbox')

                    if (group.length === 2) {
                        self.$$removeQuestion.prop('disabled', true)
                    }

                    if (group.length < countAnswer) {
                        self.$$addQuestion.prop('disabled', false)
                    }

                    return false
                },
            }

            const getAddQuestionButton = {
                text: 'Добавить ответ',
                btnClass: 'btn-success rounded-0',
                action: function () {
                    const self = this
                    const group = self.$content.find('.form-with-checkbox')
                    const form = self.$content.find('form')
                    const counter = group.length + 1

                    const $div = $('<div>')
                        .addClass('form-group mb-4 form-with-checkbox')
                    const $input = $('<input>')
                        .addClass('form-control answer-input')
                        .attr('name', 'answer_' + counter)
                        .attr('placeholder', 'Введите текст ответа')
                        .attr('type', 'text')
                        .attr('required', true)
                    const $checkbox = $('<input>')
                        .addClass('form-check-input')
                        .attr('type', 'checkbox')
                        .attr('name', 'is_right_answer_' + counter)
                    const $feedback = $('<div>')
                        .addClass('invalid-feedback feedback-pos')
                        .text('Поле не может быть пустым')

                    $div
                        .append($input)
                        .append($checkbox)
                        .append($feedback)

                    form
                        .append($div)

                    if (counter > 2) {
                        self.$$removeQuestion.prop('disabled', false)
                    }

                    if (counter === countAnswer) {
                        self.$$addQuestion.prop('disabled', true)
                    }

                    return false
                }
            }

            $body.on('click', '.deleteSelected', function() {
                $.confirm({
                    title: 'Подтвердите действие',
                    theme: 'material',
                    columnClass: 'm',
                    backgroundDismiss: false,
                    content: 'Вы действительно хотите продолжить ? Выбранные вопросы будет исключены из всех связанных тестов. Данное действие является <span class="text-danger font-weight-bold">необратимым!</span>',
                    buttons: {
                        confirm: {
                            text: 'Подтвердить',
                            btnClass: 'btn-link',
                            keys: ['enter'],
                            action: function (button) {
                                $.ajax({
                                    url: "{{ route('delete-all-question-request') }}",
                                    method: 'delete',
                                    dataType: 'json',
                                    data: { ids: [...selected], _token: "{{ csrf_token() }}" },
                                    success: function() {
                                            button.el.prop('disabled', true)
                                            setTimeout(() => {
                                                location.reload()
                                            }, 500);
                                    }
                                })

                                return false
                            }
                        },
                        cancel: {
                            text: 'Отменить',
                            btnClass: 'btn-link',
                            keys: ['esc']
                        },

                    }
                });
            })
            $deleteButton.click(function(e) {
                const id = getId(e)

                $.confirm({
                    title: 'Подтвердите действие',
                    theme: 'material',
                    columnClass: 'm',
                    backgroundDismiss: false,
                    content: 'Вы действительно хотите продолжить ? Вопрос будет исключен из всех связанных тестов. Данное действие является <span class="text-danger font-weight-bold">необратимым!</span>',
                    buttons: {
                        confirm: {
                            text: 'Подтвердить',
                            btnClass: 'btn-link',
                            keys: ['enter'],
                            action: function (button) {
                                $.ajax({
                                    url: "{{ route('delete-question-request') }}",
                                    method: 'delete',
                                    dataType: 'json',
                                    data: { id: id, _token: "{{ csrf_token() }}" },
                                    success: function() {
                                            button.el.prop('disabled', true)
                                            setTimeout(() => {
                                                location.reload()
                                            }, 500);
                                    }
                                })

                                return false
                            }
                        },
                        cancel: {
                            text: 'Отменить',
                            btnClass: 'btn-link',
                            keys: ['esc']
                        },

                    }
                });
            })
            $addButton.click(function () {
                $.confirm({
                    title: 'Добавление нового вопроса',
                    theme: 'material',
                    columnClass: 'xl',
                    backgroundDismiss: false,
                    onContentReady: function() {
                        if (this.$content.find('.form-with-checkbox').length === 2) {
                            this.$$removeQuestion.prop('disabled', true)
                        }
                    },
                    content: function () {
                        const self = this;
                        return $.ajax({
                            url: "{{ route('add-question-modal') }}",
                            method: 'post',
                            data: {_token: '{{ csrf_token() }}'}
                        }).done(function (response) {
                            self.setContent(response)
                        })
                    },
                    buttons: {
                        addQuestion: getAddQuestionButton,
                        removeQuestion: getRemoveQuestionButton,
                        confirm: {
                            text: 'Сохранить',
                            btnClass: 'btn-link',
                            action: function (button) {
                                const self = this
                                const form = self.$content.find('form')
                                const data = form.serializeArray()

                                let isValid = true

                                if(checkData(data)) {
                                    self.$content.find('.alert').remove()
                                    Array.prototype.filter.call(form, function(form) {
                                        if (form.checkValidity() === false) {
                                            isValid = false
                                        }
                                        form.classList.add('was-validated');
                                    });

                                    if(isValid){
                                        data.push({name: "_token", value: "{{csrf_token()}}" })
                                        $.ajax({
                                            url: "{{ route('add-question-request') }}",
                                            method: 'post',
                                            dataType: 'json',
                                            data: $.param(data),
                                            success: function() {
                                                button.el.prop('disabled', true)
                                                setTimeout(() => {
                                                    location.reload()
                                                }, 500);
                                            }
                                        })
                                    }
                                }else{
                                    createWarning(self, 'Не указан правильный ответ. Операция <b>не может быть продолжена</b>')
                                }

                                return false
                            }
                        },
                        cancel: {
                            text: 'Выход',
                            btnClass: 'btn-link',
                            keys: ['esc']
                        },

                    }
                });
            })
            $editButton.click(function (e) {
                const id = getId(e)

                $.confirm({
                    title: 'Редактирование вопроса',
                    theme: 'material',
                    columnClass: 'xl',
                    backgroundDismiss: false,
                    onContentReady: function() {
                        if (this.$content.find('.form-with-checkbox').length === 2) {
                            this.$$removeQuestion.prop('disabled', true)
                        }
                    },
                    content: function () {
                        const self = this;
                        return $.ajax({
                            url: "{{ route('edit-question-modal') }}",
                            method: 'post',
                            data: {id: id, _token: '{{ csrf_token() }}'}
                        }).done(function (response) {
                            self.setContent(response)
                            self.setTitle(response.title)
                        })
                    },
                    buttons: {
                        addQuestion: getAddQuestionButton,
                        removeQuestion: getRemoveQuestionButton,
                        confirm: {
                            text: 'Сохранить',
                            btnClass: 'btn-link',
                            keys: ['enter'],
                            action: function (button) {
                                const self = this
                                const form = self.$content.find('form')
                                const data = form.serializeArray()

                                let isValid = true

                                if(checkData(data)) {
                                    Array.prototype.filter.call(form, function(form) {
                                        if (form.checkValidity() === false) {
                                            isValid = false
                                        }
                                        form.classList.add('was-validated');
                                    });

                                    if(isValid){
                                        data.push({name: "_token", value: "{{csrf_token()}}" })
                                        $.ajax({
                                            url: "{{ route('update-question-request') }}",
                                            method: 'patch',
                                            dataType: 'json',
                                            data: $.param(data),
                                            success: function() {
                                                button.el.prop('disabled', true)
                                                setTimeout(() => {
                                                    location.reload()
                                                }, 500);
                                            }
                                        })
                                    }
                                }else{
                                    createWarning(self, 'Не указан правильный ответ. Операция <b>не может быть продолжена</b>')
                                }

                                return false
                            }
                        },
                        cancel: {
                            text: 'Выход',
                            btnClass: 'btn-link',
                            keys: ['esc']
                        },

                    }
                });
            })

            $body.on('keypress', '.jconfirm', function (e){
                if (e.code === 'Enter') {
                    $(this).find('.jconfirm-buttons button:first-child').trigger('click')
                    e.preventDefault();
                    e.stopPropagation();
                }
            })

            const getId = (e) => {
                return $(e.target)
                    .parents('tr')
                    .children('td:nth-child(2)')
                    .text()
            }
        });
    </script>
@endpush
