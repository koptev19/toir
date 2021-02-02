@extends('layouts.main')

@section('content')

<div class="row mb-3">
    <div class="col">
        <h3>Планирование ремонтов</h3>
    </div>
    <div class="col text-end">
        <a href="#" class="btn btn-outline-info"><i class="fas fa-chart-line"></i> Аналитика</a>
        <a href="#" class="btn btn-outline-info"><i class="fas fa-hammer"></i> Оборудование</a>
        <a href="#" class="btn btn-outline-info"><i class="fas fa-align-justify"></i> Журналы</a>
        <a href="#" class="btn btn-outline-info"><i class="fas fa-plus"></i> Добавить операции</a>
    </div>
</div>

@include('home.filter')

@include('home.table1')

@endsection