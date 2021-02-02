@extends('layouts.main')

@section('content')
<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item" role="presentation">
    	<a class="nav-link active" href="#">Оборудование</a>
	</li>
    <li class="nav-item" role="presentation">
	    <a class="nav-link" href="{{ route('departments.index')}}">Службы</a>
	</li>
	<li class="nav-item" role="presentation">
	    <a class="nav-link" href="users.php">Пользователи</a>
	</li>
	<li class="nav-item" role="presentation">
	    <a class="nav-link" href="settings.php">Настройки</a>
	</li>
</ul>
<div class="tab-content border border-top-0">
	<div class="tab-pane fade show active"role="tabpanel" aria-labelledby="equipment-tab">
		<div class="row">
			<div class='col-2 p-4 border-end '>
				@foreach($equipmentsTree as $equipmentNode)
					<div class="">
						<a href="{{ route('equipments.show', $equipmentNode) }}">{{ $equipmentNode->name }}</a>
					</div>
				@endforeach
				<div class="mt-5">
					<a href="{{ route('equipments.create') }}" class="">Добавить цех</a>
				</div>
    		</div>
            <div class="col-10 p-3">
                @yield('content_equipment')
            </div>
        </div>
	</div>
</div>

@endsection