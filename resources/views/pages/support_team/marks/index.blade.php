@extends('layouts.master')
@section('page_title', 'Manage Exam Marks')
@section('content')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-books mr-2"></i> Manage Exam Marks</h5>
            {!! Qs::getPanelOptions() !!}
        </div>
       @if(Qs::userIsTeacher())
           @if(Qs::getSetting('Close_Grade_Entry') == 0 && Qs::getSetting('planning_open') == 0)
            <div class="card-body">
                <form method="post" action="{{ route('marks.selector') }}">
                    @csrf
            @include('pages.support_team.marks.selector_course')
        </form>
            </div>
    
            @elseif(Qs::getSetting('planning_open') == 1)
            <h1 class="text-center"> Planning on Going</h1>
            @elseif(Qs::getSetting('Close_Grade_Entry') == 1)
            <h1 class="text-center"> Grade Entry Closed</h1>
            @endif
        @else
            <div class="card-body">
                @if(Qs::getSetting('Close_Grade_Entry') == 0 && Qs::getSetting('planning_open') == 0)
                    <form method="post" action="{{ route('marks.selector') }}">
                        @csrf
                    @include('pages.support_team.marks.selector_course')
                </form>
                </div>
                @elseif(Qs::getSetting('Close_Grade_Entry') == 1 && Qs::getSetting('planning_open') == 0)
                      <form method="post" action="{{ route('marks.selector') }}">
                        @csrf
                    @include('pages.support_team.marks.selector_course')
                </form>
                </div>
                @elseif(Qs::getSetting('planning_open') == 1)
                    <h1 class="text-center"> Planning on Going</h1>
                @elseif(Qs::getSetting('Close_Grade_Entry') == 0)
                    <h1 class="text-center"> Grade Entry Closed</h1>
                @endif
        @endif
    </div>
@endsection
