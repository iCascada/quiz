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
        <th class="th-sm text-center">Имя</th>
        <th class="th-sm text-center">Фамилия</th>
        <th class="th-sm text-center">Департамент</th>
        <th class="th-sm text-center">Тесты (результат)</th>
    </tr>
    </thead>
    <tbody
        class="table-striped"
    >
    @foreach($users as $user)
         <tr>
             <td>{{ $user->id }}</td>
             <td>{{ $user->name }}</td>
             <td>{{ $user->last_name }}</td>
             <td>{{ $user->department->name }}</td>
             <td>

                 @foreach($user->tests as $key => $test)
                     {{ ++$key }}. {{ $test->name }} ({{ $test->user()->pivot->result }} %)
                     <br>
                 @endforeach

             </td>
         </tr>
    @endforeach
    </tbody>
</table>
