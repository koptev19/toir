@extends('equipments._layout')

@section('content_equipment')
<form action="{{ route('equipments.update', $equipment) }}" method="post">
    @csrf
    @method('put')

    <h3 class="mb-5">Редактирование оборудования</h3>

    <div class="row mb-3">
        <div class='col-4 col-md-2'>Тип:</div>
        <div class="col-10">
        @if ($equipment->type === \App\Models\Equipment::TYPE_WORKSHOP || $equipment->type === \App\Models\Equipment::TYPE_LINE)
            <input type="text" value="{{ \App\Models\Equipment::getTypes()[$equipment->type] }}" class='form-control bg-light' readonly>
        @else
            <select class="form-select" name="type">
            @foreach(\App\Models\Equipment::getTypes() as $keyType => $nameType)
                @if($key !== \App\Models\Equipment::TYPE_WORKSHOP && $key !== \App\Models\Equipment::TYPE_LINE)
                    <option value='{{ $keyType }}' {{ $keyType === $equipment->type ? 'selected' : '' }}>{{ $nameType }}</option>
                @endif
            @endforeach
            </select>
            @error('type') <span class="text-danger">{{ $message }}</span> @enderror
        @endif
        </div>
    </div>
    <div class="row mb-3">
        <div class='col-4 col-md-2'>Наименование:</div>
        <div class="col-10">
            <input type="text" name="name" value="{{ $equipment->name }}" class='form-control' required>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class='mt-4 row'>
        <div class="col">
            <input type="submit" class='btn btn-primary' value="Сохранить">
        </div>
        <div class="col-6 text-end">
            <a href="{{ route('equipments.index') }}" class="btn btn-outline-secondary">Отмена</a>
        </div>
    </div>
</form>
@endsection