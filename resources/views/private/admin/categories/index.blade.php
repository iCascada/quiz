@extends('dashboard')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fa fa-suitcase" aria-hidden="true"></i>
                        {{ __('pages.categories') }}
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
                        <li class="breadcrumb-item active">{{ __('pages.categories') }}</li>
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
                        @if(!$categories->count())
                            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                        @else
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        @endif
                        &nbsp;
                        Добавить категорию
                    </button>
                </div>
                <hr>
                @if($categories->count())
                <table id="table" class="table table-sm stripted">
                    <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th class="th-sm text-center">Идентификатор</th>
                        <th class="th-sm text-center">Название категории</th>
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
                    @foreach($categories as $category)
                        <tr>
                            <td class="position-relative"></td>
                            <td class="text-center id">{{ $category->id }}</td>
                            <td class="text-center">{{ $category->name }}</td>
                            <td class="text-center">{{ \Illuminate\Support\Carbon::make($category->created_at)->format('d.m.Y') }}</td>
                            <td class="text-center">
                                {{ $category->createdBy->name }}

                                {{ $category->createdBy->last_name }}
                            </td>
                            <td class="text-center">{{ \Illuminate\Support\Carbon::make($category->updated_at)->format('d.m.Y') }}</td>
                            <td class="text-center">
                                @if($category->updatedBy)
                                    {{ $category->createdBy->name }}

                                    {{ $category->updatedBy->last_name }}
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
                            Необходимо добавить категории к вопросам тестов. Создание теста невозможно продолжить без указания хотя бы одной категории.
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
            const $addButton = $('.addButton')
            const $deleteButton = $('.deleteButton')
            const $body = $('body')

            $body.on('click', '.deleteSelected', function() {
                $.confirm({
                    title: 'Подтвердите действие',
                    theme: 'material',
                    columnClass: 'm',
                    backgroundDismiss: false,
                    content: 'Вы действительно хотите удалить выбранные элементы ? Удаление категорий удалит все связанные вопросы и тесты. Данное действие является <span class="font-weight-bold text-danger">необратимым!</span>',
                    buttons: {
                        confirm: {
                            text: 'Подтвердить',
                            btnClass: 'btn-link',
                            keys: ['enter'],
                            action: function (button) {
                                $.ajax({
                                    url: "{{ route('delete-all-category-request') }}",
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
                    content: 'Вы действительно хотите удалить данную категория ? Это действие приведет к удалению всех связанных с категорией вопросов и тестов. Данное действие является <span class="font-weight-bold text-danger">необратимым!<span>',
                    buttons: {
                        confirm: {
                            text: 'Подтвердить',
                            btnClass: 'btn-link',
                            keys: ['enter'],
                            action: function (button) {
                                $.ajax({
                                    url: "{{ route('delete-category-request') }}",
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
                    title: 'Добавление новой категории',
                    theme: 'material',
                    columnClass: 'xl',
                    backgroundDismiss: false,
                    content: function () {
                        const self = this;
                        return $.ajax({
                            url: "{{ route('add-category-modal') }}",
                            method: 'post',
                            data: {_token: '{{ csrf_token() }}'}
                        }).done(function (response) {
                            self.setContent(response)
                        })
                    },
                    buttons: {
                        confirm: {
                            text: 'Сохранить',
                            btnClass: 'btn-link',
                            action: function (button) {
                                const self = this
                                const form = self.$content.find('form')
                                let isValid = true

                                Array.prototype.filter.call(form, function(form) {
                                    if (form.checkValidity() === false) {
                                        isValid = false
                                    }
                                    form.classList.add('was-validated');
                                });

                                if(isValid){
                                    const data = form.serializeArray()
                                    data.push({name: "_token", value: "{{csrf_token()}}" })
                                    $.ajax({
                                        url: "{{ route('add-category-request') }}",
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
                    title: 'Редактирование категории',
                    theme: 'material',
                    columnClass: 'xl',
                    backgroundDismiss: false,
                    content: function () {
                        const self = this;
                        return $.ajax({
                            url: "{{ route('edit-category-modal') }}",
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
                            action: function (button) {
                                const self = this
                                const form = self.$content.find('form')
                                let isValid = true

                                Array.prototype.filter.call(form, function(form) {
                                    if (form.checkValidity() === false) {
                                        isValid = false
                                    }
                                    form.classList.add('was-validated');
                                });

                                if(isValid){
                                    const data = form.serializeArray()
                                    data.push({name: "_token", value: "{{csrf_token()}}" })
                                    $.ajax({
                                        url: "{{ route('update-category-request') }}",
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
