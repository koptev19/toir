@extends('layouts.toir')

@section('content')

@include('components/admin_tabs', ['active' => 'departments'])

<div class="tab-content border border-top-0" id="equipments">
    <div class="tab-pane fade show active"role="tabpanel" aria-labelledby="equipment-tab">
        <div class="row">
            <div class='p-4 border-end' style="width:300px;">
                <equipment-tree
                    :selected="{{ json_encode( $parentsId ?? [] ) }}"
                    v-on:select="equipmentShow($event)"
                ></equipment-tree>
                <div class="mt-5">
                    <a href="{{ route('equipments.create') }}" class="">Добавить цех</a>
                </div>
            </div>
            <div class="col p-3">
                @yield('content_equipment')
            </div>
        </div>
    </div>
</div>

@endsection
