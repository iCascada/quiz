<table class="table">
    <thead>
    <tr>
        <th class="th-sm text-center">Идентификатор</th>
        <th class="th-sm text-center">Категория вопроса</th>
        <th class="th-sm text-center w-350px">Текст</th>
        <th class="th-sm text-center w-450px">Ответы</th>
        <th class="th-sm text-center">Дата создания</th>
        <th class="th-sm text-center">Кем создано</th>
        <th class="th-sm text-center">Дата обновления</th>
        <th class="th-sm text-center">Кем обновлено</th>
    </tr>
    </thead>
    <tbody
        class="table-striped"
    >
    @foreach($questions as $question)
        <tr>
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
        </tr>
    @endforeach
    </tbody>
</table>
