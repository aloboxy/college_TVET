@extends('layouts.master')
@section('page_title', 'Manage Payments')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="icon-cash2 mr-2"></i> Select year</h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="post" action="{{ route('payments.invoice_year') }}">
            @csrf
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="year" class="col-form-label font-weight-bold">Select Year <span
                                class="text-danger">*</span></label>
                        <select data-placeholder="Select Year" required id="year" name="year"
                            class="form-control select">
                            @foreach($years as $yr)
                            <option {{ ($selected && $year==$yr->year) ? 'selected' : '' }} value="{{ $yr->year }}">{{
                                $yr->year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="term_id" class="col-form-label font-weight-semibold">Semester</label>
                        <select required id="term_id" name="term_id" data-placeholder="Select Semester"
                            class="form-control select">
                            <option value=""></option>
                            <option {{ (old('term_id')=='First Semester' ) ? 'selected' : '' }} value="1">First Semester
                            </option>
                            <option {{ (old('term_id')=='Second Semester' ) ? 'selected' : '' }} value="2">Second
                                Semester</option>
                        </select>
                    </div>
                </div>
                <input hidden value="{{$student_id}}" name="student_id" >

                <div class="col-md-1 mt-4">
                    <div class="text-right mt-1">
                        <button type="submit" class="btn btn-primary">Submit <i
                                class="icon-paperplane ml-2"></i></button>
                    </div>
                </div>


            </div>
    </div>

    </form>
</div>


{{--Payments List Ends--}}

@endsection
