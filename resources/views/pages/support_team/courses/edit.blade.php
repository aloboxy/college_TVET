@extends('layouts.master')
@section('page_title', 'Edit Course')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Course - {{$s->subject->name }}</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update-h" method="post" action="{{ route('courses.update', $s->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label for="term_id" class="col-lg-3 col-form-label font-weight-semibold">Semester</label>
                            <div class="col-lg-9">
                            <select required id="term_id" name="term_id" data-placeholder="Select Semester" class="form-control select">
                                <option value=""></option>
                                <option {{ ($s->term_id == '1' ? 'selected' : '') }} value="1">First Semester</option>
                               <option {{ ($s->term_id == '2' ? 'selected' : '' )}} value="2">Second Semester</option>
                            </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="section_id" class="col-lg-3 col-form-label font-weight-semibold">Level:</label>
                            <div class="col-lg-9">
                            <select required id="level" name="level" data-placeholder="Select Class First" class="form-control select">
                                <option value=""></option>
                                  <option {{ $s->level == 'Freshmen'  ? 'selected' : '' }} value="Freshmen">Freshmen</option>
                                  <option {{ $s->level == 'Sophomore' ? 'selected' : '' }} value="Sophomore">Sophomore</option>
                                  <option {{ $s->level == 'Junior' ? 'selected' : '' }} value="Junior">Junior</option>
                                  <option {{ $s->level == 'Senior' ? 'selected' : '' }} value="Senior">Senior</option>
                            </select>
                        </div>
                        </div>


                        <div class="form-group row">
                            <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Department:</label>
                            <div class="col-lg-9">
                                <select required id="department_id" name="department_id[]" class="form-control select-search" multiple>
                                    @foreach($departments as $c)
                                        <option
                                            value="{{ $c->id }}"
                                            {{ isset($s) && in_array($c->id, explode(',', $s->department_id)) ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>





                        {{-- <div class="form-group row">
                            <label for="section_id" class="col-lg-3 col-form-label font-weight-semibold">Cohort:</label>
                            <div class="col-lg-9">
                            <select required id="section_id" name="section_id" data-placeholder="Select Class First" class="form-control select">
                                <option value="{{ $s->section->id }}">{{ $s->section->name }}</option>
                            </select>
                        </div>
                        </div> --}}

                        <div class="form-group row">
                            <label for="teacher_id" class="col-lg-3 col-form-label font-weight-semibold">Instructor</label>
                            <div class="col-lg-9">
                                <select data-placeholder="Select Teacher" class="form-control select-search" name="teacher_id" id="teacher_id">
                                    <option value=""></option>
                                    @foreach($teachers as $t)
                                        <option {{ $s->teacher_id == $t->id ? 'selected' : '' }} value="{{ Qs::hash($t->id) }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>




                        <div class="form-group row">
                            <label for="session" class="col-lg-3 col-form-label font-weight-semibold">Session<span class="text-danger">*</span></label>
                            <div class="col-lg-3">
                                <input type="text" name="session" value="{{ $s->session }}" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="room" class="col-lg-3 col-form-label font-weight-semibold">Room<span class="text-danger">*</span></label>
                            <div class="col-lg-3">
                                <input type="text" name="room" value="{{ $s->room }}" type="text" class="form-control">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="capacity" class="col-lg-3 col-form-label font-weight-semibold">Capacity<span class="text-danger">*</span></label>
                            <div class="col-lg-3">
                                <input type="text" name="capacity" value="{{ $s->capacity }}" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="day" class="col-lg-3 col-form-label font-weight-semibold">Day <span class="text-danger">*</span></label>

                            <div class="col-lg-3">
                                <select id="day" name="day[]" required type="text" name="day[]"  class='form-control' multiple
                                        data-placeholder="Select Day...">
                                    <option value=""></option>
                                    @foreach(Qs::getDaysOfTheWeek() as $dw)
                                 
                                    <option {{ (in_array($dw, explode(',', $s->day))) ? 'selected' : '' }} value="{{ $dw}}">{{ $dw }}</option>
                                   

                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="time_from" class="col-lg-3 col-form-label font-weight-semibold">Time From <span class="text-danger">*</span></label>
                            <div class="col-lg-3">
                                <input type="time" name="time_from" value="{{ $s->time_from }}" type="time" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="time_to" class="col-lg-3 col-form-label font-weight-semibold">Time To <span class="text-danger">*</span></label>
                            <div class="col-lg-3">
                                <input type="time" name="time_to" value="{{ $s->time_to }}" type="time" class="form-control">
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <label for="for_all" class="col-lg-3 col-form-label font-weight-semibold">For all Departments: </label>
                            <div class="col-lg-5">
                            <select id="for_all" name="for_all" data-placeholder="Leave it if it is for only selected section"class="form-control select">
                                <option {{ ($s->for_all  == 0 ? 'selected' : '') }} value=0>For only This Department</option>
                                <option {{ ($s->for_all  == 1 ? 'selected' : '') }} value=1>Yes</option>
                            </select>
                            </div>
                        </div> --}}

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="{!! asset('js/select2/js/select2.min.js') !!}"></script>

<script type="text/javascript">
$(document).ready(function() {

  $("#day").select2(
    );

});

$('#day').find(':selected');
</script>

    {{--subject Edit Ends--}}

@endsection
