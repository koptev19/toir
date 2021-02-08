@extends('layouts.toir', ['app' => true])

@section('content')
@include('components/admin_tabs', ['active' => 'accepts'])

<div class="tab-content border border-top-0">
    <div class="tab-pane fade show active p-3" role="tabpanel" aria-labelledby="accept-tab">
        <h5 class='mb-4'>Добавление приемки оборудования</h5>

        <form action="{{ route('accepts.store') }}" method="post">
            @csrf
            
            <div class="mb-3 row mb-4">
                <div class='col-2'>Оборудование</div>
                <div class="col-10">
                    @include('components.equipment', [
                        'equipment' => old('equipment_id'),
                        'required' => true
                    ])
                    @error('equipment_id') <div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3 row mb-4">
                <div class='col-2'>Чек-лист</div>
                <div class="col-10">
                    <textarea name="checklist" class='form-control' rows="7"></textarea>
                    Для создания чеклиста введите текст.<br>
                    Каждая новая строка - это новый пункт чеклиста
                    @error('checklist') <div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class='row'>
                <div class='col-6'>
                    <input value="Сохранить" type="submit" class='btn btn-primary'>
                </div>
                <div class='col-6 text-end'>
                    <a href="{{ route('accepts.index') }}"  type="" class="btn btn-outline-secondary" >Отмена</a>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection