@extends('layouts.master')
@section('page_title', 'Enrolled List')
@section('content')
    <div class="card">

        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-books mr-2"></i> Student Enrolled List</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <livewire:student-enrolled/>
        </div>
    </div>

 </div>
    @endsection
