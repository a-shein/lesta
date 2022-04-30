@extends('layouts.app')
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
{{Form::open(['route' => 'fibonacci', 'method' => 'GET'])}}
    {{Form::number('from', $from)}}
    {{Form::number('to', $to)}}
    {{Form::submit('Рассчитать')}}
{{Form::close()}}

@if ($result)
    <div>Числа Фибоначчи в выбранном диапазоне: {{$result}}</div>
@endif
