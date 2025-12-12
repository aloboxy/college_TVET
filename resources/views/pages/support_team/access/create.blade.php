@extends('layouts.master')
@section('page_title', 'Planning Marks')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-bold">Fill The Form To Manage Planning</h6>
            {!! Qs::getPanelOptions() !!}
        </div>



    {{--Marks Manage End--}}

@endsection
