<table class="table">
    <thead>
    <tr>
        <th class="th-sm text-center">Идентификатор</th>
        <th class="th-sm text-center">Название категории</th>
        <th class="th-sm text-center">Дата создания</th>
        <th class="th-sm text-center">Кем создано</th>
        <th class="th-sm text-center">Дата обновления</th>
        <th class="th-sm text-center">Кем обновлено</th>
    </tr>
    </thead>
    <tbody>
    @foreach($categories as $category)
        <tr>
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
        </tr>
    @endforeach
    </tbody>
</table>
