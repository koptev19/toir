@extends('equipments._layout')

@section('content_equipment')

<div class='mb-5'>
    <a href="{{ route('equipments.create', ['parent' => $equipment]) }}" class='btn btn-outline-primary'><i class="fas fa-plus me-2"></i>  Добавить дочерний элемент</a>
    @if($equipment->type === \App\Models\Equipment::TYPE_WORKSHOP)
        <a href="{{ route('toir', $equipment->workshop) }}" class='btn btn-outline-primary ms-4'>
            <i class="fas fa-clipboard-list me-2"></i> Планирование ТОиР
        </a>
    @endif
</div>

@include('equipments._operations')

<h3 class="my-5">
    {{ $equipment->name }} 
    <a href="{{ route('equipments.edit', $equipment) }}" class="h6 link-dark ms-4"><i class="fas fa-pencil-alt"></i></a>
</h3>

<div class="row mb-3">
    <div class='col-4 col-md-2'>Краткое название:</div>
    <div class="col-8 col-md-10">{{ $equipment->short_name }}</div>
</div>

<div class="row mb-3">
    <div class='col-4 col-md-2'>Тип:</div>
    <div class="col-8 col-md-10">{{ \App\Models\Equipment::getTypes()[$equipment->type] }}</div>
</div>

<div class="row mb-3">
    <div class='col-4 col-md-2'>Начальник:</div>
    <div class="col-8 col-md-10">{{ optional($equipment->manager)->fullname }}</div>
</div>

<div class="row mb-3">
    <div class='col-4 col-md-2'>Механик:</div>
    <div class="col-8 col-md-10">{{ optional($equipment->mechanic)->fullname }}</div>
</div>

<div class="row mb-3">
    <div class='col-4 col-md-2'>Инвентарный номер:</div>
    <div class="col-8 col-md-10">{{ $equipment->inventory_number }}</div>
</div>

<div class="row mb-3">
    <div class='col-4 col-md-2'>Дата ввода в экспуатацию:</div>
    <div class="col-8 col-md-10">{{ $equipment->enter_date_formatted }}</div>
</div>

<div class="row mb-3">
    <div class='col-4 col-md-2'>Описание:</div>
    <div class="col-8 col-md-10">{!! $equipment->description !!}</div>
</div>

<div class="row mb-3">
    <div class='col-4 col-md-2'>Внешний вид:</div>
    <div class="col-8 col-md-2">
        {!! \App\Helpers\ImageHelper::linkImgTag($equipment->photo, [], ["class" => "img-fluid"]) !!}
    </div>
</div>

<div class="row mb-3">
    <div class='col-4 col-md-2'>Схема:</div>
    <div class="col-8 col-md-2">
        {!! \App\Helpers\ImageHelper::linkImgTag($equipment->sketch, [], ["class" => "img-fluid"]) !!}
    </div>
</div>

<div class="row mb-3">
    <div class='col-4 col-md-2'>Документация:</div>
    <div class="col-8 col-md-10">
        @foreach($equipment->documents as $document)
            <a href="{{ \App\Helpers\FileHelper::url($document) }}" class="d-block mb-2" target=_blank>{{ $document->original_name }}</a>
        @endforeach
    </div>
</div>

@endsection