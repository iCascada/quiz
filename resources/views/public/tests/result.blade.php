@if(is_null($test->pivot->result) && is_null($test->pivot->attempt))
    <div class="mt-5">
        <div class="alert alert-danger show-always w-100" role="alert">
            <h4 class="alert-heading text-center font-weight-bold">Внимание!</h4>
            <hr>
            <p class="mb-0 text-center">
                Данный тест Вами не пройден, при необходимости свяжитесь с администратором
            </p>
        </div>
    </div>
@else
    <div class="mt-3">
        <div class="justify-content-center d-flex">
            <div class="mt-1">
            <span
                style="font-size: 1.3rem"
                class="font-weight-bold"
            >
                Ваш результат:
            </span>
            </div>
            &nbsp;&nbsp;&nbsp;
            <span
                class="@if($test->pivot->result >= $test->passed_value) text-success @else text-danger @endif"
                style="font-size: 1.6rem"
            >
            {{ $test->pivot->result }} <i class="fa fa-percent" aria-hidden="true"></i>
        </span>
        </div>
        <hr>
        <div class="justify-content-center d-flex">
            <div class="mt-1">
            <span
                style="font-size: 1.3rem"
                class="font-weight-bold"
            >
                Количество использованных попыток:
            </span>
            </div>
            &nbsp;&nbsp;&nbsp;
            <span
                style="font-size: 1.6rem"
            >
            {{ $test->pivot->attempt }}
        </span>
        </div>
        <hr>
        <div
            style="margin: auto; width: 450px; height: 450px"
        >
            <canvas id="cnv"></canvas>
        </div>
    </div>
@endif

<script src="/js/chart.min.js"></script>
<script>
    new Chart(
        document
        .getElementById('cnv')
        .getContext('2d'),
        {
            type: 'doughnut',
            data: {
                labels: ['Верные ответы, %', 'Неверные ответы, %'],
                datasets: [{
                    data: [
                        parseInt("{{ $test->pivot->result }}"),
                        100 - parseInt("{{ $test->pivot->result }}")
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
</script>
