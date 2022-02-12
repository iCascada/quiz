@extends('dashboard')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fa fa-suitcase" aria-hidden="true"></i>
                        {{ __('pages.tests-management') }}
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
                        <li class="breadcrumb-item active">{{ __('pages.tests-management') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card p-3">
            <div class="d-flex justify-content-end option-panel">
                <button class="btn btn-link text-dark" onclick=location.href="{{ route('tests') }}">
                    <i class="fa fa-external-link" aria-hidden="true"></i>
                    &nbsp;
                    Создать новый тест
                </button>
            </div>
            <hr>
            <div class="container-fluid">
                @if($tests->count())
                    <table id="table" class="table table-sm stripted">
                        <thead class="thead-dark">
                        <tr>
                            <th></th>
                            <th class="th-sm text-center">Идентификатор</th>
                            <th class="th-sm text-center">Название</th>
                            <th class="th-sm text-center">Таймер</th>
                            <th class="th-sm text-center">Процент</th>
                            <th class="th-sm text-center">Попытки</th>
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
                        @foreach($tests as $test)
                            <tr
                                @if(!$test->is_actual) class="bg-danger" @endif
                            >
                                <td class="position-relative"></td>
                                <td class="text-center id">{{ $test->id }}</td>
                                <td class="text-center">{{ $test->name }}</td>
                                <td class="text-center @if($test->timer) font-weight-bold @endif">
                                    @if(!$test->timer) Таймер не установлен
                                    @else {{$test->timer}} минут
                                    @endif
                                </td>
                                <td class="text-center font-weight-bold">
                                    {{$test->passed_value}} %
                                </td>
                                <td class="text-center @if($test->attempt) font-weight-bold @endif">
                                    @if(!$test->attempt) Без ограничений
                                    @else {{$test->attempt}}
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{\Illuminate\Support\Carbon::make($test->created_at)->format('d.m.Y') }}
                                </td>
                                <td class="text-center">
                                    {{ $test->createdBy->name }}

                                    {{ $test->createdBy->last_name }}
                                </td>
                                <td class="text-center">
                                    {{\Illuminate\Support\Carbon::make($test->updated_at)->format('d.m.Y') }}
                                </td>
                                <td class="text-center">
                                    @if($test->updatedBy)
                                        {{ $test->updatedBy->name }}

                                        {{ $test->updatedBy->last_name }}
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex operations justify-content-around">
                                        @if($test->is_actual)
                                        <button class="btn btn-link text-dark lockButton">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                        </button>
                                        @else
                                        <button class="btn btn-link text-dark unlockButton">
                                            <i class="fa fa-undo" aria-hidden="true"></i>
                                        </button>
                                        @endif
                                        <button class="btn btn-link text-dark showButton">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </button>
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
                    <div class="alert alert-info show-always w-100" role="alert">
                        <h4 class="alert-heading text-center">Информация!</h4>
                        <hr>
                        <p class="mb-0 text-center">
                            В настоящий момент активных тестов <b>не найдено</b>
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
            const $deleteButton = $('.deleteButton')
            const $showButton = $('.showButton')
            const $lockButton = $('.lockButton')
            const $unlockButton = $('.unlockButton')
            const $body = $('body')

            $lockButton.click(function(e) {
                const id = getId(e)

                $.confirm({
                    title: 'Подтвердите действие',
                    theme: 'material',
                    columnClass: 'm',
                    backgroundDismiss: false,
                    content: 'Тест станет недоступен для прохождения пользователями ?',
                    buttons: {
                        confirm: {
                            text: 'Подтвердить',
                            btnClass: 'btn-link',
                            keys: ['enter'],
                            action: function () {
                                $.ajax({
                                    url: "{{ route('lock-test') }}",
                                    method: 'post',
                                    dataType: 'json',
                                    data: { id: id, _token: "{{ csrf_token() }}" },
                                    success: function () {
                                        location.reload()
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
            $unlockButton.click(function(e) {
                const id = getId(e)

                $.confirm({
                    title: 'Подтвердите действие',
                    theme: 'material',
                    columnClass: 'm',
                    backgroundDismiss: false,
                    content: 'Данный тест станет доступным для прохождения ?',
                    buttons: {
                        confirm: {
                            text: 'Подтвердить',
                            btnClass: 'btn-link',
                            keys: ['enter'],
                            action: function () {
                                $.ajax({
                                    url: "{{ route('unlock-test') }}",
                                    method: 'post',
                                    dataType: 'json',
                                    data: { id: id, _token: "{{ csrf_token() }}" },
                                    success: function () {
                                        location.reload()
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

            $body.on('click', '.deleteSelected', function() {
                $.confirm({
                    title: 'Подтвердите действие',
                    theme: 'material',
                    columnClass: 'm',
                    backgroundDismiss: false,
                    content: 'Выбранные тесты будут удалены, продолжить ?',
                    buttons: {
                        confirm: {
                            text: 'Подтвердить',
                            btnClass: 'btn-link',
                            keys: ['enter'],
                            action: function () {
                                $.ajax({
                                    url: "{{ route('delete-all-tests') }}",
                                    method: 'delete',
                                    dataType: 'json',
                                    data: { ids: [...selected], _token: "{{ csrf_token() }}" },
                                    success: function () {
                                        location.reload()
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
            $showButton.click(function(e) {
                const id = getId(e)
                $.confirm({
                    title: false,
                    theme: 'material',
                    columnClass: 'xl',
                    backgroundDismiss: true,
                    content: function () {
                        const self = this;
                        return $.ajax({
                            url: "{{ route('show-test-modal') }}",
                            method: 'post',
                            data: {id: id, _token: '{{ csrf_token() }}'}
                        }).done(function (response) {
                            self.setContent(response)
                            self.setTitle(response.title)
                        })
                    },
                    buttons: {
                        cancel: {
                            text: 'Выход',
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
                    content: 'Данный тест будет удален, продолжить ?',
                    buttons: {
                        confirm: {
                            text: 'Подтвердить',
                            btnClass: 'btn-link',
                            keys: ['enter'],
                            action: function () {
                                $.ajax({
                                    url: "{{ route('delete-test') }}",
                                    method: 'delete',
                                    dataType: 'json',
                                    data: { id: id, _token: "{{ csrf_token() }}" },
                                    success: function () {
                                        location.reload()
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
            $editButton.click(function (e) {
                const id = getId(e)

                $.confirm({
                    title: 'Редактирование теста',
                    theme: 'material',
                    columnClass: 'xl',
                    backgroundDismiss: false,
                    content: function () {
                        const self = this;
                        return $.ajax({
                            url: "{{ route('edit-test-modal') }}",
                            method: 'post',
                            data: {id: id, _token: '{{ csrf_token() }}'}
                        }).done(function (response) {
                            self.setContent(response)
                            self.setTitle(response.title)
                        })
                    },
                    buttons: {
                        confirm: {
                            text: 'Сохранить',
                            btnClass: 'btn-link',
                            keys: ['enter'],
                            action: function () {
                                const self = this
                                const form = self.$content.find('form')
                                const data = form.serializeArray()

                                const $categoryContainer = self.$content.find('#categoryContainer')
                                const questionCount = $categoryContainer.find('.select2-selection__choice').length
                                const $questionSelect2  = $categoryContainer.find('.select2-selection')

                                let isValid = true

                                self.$content
                                    .find('#userContainer')
                                    .find('.select2-selection')
                                    .addClass('valid')

                                Array.prototype.filter.call(form, function (form) {
                                    if (
                                        form.checkValidity() === false ||
                                        !questionCount
                                    ) {
                                        isValid = false
                                    }
                                    form.classList.add('was-validated');
                                });


                                if (isValid) {
                                    $questionSelect2.addClass('valid')

                                    data.push({name: "_token", value: "{{csrf_token()}}"})

                                    $.ajax({
                                        url: "{{ route('update-test-request') }}",
                                        method: 'patch',
                                        dataType: 'json',
                                        data: $.param(data),
                                        success: function () {
                                            setTimeout(() => location.reload(), 500)
                                        }
                                    })
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
