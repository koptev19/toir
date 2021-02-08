@extends('layouts.toir')

@section('content')
@include('components/admin_tabs', ['active' => 'users'])

<div class="tab-content border border-top-0">
    <div class="tab-pane fade show active p-3" role="tabpanel" aria-labelledby="users-tab" id="users">
        @if(session('users_message'))
            <div class="alert alert-info">{{ session('users_message') }}</div>
        @endif

        @foreach($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>            
        @endforeach

        <form action="{{ route('users.store') }}" method="post">
            @csrf

            <users-managing-table
                :users="{{ json_encode($users) }}"
                :workshops="{{ json_encode($workshops) }}"
                :departments="{{ json_encode($departments) }}"
                exclude="{{ \Auth::user()->id }}"
            ></users-managing-table>

            <div class="my-4 text-center">
                <input type="submit" value="Сохранить" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/users.js') }}"></script>
@endsection