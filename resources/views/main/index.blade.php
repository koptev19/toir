@extends('layouts.toir')

@section('content')

<div class="m-auto pt-5" style="width:400px;">
@if(count($workshops) > 0)
    <h5 class="text-center">Выберите цех</h5>
    <ul class="list-group mt-4">
    @foreach($workshops as $workshop)
        <li class="list-group-item"><a href="{{ route('toir', $workshop) }}">{{ $workshop->name }}</a></li>
    @endforeach
    </ul>
@else
    К сожалению, Вы не подключены к системе
@endif
</div>

@endsection