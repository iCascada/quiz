@extends('pdf.base')


<div class="info">
    <small>
        <i>{{ $filename }}</i>
        <br>
        <b>Дата: {{ \Illuminate\Support\Carbon::now()->format('d.m.Y H:s') }}</b>
    </small>
</div>

<hr>

<table class="table">
    <thead>
    <tr>
        <th class="th-sm text-center">Идентификатор</th>
        <th class="th-sm text-center">Название теста</th>
        <th class="th-sm text-center">Пользователи</th>
    </tr>
    </thead>
    <tbody
        class="table-striped"
    >
    @foreach($tests as $test)
         <tr>
             <td>{{ $test->id }}</td>
             <td>{{ $test->name }}</td>
             <td>

                 @foreach($test->users as $key => $user)
                     {{ ++$key }}. {{ $user->last_name }} {{ $user->name }} ({{ $user->department->name }})
                     <br>
                 @endforeach

             </td>
         </tr>
    @endforeach
    </tbody>
</table>
