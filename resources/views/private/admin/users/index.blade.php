@extends('dashboard')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fa fa-users" aria-hidden="true"></i>
                        {{ __('pages.users-management') }}
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
                        <li class="breadcrumb-item active">{{ __('pages.users-management') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card p-3">
            <div class="container-fluid">
                <table id="table" class="table table-sm stripted">
                    <thead class="thead-dark">
                    <tr>
                        <th class="th-sm text-center">Идентификатор</th>
                        <th class="th-sm text-center">Имя</th>
                        <th class="th-sm text-center">Фамилия</th>
                        <th class="th-sm text-center">Электронная почта</th>
                        <th class="th-sm text-center">Подтвержден</th>
                        <th class="th-sm text-center">Департамент</th>
                        <th class="th-sm text-center">Группа</th>
                        <th class="th-sm text-center">Регистрация</th>
                        <th class="th-sm text-center">Действия</th>
                    </tr>
                    </thead>
                    <tbody
                        class="table-striped"
                    >
                    @foreach($users as $user)
                        <tr @if($user->is_blocked) class="bg-danger" @endif >
                            <td class="text-center">{{ $user->id }}</td>
                            <td class="text-center">{{ $user->name }}</td>
                            <td class="text-center">{{ $user->last_name }}</td>
                            <td class="text-center">{{ $user->email }}</td>
                            <td class="text-center"> @if($user->email_verified_at)
                                    <i class="fa fa-check-square-o text-success"></i>
                                @else
                                    <i class="fa fa-minus-square text-danger"></i>
                                @endif
                            </td>
                            <td class="text-center">{{ $user->department->name }}</td>
                            <td class="text-center">{{ $user->role->name }}</td>
                            <td class="text-center">{{ \Illuminate\Support\Carbon::make($user->created_at)->format('d.m.Y') }}</td>
                            <td>
                                <div class="d-flex operations justify-content-around">
                                    @if($user->is_blocked)
                                        <button class="btn btn-link text-dark lockButton">
                                            <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-link text-dark lockButton">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-link text-dark editButton">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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

            $($tableId.hash()).DataTable({
                "language": {
                    "url": '/js/datatable-rus.json'
                },
                "pagingType": "numbers"
            });

            const $editButton = $('.editButton')
            const $lockButton = $('.lockButton')

            $lockButton.click(function(e) {
                const id = $(e.target)
                    .parents('tr')
                    .children('td:first-child')
                    .text()

                const target = e.target
                let action, content, className

                if (target.tagName === 'BUTTON'){
                    className = $(target).children('i').attr('class').split(' ')[1]
                }else{
                    className = $(target).attr('class').split(' ')[1]
                }

                className === 'fa-lock' ?
                    action = 'lock' :
                    action ='unlock'

                if(action === 'lock'){
                    content = "Вы действительно хотите <b>заблокировать</b> указанного пользователя ?"
                }else{
                    content = "Вы действительно хотите <b>разблокировать</b> указанного пользователя ?"
                }

                $.confirm({
                    title: 'Подтвердите действие',
                    theme: 'material',
                    columnClass: 'm',
                    backgroundDismiss: false,
                    content: content,
                    buttons: {
                        confirm: {
                            text: 'Подтвердить',
                            btnClass: 'btn-link',
                            keys: ['enter'],
                            action: function (button) {
                                $.ajax({
                                    url: "{{ route('access-management') }}",
                                    method: 'post',
                                    dataType: 'json',
                                    data: { id: id, action: action, _token: "{{ csrf_token() }}" },
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

            $editButton.click(function (e) {
                const id = $(e.target)
                    .parents('tr')
                    .children('td:first-child')
                    .text()

                $.confirm({
                    title: 'Редактирование пользователя',
                    theme: 'material',
                    columnClass: 'xl',
                    backgroundDismiss: false,
                    content: function () {
                        const self = this;
                        return $.ajax({
                            url: "{{ route('users-edit-page') }}",
                            method: 'post',
                            data: {id: id, _token: '{{ csrf_token() }}'}
                        }).done(function (response) {
                            self.setContent(response)
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
                                        url: "{{ route('users-update') }}",
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
        });
    </script>
@endpush
