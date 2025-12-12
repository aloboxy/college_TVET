@extends('layouts.master')
@section('page_title', 'Coure List')
@section('content')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-books mr-2"></i> Course List</h5>
            {!! Qs::getPanelOptions() !!}
        </div>
        <div>
        <form method="post" action="{{ route('courselist.selector') }}">
            @csrf

        <div class="card-body">
            <livewire:mark-selector/>
        </div>
        </form>
    </div>
    </div>
    @endsection
