@extends('departments._layout')

@section('content_department')
<form action="{{ route('departments.store') }}" method="post">
    @csrf

    <h3 class="mb-5">Новая служба</h3>

    <div class="row mb-3">
        <div class='col-4 col-md-2'>Наименование:</div>
        <div class="col-8 col-md-10">
            <input type="text" name="name" value="" class='form-control' required>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class='col-4 col-md-2'>Краткое наименование:</div>
        <div class="col-8 col-md-10">
            <input type="text" name="short_name" value="" class='form-control' required>
            @error('short_name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class='col-4 col-md-2'>Руководитель:</div>
        <div class="col-8 col-md-10">
            
            @error('manager_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class='mt-4 row'>
        <div class="col">
            <input type="submit" class='btn btn-primary' value="Сохранить">
        </div>
        <div class="col-6 text-end">
            <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">Отмена</a>
        </div>
    </div>
</form>
@endsection