@extends('layouts.master')
@section('page_title', 'Clean Duplicates')
@section('content')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-books mr-2"></i> Clean Duplicates</h5>
            {!! Qs::getPanelOptions() !!}
        </div>
            <div class="card-body">
                @if(Qs::getSetting('Close_Grade_Entry') == 0 && Qs::getSetting('planning_open') == 0)
                    <form class="ajax-update" method="post" action="{{ route('duplicate.clean') }}">
                        @csrf
                    @include('pages.support_team.duplicate.selector')
                </form>
                </div>
                @elseif(Qs::getSetting('Close_Grade_Entry') == 1 && Qs::getSetting('planning_open') == 0)
                      <form class="ajax-update" method="post" action="{{ route('duplicate.clean') }}">
                        @csrf
                    @include('pages.support_team.duplicate.selector')
                </form>
                </div>
                @elseif(Qs::getSetting('planning_open') == 1)
                    <h1 class="text-center"> Planning on Going</h1>
                @elseif(Qs::getSetting('Close_Grade_Entry') == 0)
                    <h1 class="text-center"> Grade Entry Closed</h1>
                @endif
    </div>
@endsection
