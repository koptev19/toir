@extends('layouts.toir')

@section('content')

@include('components/admin_tabs', ['active' => 'departments'])

<div class="tab-content border border-top-0" id="equipments">
    <div class="tab-pane fade show active"role="tabpanel" aria-labelledby="equipment-tab">
        <div class="row">
            <div class='col-2 p-4 border-end'>
                @foreach(\App\Models\Workshop::withCount('children')->get() as $workshop)
                    <equipment-item
                        id="{{ $workshop->id }}"
                        name="{{ $workshop->name }}"
                        childrencount="{{ $workshop->children_count }}"
                        route="{{ route('equipments.children') }}"
                        htmlclass="{{ $workshop->html_class }}"
                        :selected="{{ json_encode( $parentsId ?? [] ) }}"
                    ></equipment-item>
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
    <script src="{{ asset('js/equipments.js') }}"></script>
@endsection