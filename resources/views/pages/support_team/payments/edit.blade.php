@extends('layouts.master')
@section('page_title', 'Edit Payment')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Payment</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" method="post" action="{{ route('payments.update', $payment->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Title <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="title" value="{{ $payment->title }}" required type="text" class="form-control" placeholder="Eg. School Fees">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="term_id" class="col-lg-3 col-form-label font-weight-semibold">Semester</label>
                            <div class="col-lg-9">
                            <select required id="term_id" name="term_id" data-placeholder="Select Semester" class="form-control select">
                                <option value=""></option>
                                <option {{ ($payment->term_id == '1' ? 'selected' : '') }} value="1">First Semester</option>
                               <option {{ ($payment->term_id == '2' ? 'selected' : '' )}} value="2">Second Semester</option>
                            </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Class </label>
                            <div class="col-lg-9">
                                <select required onchange="getClassSections(this.value);" id="my_class_id" name="my_class_id[]" class="form-control select-search" multiple>
                                    @foreach($my_classes as $c)
                                        @if(in_array($c->id, explode(',', $payment->my_class_id)))
                                            <option {{ (in_array($c->id, explode(',', $payment->my_class_id))) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="section_id" class="col-lg-3 col-form-label font-weight-semibold">Cohort:</label>
                            <div class="col-lg-9">
                            <select required id="section_id" name="section_id" data-placeholder="Select Class First" class="form-control select">
                                <option value="{{ $payment->section}}">{{ $payment->section }}</option>
                            </select>
                        </div>
                        </div>



                        <div class="form-group row">
                            <label for="method" class="col-lg-3 col-form-label font-weight-semibold">Payment Method</label>
                            <div class="col-lg-9">
                                <input title="method" value="{{ ucwords($payment->method) }}" disabled class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="amount" class="col-lg-3 col-form-label font-weight-semibold">Amount </label>
                            <div class="col-lg-9">
                                <input class="form-control" value="{{ $payment->amount }}" id="amount" type="text">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-lg-3 col-form-label font-weight-semibold">PAYMENT CURRENCY</label>
                            <div class="col-lg-9">
                                <input class="form-control" value="{{ $payment->description }}" name="description" id="description" type="text">
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--Payment Edit Ends--}}

@endsection
