<table class="table">
    <thead>
    <tr>
        <th class="th-sm text-center">Идентификатор</th>
        <th class="th-sm text-center">Название</th>
        <th class="th-sm text-center">Таймер</th>
        <th class="th-sm text-center">Процент</th>
        <th class="th-sm text-center">Попытки</th>
        <th class="th-sm text-center">Дата создания</th>
        <th class="th-sm text-center">Кем создано</th>
        <th class="th-sm text-center">Дата обновления</th>
        <th class="th-sm text-center">Кем обновлено</th>
    </tr>
    </thead>
    <tbody
        class="table-striped"
    >
    @foreach($tests as $test)
        <tr
            @if(!$test->is_actual) class="bg-danger" @endif
        >
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
        </tr>
    @endforeach
    </tbody>
</table>
