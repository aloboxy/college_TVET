@extends('layouts.master')
@section('page_title', 'Change Student College')
@section('content')

    <div class="card">
            <div class="card-header bg-white header-elements-inline">
                <h6 id="ajax-title" class="card-title">Change of College For {{ $student->user->name }}</h6>

                {!! Qs::getPanelOptions() !!}
            </div>
            {{-- ajax-update --}}
            <form method="post" class="ajax-update" data-reload="#ajax-title" action="{{ route('students.change_college.store', Qs::hash($student->id)) }}" data-focus>
                @csrf @method('PUT')
                  <div class="row">
                           <div class="col-md-3">
                            <div class="form-group">
                                <h3 for="current_department">Current Department:</h3>
                                <h1>{{$student->real_department->name ?? $student->department->name}}</h1>
                            </div>
                        </div>


                        <div class="col-md-3" >
                            <div class="form-group">
                                <label for="college_id">New College: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..."  name="college_id" id="college_id" class="select-search form-control">
                                    <option value=""></option>
                                    @foreach($college as $college)
                                        <option  value="{{ $college->id }}">{{ $college->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                           <div class="col-md-3">
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

                 <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-3"></i></button>
                </div>
            </form>
</div>

 <script>
    // Load section by class
   $(document).on('change', '#my_class_id', function () {
        const class_id  = $(this).val();
        if(class_id) {
            $.ajax({
                type: "GET",
                url: "{{ url('class/section') }}?class=" + class_id,
                success: function(data){
                    $('#section_id').empty().append('<option>Please select</option>');
                    $.each(data, function(key){
                        $('#section_id').append('<option value="' + data[key].id +'">'+data[key].name + '</option>');
                    });
                }
            });
        } else {
            $('#section_id').empty();
        }
    });

  // Load departments by college and store class_base
$(document).on('change', '#college_id', function () {
    var college_id = $(this).val();
    if (college_id) {
        $.ajax({
            type: "GET",
            url: "{{ url('colleges/department') }}?college=" + college_id,
            success: function (data) {
                $('#department_id').empty().append('<option>Please select</option>');
                $('#department_id').data('departments', data); // ✅ Store all department data

                $.each(data, function (key) {
                    $('#department_id').append(
                        '<option value="' + data[key].id + '">' + data[key].name + '</option>'
                    );
                });

                // Reset display
                $('.academic-data, .college-data').slideUp();
            }
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
            } else {
                $('.academic-data, .college-data').slideUp();
            }
        }
    } else {
        $('.academic-data, .college-data').slideUp();
    }
});



    // Load majors by department
    $(document).on('change', '#department_id', function () {
        const dept_id = $(this).val();
        if (dept_id) {
            $.ajax({
                type: "GET",
                url: "{{ url('colleges/majors') }}?department=" + dept_id,
                success: function(data){
                    // console.log(data);
                    $('#major').empty().append('<option>Please select</option>');
                    $.each(data, function(key){
                        $('#major').append('<option value="' + data[key].major + '">' + data[key].major + '</option>');
                    });
                }
            });
        } else {
            $('#major').empty();
        }
    });

    // Load minors by major
    $(document).on('change', '#major', function () {
        const major = $(this).val();
        if (major) {
            $.ajax({
                type: "GET",
                url: "{{ url('colleges/minors') }}?major=" + major,
                success: function(data){
                    $('#minor').empty().append('<option>Please select</option>');
                    $.each(data, function(key){
                        $('#minor').append('<option value="' + data[key].minor + '">' + data[key].minor + '</option>');
                    });
                }
            });
        } else {
            $('#minor').empty();
        }
    });

</script>
@endsection
