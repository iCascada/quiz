@extends('dashboard')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                        {{ __('pages.tests-actual') }}
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
                        <li class="breadcrumb-item active">
                            {{ __('pages.tests-actual') }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card p-3">
            <div class="d-flex flex-wrap">
                @if($actualTests->count())
                    @foreach($actualTests as $test)
                    <div class="col-4">
                        <div
                            class="card test"
                        >
                            <div class="card-body">
                                <div>
                                    <h5 class="card-title">{{ $test->name }}</h5>
                                </div>
                                <hr style="margin-top: 50px">
                                <div class="mb-3">
                                    <div class="timer d-flex justify-content-start">
                                        @if($test->timer)
                                            <div>
                                                Время : {{ $test->timer }}&nbsp;<i class="fa fa-clock-o" aria-hidden="true"></i></div>
                                        @else
                                            <div>
                                                Время : не ограничено&nbsp;<i class="fa fa-times-circle-o" aria-hidden="true"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="d-flex justify-content-start">
                                        <div>
                                            Минимальный балл : {{ $test->passed_value }}&nbsp;<i class="fa fa-percent" aria-hidden="true"></i>
                                        </div>
                                    </div>

                                    <div class="timer d-flex justify-content-start">
                                        @if($test->attempt)
                                            <div>
                                                Попытки : {{ $test->attempt }}
                                            </div>
                                        @else
                                            <div>
                                                Попытки : не ограничено&nbsp;<i class="fa fa-times-circle-o" aria-hidden="true"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <hr style="margin-top: 25px">

                                    <div class="d-flex justify-content-start">
                                        <div>
                                            <b>Лучший результат</b> :
                                            @if(!is_null($test->user()->pivot->result))
                                                <span
                                                    class="@if($test->user()->pivot->result >= $test->passed_value) text-success @else text-danger @endif"
                                                >
                                                    {{ $test->user()->pivot->result }}
                                                &nbsp;<i class="fa fa-percent" aria-hidden="true"></i>
                                                </span>
                                            @else
                                                <span class="text-danger font-weight-bold">Результат не найден</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-start">
                                        <div>
                                            <b>Оставшихся попыток</b> :
                                            @if(is_null($test->attempt))
                                                Без ограничений
                                            @else
                                                @if(is_null($test->user()->pivot->attempt))
                                                    {{ $test->attempt }}
                                                @else
                                                    <span class="text-danger font-weight-bold">
                                                {{ $test->attempt - $test->user()->pivot->attempt }}
                                                </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <hr style="margin-top: 25px">

                                    <p class="card-text">
                                        <b>Создано:</b> {{ $test->createdBy->name }} {{ $test->createdBy->last_name }}
                                    </p>

                                </div>
                                @if(!is_null($test->user()->pivot->result))
                                    <button
                                        data-id="{{ $test->id }}"
                                        type="button"
                                        class="btn btn-dark showResult"
                                    >
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        &nbsp;
                                         Лучший результат
                                    </button>
                                @endif
                                @if($test->user()->pivot->attempt <= $test->attempt)
                                    <button
                                        data-id="{{ $test->id }}"
                                        type="button"
                                        class="btn btn-dark startTest"
                                    >
                                        <i class="fa fa-hourglass-half" aria-hidden="true"></i>
                                        &nbsp;
                                        Начать тест
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                @else
                    <div class="alert alert-info show-always w-100" role="alert">
                        <h4 class="alert-heading text-center">Информация!</h4>
                        <hr>
                        <p class="mb-0 text-center">
                            В настоящий момент для Вас нет тестов для прохождения
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('js')
<script>
    $(document).ready (function () {
        const $startTestButton = $('.startTest')
        const $resultButton = $('.showResult')

        $resultButton.click(function (e) {
            const id = $(e.target).data('id')

            $.alert({
                title: 'Результаты прохождения теста',
                theme: 'material',
                columnClass: 'm',
                backgroundDismiss: false,
                content: function () {
                    const self = this;
                    return $.ajax({
                        url: "{{ route('passed-result-modal') }}",
                        method: 'get',
                        data: {id: id, _token: '{{ csrf_token() }}'}
                    }).done(function (response) {
                        self.setContent(response)
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

        $startTestButton.click(function (e) {
            const id = $(e.target).data('id')

            $.confirm({
                title: 'Подтвердите действие',
                theme: 'material',
                columnClass: 'm',
                backgroundDismiss: false,
                content: "После продолжения будет списана <b>одна попытка</b> для прохождения теста, Вы готовы приступить ?",
                buttons: {
                    confirm: {
                        text: 'Подтвердить',
                        btnClass: 'btn-link',
                        keys: ['enter'],
                        action: function () {
                            const cancel = this.$$cancel
                            $.ajax({
                                url: "{{ route('actual-attempt-inc') }}",
                                method: 'patch',
                                data: {id: id, _token: '{{ csrf_token() }}'}
                            }).done(function () {
                                cancel.trigger('click')
                                openTest(id)
                            })
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

        function openTest(id) {
            $.alert({
                theme: 'supervan',
                columnClass: 'xl',
                backgroundDismiss: false,
                title: false,
                content: function () {
                    const self = this;
                    return $.ajax({
                        url: "{{ route('process-test-modal') }}",
                        method: 'get',
                        data: {id: id, _token: '{{ csrf_token() }}'}
                    }).done(function (response) {
                        self.setContent(response)
                    })
                },
                buttons: {
                    cancel: {
                        text: "<i class='fa fa-check'></i> Завершенить тестирование",
                        keys: ['esc'],
                        action: function () {
                            confirmExit(this)
                            return false
                        }
                    },

                }
            });
        }

        function confirmExit(testObject) {
            $.confirm({
                title: 'Подтвердите действие',
                content: "Вы действительно хотите закончить ?",
                buttons: {
                    confirm: {
                        action: function (){
                            const data = testObject.$content.find('#testForm').serializeArray()
                            data.push({name: "_token", value: "{{csrf_token()}}" })
                            $.ajax({
                                url: "{{ route('check-test') }}",
                                method: 'post',
                                data: data
                            }).done(function () {
                                testObject.close()
                                location.reload()
                            })
                        },
                        text: 'Подтвердить',
                        btnClass: 'btn-link',
                        keys: ['enter']
                    },
                    cancel: {
                        text: 'Выход',
                        btnClass: 'btn-link',
                        keys: ['esc']
                    },
                }
            });
        }
    })
</script>
@endpush
