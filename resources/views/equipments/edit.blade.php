@extends('equipments._layout')

@section('content_equipment')
<form action="{{ route('equipments.update', $equipment) }}" method="post" enctype="multipart/form-data">
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
                @if($keyType !== \App\Models\Equipment::TYPE_WORKSHOP && $keyType !== \App\Models\Equipment::TYPE_LINE)
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
            <input type="text" name="name" value="{{ old('name', $equipment->name) }}" class="form-control" required>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class='col-4 col-md-2'>Краткое название:</div>
        <div class="col-8 col-md-10">
            <input type="text" name="short_name" value="{{ old('short_name', $equipment->short_name) }}" class="form-control">
            @error('short_name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class='col-4 col-md-2'>Начальник:</div>
        <div class="col-8 col-md-10">
            <select name="manager_id" class="form-select">
                <option value=""></option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @if($user->id === old('manager_id', $equipment->manager_id)) @endif>{{ $user->fullname }}</option>
                @endforeach
            </select>
            @error('manager_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class='col-4 col-md-2'>Механик:</div>
        <div class="col-8 col-md-10">
            <select name="mechanic_id" class="form-select">
                <option value=""></option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @if($user->id === old('mechanic_id', $equipment->mechanic_id)) @endif>{{ $user->fullname }}</option>
                @endforeach
            </select>
            @error('mechanic_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class='col-4 col-md-2'>Инвентарный номер:</div>
        <div class="col-8 col-md-10">
            <input type="text" name="inventory_number" value="{{ old('inventory_number', $equipment->inventory_number) }}" class="form-control">
            @error('inventory_number') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class='col-4 col-md-2'>Дата ввода в экспуатацию:</div>
        <div class="col-8 col-md-10">
            <input type="date" name="enter_date" value="{{ old('enter_date', $equipment->enter_date ? $equipment->enter_date->format('Y-m-d') : '') }}" class="form-control w-auto">
            @error('enter_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class='col-4 col-md-2'>Описание:</div>
        <div class="col-8 col-md-10">
            <textarea name="description" class="form-control" rows="5">{{ old('description', $equipment->description) }}</textarea>
            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class='col-4 col-md-2'>Внешний вид:</div>
        <div class="col-8 col-md-10">
            @if($equipment->photo)
                {!! \App\Helpers\ImageHelper::linkImgTag($equipment->photo, [], ['style'=>"max-width: 200px;"]) !!}
                <label class="d-block my-3">
                    <input type="checkbox" name="photo_del" value="1"> Удалить фото
                </label>
            @endif
            <input type="file" class="form-control" name="photo">
            @error('photo') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class='col-4 col-md-2'>Схема:</div>
        <div class="col-8 col-md-10">
            @if($equipment->sketch)
                {!! \App\Helpers\ImageHelper::linkImgTag($equipment->sketch, [], ['style'=>"max-width: 200px;"]) !!}
                <label class="d-block my-3">
                    <input type="checkbox" name="sketch_del" value="1"> Удалить фото
                </label>
            @endif
            <input type="file" class="form-control" name="sketch">
            @error('sketch') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class='col-4 col-md-2'>Документация:</div>
        <div class="col-8 col-md-10">
            <div class="mb-3">
                @foreach($equipment->documents as $document)
                    <div class="d-block mb-2">
                        <a href="{{ \App\Helpers\FileHelper::url($document) }}" class="me-4" target=_blank>{{ $document->original_name }}</a>
                        <label>
                            <input type="checkbox" name="documents_deleted[]" value="{{ $document->id }}"> Удалить файл
                        </label>
                    </div>
                @endforeach
            </div>

            <input type="file" class="form-control" name="documents[]" multiple>
            @error('documents') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class='mt-4 row'>
        <div class="col">
            <input type="submit" class='btn btn-primary' value="Сохранить">
        </div>
        <div class="col-6 text-end">
            <a href="{{ route('equipments.show', $equipment) }}" class="btn btn-outline-secondary">Отмена</a>
        </div>
    </div>
</form>
@endsection