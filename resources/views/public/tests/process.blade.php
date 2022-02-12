<div class="p-3">
    <form id="testForm" data-id="{{ $test->id }}">
        <input type="hidden" name="id" value="{{ $test->id }}">
        @if($test->timer)
            <div class="d-flex justify-content-end mb-3">
                <div id="countdown"></div>
            </div>
        @endif
        <div class="d-flex justify-content-end">
            <div class="test-info">
                <div>
                    <b>Оставшихся попыток: </b> <span class="text-danger font-weight-bold">{{ $test->attempt - $test->user()->pivot->attempt }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <div>
                <b>Лучший результат</b> :
                @if(!is_null($test->user()->pivot->result))
                    <span
                        class="@if($test->user()->pivot->result >= $test->result) text-success @else text-danger @endif"
                    >
                        {{ $test->user()->pivot->result }}&nbsp;<i class="fa fa-percent" aria-hidden="true"></i>
                    </span>
                @else
                    <span class="text-danger font-weight-bold">Результат не найден</span>
                @endif
            </div>
        </div>
        <h3 class="text-center font-weight-bold mb-5">{{ $test->name }}</h3>
        @foreach($test->questions as $question)
            <div class="mb-3">
                <h6 class="mb-3"><i><b>{{ $question->text }} ?</b></i></h6>
                <hr>
                @foreach($question->answers as $answer)
                    <div class="form-group mb-2 form-check">
                        <input
                            name="{{ $question->id }}-{{ $answer->id }}"
                            class="form-check-input me-2" type="checkbox"
                            id="{{ $answer->id }}"
                        >
                        &nbsp;
                        <label for="{{ $answer->id }}">{{ $answer->text }}</label>
                    </div>
                @endforeach
            </div>
        @endforeach
    </form>
</div>

@if($test->timer)
<script src="/js/jquery.countdown360.min.js"></script>
<script>
    const countdown = $("#countdown").countdown360({
        radius      : 30,
        seconds     : parseInt({{ $test->timer }}) * 360,
        fontColor   : '#000',
        fontSize    : 20,
        fillStyle   : "#fff",
        strokeStyle : "#dc3545",
        label       : ["second","секунд"],
        strokeWidth : 2,
        autostart   : false,
        smooth      : true,
        onComplete  : function () {

            $.alert({
                title: 'Тест завершен',
                theme: 'material',
                columnClass: 'm',
                backgroundDismiss: false,
                content: 'Через несколько секунд Вы будете перенаправлены на страницу управления',
                autoClose: 'cancel|5000',
                buttons: {
                    cancel: {
                        action: function(){
                            location.reload()
                        },
                        text: 'Выход',
                        btnClass: 'btn-link',
                        keys: ['esc']
                    },
                }
            });

            setTimeout(function() {
                const data = $('#testForm').serializeArray()
                data.push({name: "_token", value: "{{csrf_token()}}" })

                $.ajax({
                    url: "{{ route('check-test') }}",
                    method: 'post',
                    data: data
                }).done(function () {
                    location.reload()
                })
            }, 5000)
        }
    });
    countdown.start();
</script>
@endif
