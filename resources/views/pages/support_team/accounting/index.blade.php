@extends('layouts.master')
@section('page_title', 'Student Records Balance Inquiry')
@section('content')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-books mr-2"></i> Student Records Balance Inquiry</h5>
            {!! Qs::getPanelOptions() !!}
        </div>
    
            <div class="card-body">
                <form method="post" action="{{ route('accounting.student_fees') }}">
                    @csrf
            @include('pages.support_team.marks.selector_accounting')
        </form>
    
                  
    </div>
@endsection
