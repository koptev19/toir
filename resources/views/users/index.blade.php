@extends('layouts.toir')

@section('content')
@include('components/admin_tabs', ['active' => 'users'])

<div class="tab-content border border-top-0">
    <div class="tab-pane fade show active p-3" role="tabpanel" aria-labelledby="users-tab" id="users">
        <users-managing-table
            :users="{{ json_encode($users) }}"
            :workshops="{{ json_encode($workshops) }}"
            :departments="{{ json_encode($departments) }}"
        ></users-managing-table>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/users.js') }}"></script>
@endsection