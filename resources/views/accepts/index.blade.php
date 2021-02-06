@extends('layouts.toir')

@section('content')
@include('components.admin_tabs', ['active' => 'accepts'])

<div class="tab-content border border-top-0">
    <div class="tab-pane fade show active p-3" role="tabpanel" aria-labelledby="accept-tab">
        <a href="{{ route('accepts.create') }}" class='btn btn-outline-primary'>Добавить</a>

        <table class="table table-bordered mt-4 table-hover">
            <thead>
                <tr class='text-center'>
                    <th><div>№</div></th>
                    <th><div>Оборудование</div></th>
                    <th><div>Ссылка</div></th>
                    <th><div>Приемка</div></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @each('accepts._item', $accepts, 'accept')
            </tbody>
        </table>
    </div>
</div>

@endsection