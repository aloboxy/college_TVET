@extends('layouts.master')
@section('page_title', 'Manage Planning Access')
@section('content')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-books mr-2"></i> Manage Planning Access</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            @include('pages.support_team.access.selector')
        </div>
    </div>
    @endsection
