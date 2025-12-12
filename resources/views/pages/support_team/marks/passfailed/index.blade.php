@extends('layouts.master')
@section('page_title', 'Promotion Data')
@section('content')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-books mr-2"></i> Student Promotion Data</h5>
            {!! Qs::getPanelOptions() !!}
        </div>
<livewire:failed-pass>
@endsection
