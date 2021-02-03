@extends('layouts.toir')

@section('content')
@include('components/admin_tabs', ['active' => 'departments'])

<div class="tab-content border border-top-0">
    <div class="tab-pane fade show active"role="tabpanel" aria-labelledby="department-tab">
        <div class="row">
            <div class='col-2 p-4 border-end '>
                @foreach(\App\Models\Department::all() as $department)
                   <a href="{{ route('departments.edit', $department) }}" class="d-block pb-2">{{ $department->name }}</a>
                @endforeach
                <div class="mt-5">
                    <a href="{{ route('departments.create') }}" class="">Добавить службу</a>
                </div>
            </div>
            <div class="col-10 p-3">
                @yield('content_department')
            </div>
        </div>
    </div>
</div>

@endsection