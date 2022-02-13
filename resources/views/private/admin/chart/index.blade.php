@extends('dashboard')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fa fa-pie-chart" aria-hidden="true"></i>
                        {{ __('pages.chart') }}
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
                        <li class="breadcrumb-item active">{{ __('pages.chart') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card p-3">
            <div class="d-flex justify-content-start option-panel">
                 Выберите пользователя&nbsp;<i class="fa fa-level-down" aria-hidden="true"></i>
            </div>
            <hr>
            <div class="container-fluid">
                <form>
                    <select name="userId" class="select2 w-100">
                        @foreach($departments as $department)
                            @if($department->users->count())
                                <optgroup label="{{ $department->name }}"></optgroup>
                                @foreach($department->users as $user)
                                    <option value="{{ $user->id }}">{{ $user->last_name }} {{ $user->name }}</option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                    <div class="d-flex justify-content-end">
                        <button id="showChartByUser" type="button" class="btn btn-dark mt-3">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            &nbsp;
                            Показать
                        </button>
                    </div>
                </form>

                <div id="charts"></div>
            </div>
        </div>
    </section>
@endsection

@push('css')
    <link rel="stylesheet" href="/css/admin/select2.min.css">
    <style>
        .chart-container{
            width: 500px;
            height: 500px;
            margin: auto auto 5rem;
        }
    </style>
@endpush

@push('js')
    <script src="/js/select2.min.js"></script>
    <script src="/js/chart.min.js"></script>
    <script>
        const $select = $('.select2')
        const $showChartByUser = $('#showChartByUser')

        $select.select2();

        $showChartByUser.click(function() {
            const data = $(this).parents('form:first').serializeArray()
            const id = data[0]['value']

            $.ajax({
                type: 'GET',
                dataType: 'JSON',
                url: "{{ route('show-charts-by-user-id') }}/" + id,
                success: function({data}) {
                    const container = $('#charts')
                    let isContained = false
                    container.empty()
                    const tests = data

                    for(let key in tests) {
                        const test = tests[key]
                        const name = test[0]
                        const result = test[1]
                        const canvas = $('<canvas>')
                        const wrapper = $('<div>')
                        const title = $('<h3>').addClass('mb-3 text-center').text(name)

                        if (result) {
                            isContained = true
                            wrapper.addClass('chart-container')
                            wrapper.append(title)

                            new Chart(
                                canvas[0]
                                    .getContext('2d'),
                                {
                                    type: 'pie',
                                    data: {
                                        labels: ['Верные ответы, %', 'Неверные ответы, %'],
                                        datasets: [{
                                            data: [
                                                parseInt(result),
                                                100 - parseInt(result)
                                            ],
                                            backgroundColor: [
                                                'rgba(75, 192, 192, 0.2)',
                                                'rgba(255, 99, 132, 0.2)',
                                            ],
                                            borderColor: [
                                                'rgba(75, 192, 192, 1)',
                                                'rgba(255, 99, 132, 1)',
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                }
                            );
                            wrapper.append(canvas)
                        }else {
                            wrapper.addClass('w-100')
                            const alert = $('<div>')
                                .addClass('alert alert-danger w-100')
                                .text('Тест не пройден')
                                .prepend(
                                    $('<i>').addClass('fa fa-bell mr-3')
                                )
                            wrapper.append(title)
                            wrapper.append(alert)
                        }
                        container.append(wrapper)
                        container.append($('<hr>'))
                    }

                    if (!isContained) {
                        const alert = $('<div>')
                            .addClass('alert alert-danger w-100 mt-5')
                            .text('У пользователя не найдено пройденных или назначенных тестов')
                            .prepend(
                                $('<i>').addClass('fa fa-bell mr-3')
                            )
                        container.append(alert)
                    }
                }
            })
        })
    </script>
@endpush
