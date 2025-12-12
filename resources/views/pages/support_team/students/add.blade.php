@extends('layouts.master')
@section('page_title', 'Admit Student')
@section('content')
        <div class="card">
            <div class="card-header bg-white header-elements-inline">
                <h6 class="card-title">Please fill The form Below To Admit A New Student</h6>

                {!! Qs::getPanelOptions() !!}
            </div>
            {{-- ajax-reg  1024888888  10264888888--}}
            <form id="ajax-reg" method="post" enctype="multipart/form-data" class="wizard-form steps-validation" action="{{ route('students.store') }}" data-fouc>
               @csrf
                <h6>Personal data</h6>
                <fieldset>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name: <span class="text-danger">*</span></label>
                                <input value="{{ old('name') }}" required type="text" name="name" placeholder="Full Name" class="form-control">
                                </div>
                            </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address: <span class="text-danger">*</span></label>
                                <input value="{{ old('address') }}" class="form-control" placeholder="Address" name="address" type="text" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email address: </label>
                                <input type="email" value="{{ old('email') }}" name="email" class="form-control" placeholder="Email Address">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender">Gender: <span class="text-danger">*</span></label>
                                <select class="select" id="gender" name="gender" required data-fouc data-placeholder="Choose..">
                                    <option value=""></option>
                                    <option {{ (old('gender') == 'Male') ? 'selected' : '' }} value="Male">Male</option>
                                    <option {{ (old('gender') == 'Female') ? 'selected' : '' }} value="Female">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Phone #/Whatsapp:</label>
                                <input value="{{ old('phone') }}" type="text" name="phone" class="form-control" placeholder="" >
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Date of Birth:</label>
                                    <input name="dob" value="{{ old('dob') }}" type="text" class="form-control date-pick" placeholder="Select Date...">

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="nal_id">Nationality: <span class="text-danger">*</span></label>
                                    <select data-placeholder="Choose..." required name="nal_id" id="nal_id" class="select-search form-control">
                                        <option value=""></option>
                                        @foreach($nationals as $nal)
                                            <option {{ (old('nal_id') == $nal->id ? 'selected' : '') }} value="{{ $nal->id }}">{{ $nal->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="state_id">County of Origin:</label>
                                <select onchange="getLGA(this.value)"  data-placeholder="Choose.." class="select-search form-control" name="state_id" id="state_id">
                                    <option value=""></option>
                                    @foreach($states as $st)
                                        <option {{ (old('state_id') == $st->id ? 'selected' : '') }} value="{{ $st->id }}">{{ $st->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="my_parent">Emergency Contact Name: </label>
                                <input value="{{ old('my_parent') }}" type="text" name="my_parent" id= "my_parent" placeholder="only emergency contact" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Emergency Contact Number:</label>
                                <input value="{{ old('phone2') }}" type="text" name="phone2" class="form-control" placeholder="Emergency Contact" >
                            </div>
                        </div>

                    </div>



                    <div class="row">


                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Upload Photo:</label>
                                <input value="{{ old('photo') }}" accept="image/*" type="file" name="photo" class="form-input-styled" data-fouc>
                                <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb</span>
                            </div>
                        </div>
                    </div>

                </fieldset>

                <h6>Student Program</h6>
                <fieldset>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="program"><strong>Program:</strong> <span class="text-danger">*</span></label>
                                <select name="program" id="program" data-placeholder="please choose program" id="program" class="select-search form-control" required>
                                    <option value=""></option>
                                    <option value="BSc">BSc</option>
                                    <option value="MSc">MSc</option>
                                    <option value="Diploma">Diploma</option>
                                    <option value="Certificate">Certificate</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>



                <h6>Academic Data</h6>
                <fieldset>
                <div class="row">

                     <div class="col-md-3">
                            <div class="form-group">
                                <label for="college_id">College: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..."  name="college_id" id="college_id" class="select-search form-control">
                                    <option value=""></option>
                                    @foreach($college as $college)
                                        <option  value="{{ $college->id }}">{{ $college->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    <div class="col-md-3" >
                            <div class="form-group">
                                <label for="department_id">Department: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..." required name="department_id" id="department_id" class="select-search form-control">
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 academic-data" style="display: none;">
                            <div class="form-group">
                                <label for="my_class_id">Class: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..." required name="my_class_id" id="my_class_id" class="select-search form-control">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                           <div class="col-md-3 academic-data" style="display: none;">
                            <div class="form-group">
                                <label for="section_id">Cohort: <span class="text-danger">*</span></label>
                                <select data-placeholder="Select Class First"  name="section_id" id="section_id" class="select-search form-control">
                                </select>
                            </div>
                        </div>



                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year_admitted">Year Admitted: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..."  name="year_admitted" id="year_admitted" class="select-search form-control">
                                    <option value=""></option>
                                    @for($y=date('Y', strtotime('- 1 years')); $y<=date('Y'); $y++)
                                        <option {{ (old('year_admitted') == $y) ? 'selected' : '' }} value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>





                        <div class="col-md-3" >
                            <div class="form-group">
                                <label for="major">Major: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..."  name="major" id="major" class="select-search form-control">
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 college-data" style="display: none;">
                            <div class="form-group">
                                <label for="minor">Minor: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..."  name="minor" id="minor" class="select-search form-control">
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3 college-data" style="display: none;">
                            <div class="form-group">
                                <label for="level">Level: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..."  name="level" id="level" class="select-search form-control">
                                    <option value="Freshmen">Freshmen</option>
                                    <option value="Sophomore">Sophomore</option>
                                    <option value="Junior">Junior</option>
                                    <option value="Senior">Senior</option>
                                </select>
                            </div>
                        </div>
                </div>

                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name of Previous School: </label>
                                <input value="{{ old('previous_school') }}" type="text" id="previous_school" name="previous_school" placeholder="School Name" class="form-control">
                                </div>
                            </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Admission Number:</label>
                                <input required type="text" name="adm_no" placeholder="Admission Number" class="form-control" value="{{ old('adm_no') }}">
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="session">Year:</label>
                                <select required id="year" name="year" class="form-control">
                                    <option value="">Academic Year</option>
                                @foreach($years as $y)
                                    <option value="{{$y->year }}">{{ $y->year}}</option>
                                @endforeach

                                </select>
                            </div>
                        </div>


                    </div>




    <script>
$(document).ready(function () {
    // Load sections by class
    $('#my_class_id').on('change', function () {
        const class_id = $(this).val();
        if (class_id) {
            $.get("{{ url('class/section') }}?class=" + class_id, function (data) {
                $('#section_id').empty().append('<option>Please select</option>');
                $.each(data, function (key) {
                    $('#section_id').append('<option value="' + data[key].id + '">' + data[key].name + '</option>');
                });
            });
        } else {
            $('#section_id').empty();
        }
    });

    // Load departments by college
    $('#college_id').on('change', function () {
        const college_id = $(this).val();
        if (college_id) {
            $.get("{{ url('colleges/department') }}?college=" + college_id, function (data) {
                $('#department_id').empty().append('<option>Please select</option>');
                $('#department_id').data('departments', data);
               // console.log(data); // Debugging line to check data structure
                $.each(data, function (key) {
                    $('#department_id').append('<option value="' + data[key].id + '">' + data[key].name + '</option>');
                });
                $('.academic-data, .college-data').slideUp();
            });
        } else {
            $('#department_id').empty();
            $('.academic-data, .college-data').slideUp();
        }
    });

    // Slide based on department's class_base

$(document).on('change', '#department_id', function () {
    var dept_id = $(this).val();
    var departments = $('#department_id').data('departments');

    if (departments && dept_id) {
        var selectedDept = departments.find(function (dept) {
            return dept.id == dept_id;
        });

        // console.log(selectedDept); // ✅ Safe to debug

        if (selectedDept && selectedDept.class_base !== undefined) {
            if (selectedDept.class_base === 1) {
                $('.academic-data').slideDown();
                $('.college-data').slideUp();

                 $('#my_class_id, #section_id').attr('required', true);
                    $('#major, #minor, #level').val('').removeAttr('required');
                   $.ajax({
                    type: "GET",
                    url: "{{ url('department/class') }}?department_id=" + selectedDept.id,
                    success: function(data) {
                        $('#my_class_id').empty();
                        $.each(data, function(key, value) {
                            $('#my_class_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });

            } else if (selectedDept.class_base === 0) {
                $('.college-data').slideDown();
                $('.academic-data').slideUp();

                $('#my_class_id, #section_id').val('').removeAttr('required');
                $('#major, #minor, #level').attr('required', true);
            } else {
                $('.academic-data, .college-data').slideUp();
            }
        }
    } else {
        $('.academic-data, .college-data').slideUp();
    }
});




    // Load majors by department
    $('#department_id').on('change', function () {
        const dept_id = $(this).val();
        if (dept_id) {
            $.get("{{ url('colleges/majors') }}?department=" + dept_id, function (data) {
                $('#major').empty().append('<option>Please select</option>');
                $.each(data, function (key) {
                    $('#major').append('<option value="' + data[key].major + '">' + data[key].major + '</option>');
                });
            });
        } else {
            $('#major').empty();
        }
    });

    // Load minors by major
    $('#major').on('change', function () {
        const major = $(this).val();
        if (major) {
            $.get("{{ url('colleges/minors') }}?major=" + major, function (data) {
                $('#minor').empty().append('<option>Please select</option>');
                $.each(data, function (key) {
                    $('#minor').append('<option value="' + data[key].minor + '">' + data[key].minor + '</option>');
                });
            });
        } else {
            $('#minor').empty();
        }
    });
});
</script>

</fieldset>

</form>
</div>
    @endsection
