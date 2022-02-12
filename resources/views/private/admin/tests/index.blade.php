@extends('dashboard')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        {{ __('pages.tests-create') }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                href="{{ route('dashboard-page') }}"
                            >
                                {{ app('main-title') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('pages.tests-create') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card p-3">
            <div class="d-flex justify-content-end option-panel">
                <button class="btn btn-link text-dark saveButton">
                    <i class="fa fa-cogs" aria-hidden="true"></i>
                    &nbsp;
                    Конфигурация нового теста
                </button>
            </div>
            <hr>
            <div class="container-fluid">
                <select name="questions[]" multiple="multiple" class="select2 w-100">
                    @foreach($categories as $category)
                        <optgroup label="{{ $category->name }}"></optgroup>
                        @foreach($category->questions as $question)
                            <option value="{{ $question->id }}">{{ $question->text }}</option>
                        @endforeach
                    @endforeach
                </select>
                <h3 class="text-center mt-3">Предпросмотр:</h3>
                <div class="test-preview container">
                    <hr>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css')
    <link rel="stylesheet" href="/css/admin/select2.min.css">
@endpush

@push('js')
    <script src="/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {

            const $select = $('.select2')
            const $saveButton = $('.saveButton')
            let questionIds = []

            $saveButton
                .prop('disabled', true)

            $select.select2({
                closeOnSelect: false,
            });

            $saveButton.click(function() {
                $.confirm({
                    title: '<i class=\"fa fa-cogs\" aria-hidden=\"true\"></i> Конфигурация нового теста',
                    theme: 'material',
                    content: function() {
                        const self = this;
                        return $.ajax({
                            url: "{{ route('create-test-modal') }}",
                            method: 'post',
                            data: {_token: '{{ csrf_token() }}'}
                        }).done(function (response) {
                            self.setContent(response)
                        })
                    },
                    columnClass: 'l',
                    buttons: {
                        confirm: {
                            btnClass: 'btn-link',
                            text: 'Создать тест',
                            keys: ['enter'],
                            action: function(){
                                const self = this
                                const form = self.$content.find('form')
                                const data = form.serializeArray()
                                let isValid = true

                                Array.prototype.filter.call(form, function(form) {
                                    if (form.checkValidity() === false) {
                                        isValid = false
                                    }
                                    form.classList.add('was-validated');
                                });

                                form.find('.select2').addClass('was-validate')

                                if (isValid) {
                                    data.push({name: "_token", value: "{{csrf_token()}}" })
                                    questionIds.forEach(function(id) {
                                        data.push({name: "question_ids[]", value: id})
                                    })

                                    $.ajax({
                                        url: "{{ route('save-test') }}",
                                        type: 'POST',
                                        data: $.param(data),
                                        dataType: 'JSON',
                                        success: function() {
                                            location.href = "{{ route('management-test') }}"
                                        }
                                    })

                                }


                                return false
                            }
                        },
                        cancel: {
                            btnClass: 'btn-link',
                            text: ' Выход',
                            keys: ['esc']
                        },
                    }
                });
            })

            $('body').on('keypress', '.jconfirm', function (e){
                if (e.code === 'Enter') {
                    $(this).find('.jconfirm-buttons button:first-child').trigger('click')
                    e.preventDefault();
                    e.stopPropagation();
                }
            })

            $select.on('select2:select', function (e) {
                const questionId = e.params.data.id
                $.ajax({
                    method: 'GET',
                    url: 'test/question/' + questionId,
                    success: function(response) {
                        const questionId = response.questionId
                        const question = response.questionText
                        const answers = response.answers
                        const $preview = $('.test-preview')

                        const $div = $('<div>')
                            .addClass('w-100 mb-3 question-container')
                            .attr('id', 'questionId_'+questionId)

                        const $questionDIV = $('<div>').addClass('font-weight-bold').text(question)
                        let counter = 1

                        $div.append($questionDIV)

                        answers.forEach(function(answerText) {
                            const $answerDIV = $('<div>').text(counter++ +'. '+answerText)
                            $div.append($answerDIV)

                        })
                        $div.append('<hr>')
                        questionIds.push(questionId)
                        $preview.append($div)
                        $saveButton.prop('disabled', false)
                    },
                })
            });

            $select.on('select2:unselect', function (e) {
                const questionId = parseInt(e.params.data.id)
                $('#questionId_' + questionId).remove()
                questionIds = questionIds.filter(item => item !== questionId)
                if (!questionIds.length) {
                    $saveButton.prop('disabled', true)
                }
            });
        });
    </script>
@endpush
