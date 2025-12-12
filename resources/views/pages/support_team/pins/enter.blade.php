@extends('layouts.master')
@section('page_title', 'Enter PIN')
@section('content')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-alarm mr-2"></i> Enter PIN</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h1>Grades are not yet being approved!!</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
