@extends('layouts.toir')

@section('content')
<h1 class='text-center mb-5'>Приемка оборудования</h1>

<form  method="post" action="{{ route('accept-histories.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="accept_id" value="{{ $accept->id }}">

    @if($errors->any())
        <div class="alert alert-danger">
            {{ implode('<br>', $errors->all()) }}
        </div>
    @endif

    <accept-history-create
        equipment="{{ $accept->equipment->full_path }}"
        :checklist="{{ json_encode(explode(PHP_EOL, $accept->checklist ?: [])) }}"
    ></accept-history-create>

</form>
@endsection
