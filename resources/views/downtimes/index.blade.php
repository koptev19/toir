@extends('layouts.toir', ['title' => 'Журнал простоев'])

@section('content')

<h3 class="text-center">Журнал простоев</h3>

@include('downtimes._filter')

<downtimes-table
    dates="date_from={{ old('date_from') }}&date_to={{ old('date_to') }}"
></downtimes-table>

@endsection