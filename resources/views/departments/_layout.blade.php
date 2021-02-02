@extends('layouts.main')

@section('content')
<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item" role="presentation">
    	<a class="nav-link" href="{{ route('equipments.index') }}">Оборудование</a>
	</li>
    <li class="nav-item" role="presentation">
	    <a class="nav-link active" href="{{ route('departments.index') }}">Службы</a>
	</li>
	<li class="nav-item" role="presentation">
	    <a class="nav-link" href="users.php">Пользователи</a>
	</li>
	<li class="nav-item" role="presentation">
	    <a class="nav-link" href="settings.php">Настройки</a>
	</li>
</ul>
<div class="tab-content border border-top-0">
	<div class="tab-pane fade show active"role="tabpanel" aria-labelledby="department-tab">
		<div class="row">
			<div class='col-2 p-4 border-end '>
				@foreach(\App\Models\Department::all() as $department)
					<div class="">
						<a href="{{ route('departments.edit', $department) }}">{{ $department->name }}</a>
					</div>
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