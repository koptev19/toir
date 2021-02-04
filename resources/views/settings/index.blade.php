@extends('layouts.toir')

@section('content')
@include('components/admin_tabs', ['active' => 'settings'])

<div class="tab-content border border-top-0">
    <div class="tab-pane fade show active p-4"role="tabpanel" aria-labelledby="department-tab">
        <h5>Настройки</h5>

        @if(session('settings_message'))
            <div class="alert alert-info">
                {{ session('settings_message') }}
            </div>
        @endif

        <form action="{{ route('settings.update')}}" method="post">
        @csrf
        @method('put')
            <div class="row mb-3 mt-3">
                <div class='col-4 col-md-3'><label class="col-form-label">Дата планирования "График ТОиР" :</label></div>
                <div class='col-8 col-md-9'>
                    <input name='plan_month_day' type="number" min="1" max="31" step="1" class="form-control" value="{{ old('plan_month_day', $settings['plan_month_day']->value) }}" required>
                    @error('plan_month_day') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class='mt-4'>
                <input type="submit" value="Сохранить" class='btn btn-primary'>
            </div>
        </form>
    </div>
</div>

@endsection