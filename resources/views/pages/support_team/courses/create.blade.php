@extends('layouts.master')
@section('page_title', 'Manage Course Planning')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Plan Course</h6>
            {!! Qs::getPanelOptions() !!}
        </div>


        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#new-course" class="nav-link active" data-toggle="tab">Add Course For Planning</a></li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane show  active fade" id="new-course">
                    <div class="row">
                        <div class="col-md-6">
                            {{-- ajax-store --}}
                            <form class="ajax-store" method="post" action="{{ route('courses.store') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="year" class="col-lg-3 col-form-label font-weight-semibold">Year</label>
                                    <div class="col-lg-9">
                                    <select required id="year" name="year" data-placeholder="Select Year" class="form-control select">
                                       <option value=""></option>
                                        @foreach ($years as $y )
                                            <option value="{{ $y->year }}">{{ $y->year }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>


                                    <div class="form-group row">
                                        <label for="term_id" class="col-lg-3 col-form-label font-weight-semibold">Semester</label>
                                        <div class="col-lg-9">
                                        <select required id="term_id" name="term_id" data-placeholder="Select Semester" class="form-control select">
                                           <option value=""></option>
                                           <option {{ (old('term_id') == 'First Semester') ? 'selected' : '' }} value="1">First Semester</option>
                                           <option {{ (old('term_id') == 'Second Semester') ? 'selected' : '' }} value="2">Second Semester</option>
                                        </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="section_id" class="col-lg-3 col-form-label font-weight-semibold">Level:</label>
                                        <div class="col-lg-9">
                                        <select required id="level" name="level" data-placeholder="Select Class First" class="form-control select">
                                            <option value=""></option>
                                              <option value="Freshmen">Freshmen</option>
                                              <option value="Sophomore">Sophomore</option>
                                              <option value="Junior">Junior</option>
                                              <option value="Senior">Senior</option>
                                        </select>
                                    </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="subject_id" class="col-lg-3 col-form-label font-weight-semibold">Course <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <select data-placeholder="Select Subject" class="form-control select-search" name="subject_id" id="subject_id">
                                        <option value=""></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Department:</label>
                                        <div class="col-lg-9">
                                        <select required onchange="getClassSubjects(this.value)" id="department_id" name="department_id[]" class="form-control select-search" multiple>
                                            <option value="">Select Department</option>
                                            @foreach($my_classes as $c)
                                                <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                    {{-- <div class="form-group row">
                                        <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Add another Class</label>
                                        <div class="col-lg-9">
                                        <select  id="another_section" name="another_section" class="form-control select-search">
                                            <option value="">Select Another Class if any</option>
                                            @foreach($my_classes as $c)
                                                <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}








                                <div class="form-group row">
                                    <label for="teacher_id" class="col-lg-3 col-form-label font-weight-semibold">Instructor <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select data-placeholder="Please Select Instructor" class="form-control select-search" name="teacher_id" id="teacher_id">
                                            <option value=""></option>
                                            @foreach($teachers as $teacher)
                                            <option {{ old('user_id') == Qs::hash($teacher->id) ? 'selected' : '' }} value="{{ Qs::hash($teacher->id) }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="session" class="col-lg-3 col-form-label font-weight-semibold">Session<span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <input type="text" name="session" value="{{ old('session') }}" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="room" class="col-lg-3 col-form-label font-weight-semibold">Room<span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <input type="text" name="room" value="{{ old('room') }}" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="day" class="col-lg-3 col-form-label font-weight-semibold">Day <span class="text-danger">*</span></label>

                                    <div class="col-lg-3">
                                        <select   required id="day" name="day[]"  type="text" class='form-control' multiple data-placeholder="Select Day...">
                                            <option value=""></option>
                                            @foreach(Qs::getDaysOfTheWeek() as $dw)
                                                <option {{ old('day') == $dw ? 'selected' : '' }} value="{{ $dw }}">{{ $dw }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="capacity" class="col-lg-3 col-form-label font-weight-semibold">Capacity<span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <input type="text" name="capacity" value="{{ old('capacity') }}" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="time_from" class="col-lg-3 col-form-label font-weight-semibold">Time From <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <input type="time" name="time_from" value="{{ old('time_from') }}" type="time" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="time_to" class="col-lg-3 col-form-label font-weight-semibold">Time To <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <input type="time" name="time_to" value="{{ old('time_to') }}" type="time" class="form-control">
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
        </div>
    </div>


<script src="{!! asset('js/select2/js/select2.min.js') !!}"></script>

<script type="text/javascript">
$(document).ready(function() {

  $("#day").select2(
    );

    $("#my_class_id").select2(
    );


});


    $(document).ready(function () {
        $('#level').on('change', function () {
            const level = $(this).val();

            if (level) {
                $.ajax({
                    url: '{{ route("get.subjects.by.level") }}', // route you will define
                    type: 'GET',
                    data: { level: level },
                    success: function (data) {
                        $('#subject_id').empty().append('<option value=""></option>');
                        $.each(data, function (key, subject) {
                            $('#subject_id').append(`<option value="${subject.id}">${subject.name}</option>`);
                        });
                    },
                    error: function () {
                        alert('Failed to load subjects');
                    }
                });
            } else {
                $('#subject_id').empty().append('<option value=""></option>');
            }
        });
    });

</script>

    {{--subject List Ends--}}

@endsection
