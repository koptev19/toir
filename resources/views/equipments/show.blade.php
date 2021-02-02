@extends('equipments._layout')

@section('content_equipment')

<div class='mb-5'>
    <a href="{{ route('equipments.create', ['parent' => $equipment]) }}" class='btn btn-outline-primary'><i class="fas fa-plus me-2"></i>  Добавить дочерний элемент</a>
    @if($equipment->type === \App\Models\Equipment::TYPE_WORKSHOP)
        <a href="{{ route('home', $equipment->workshop) }}" class='btn btn-outline-primary ms-4'>
            <i class="fas fa-clipboard-list me-2"></i> Планирование ТОиР
        </a>
    @endif
</div>

@include('equipments._plans')

<h3 class="my-5">
    {{ $equipment->name }} 
    <a href="{{ route('equipments.edit', $equipment) }}" class="h6 link-dark ms-4"><i class="fas fa-pencil-alt"></i></a>
</h3>

<div class="row mb-3">
    <div class='col-4 col-md-2'>Тип:</div>
    <div class="col-8 col-md-10">{{ \App\Models\Equipment::getTypes()[$equipment->type] }}</div>
</div>

@endsection