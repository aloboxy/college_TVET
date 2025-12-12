@extends('layouts.master')
@section('page_title', 'Enrolled Students')
@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="icon-books mr-2"></i> Student Course enrollment</h5>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="card-body">
        <livewire:teacheradd />
    </div>
</div>
@endsection
