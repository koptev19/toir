@extends('layouts.toir')

@section('content')

@include('components/admin_tabs', ['active' => 'departments'])

<div class="tab-content border border-top-0">
    <div class="tab-pane fade show active"role="tabpanel" aria-labelledby="equipment-tab">
        <div class="row">
            <div class='col-2 p-4 border-end' id="equipments">
                @foreach(\App\Models\Workshop::all() as $workshop)
                    <equipments-item
                    ></equipments-item>
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

@section('scripts')
    <script src="/.js"></script>
@endsection