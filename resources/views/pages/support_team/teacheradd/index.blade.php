@extends('layouts.master')
@section('page_title', 'Manage Student Course Enrollment')
@section('content')


<div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Add Student to Course</h6>
            {!! Qs::getPanelOptions() !!}
        </div>
        <div class="card-body">
        <div class="row">
        <div class="col-mb-2">
            <div class="form-group">
                <label for="session" class="col-form-label font-weight-bold">Year:</label>
                <select required id="year" name="year" class="form-control" wire:model.live="selectedYear">
                    <option value="">Academic Year</option>
                    @foreach($years as $y)
                        <option value="{{ $y->year }}">{{ $y->year }}</option>
                    @endforeach
                </select>
                @if (session()->has('message'))
                    <div class="alert alert-warning">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="exam_id" class="col-form-label font-weight-bold">Semester:</label>
                <select required id="exam_id" name="exam_id" class="form-control">
                    <option value="">Select Semester</option>
                  
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="my_class_id" class="col-form-label font-weight-bold">Department:</label>
                <select required class="select-search form-control" id="department_id" name="department_id" wire:model.live="selectedDepartment">
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="level" class="col-form-label font-weight-bold">Level:</label>
                <select required class="form-control select" id="level" name="level" wire:model.live="selectedLevel">
                    <option value="">Please Select Level</option>
                    @foreach($levels as $level)
                        <option value="{{ $level }}">{{ $level }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="subject_id" class="col-form-label font-weight-bold">Subject:</label>
                <select required class="select-search form-control" id="subject_id" name="subject_id" >
                    <option value="">Select Course</option>
                    
                </select>
            </div>
        </div>
        <div  id="loading" style="display:none;">
    <div class="spinner-overlay">
        <div class="text-center loading-container">
            <div class="loading"></div>
            <div id="loading-text">Processing</div>
        </div>
    </div>
</div>
    <style>
       
                #link {
                    color: #E45635;
                    display: block;
                    font: 12px "Helvetica Neue", Helvetica, Arial, sans-serif;
                    text-align: center;
                    text-decoration: none;
                }

                #link:hover {
                    color: #b82222
                }

                #link,
                #link:hover {
                    -webkit-transition: color 0.5s ease-out;
                    -moz-transition: color 0.5s ease-out;
                    -ms-transition: color 0.5s ease-out;
                    -o-transition: color 0.5s ease-out;
                    transition: color 0.5s ease-out;
                }

                /** BEGIN CSS **/
                body {
                    background: #f3efef;
                }

                @keyframes rotate-loading {
                    0% {
                        transform: rotate(0deg);
                        -ms-transform: rotate(0deg);
                        -webkit-transform: rotate(0deg);
                        -o-transform: rotate(0deg);
                        -moz-transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                        -ms-transform: rotate(360deg);
                        -webkit-transform: rotate(360deg);
                        -o-transform: rotate(360deg);
                        -moz-transform: rotate(360deg);
                    }
                }

                @-moz-keyframes rotate-loading {
                    0% {
                        transform: rotate(0deg);
                        -ms-transform: rotate(0deg);
                        -webkit-transform: rotate(0deg);
                        -o-transform: rotate(0deg);
                        -moz-transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                        -ms-transform: rotate(360deg);
                        -webkit-transform: rotate(360deg);
                        -o-transform: rotate(360deg);
                        -moz-transform: rotate(360deg);
                    }
                }

                @-webkit-keyframes rotate-loading {
                    0% {
                        transform: rotate(0deg);
                        -ms-transform: rotate(0deg);
                        -webkit-transform: rotate(0deg);
                        -o-transform: rotate(0deg);
                        -moz-transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                        -ms-transform: rotate(360deg);
                        -webkit-transform: rotate(360deg);
                        -o-transform: rotate(360deg);
                        -moz-transform: rotate(360deg);
                    }
                }

                @-o-keyframes rotate-loading {
                    0% {
                        transform: rotate(0deg);
                        -ms-transform: rotate(0deg);
                        -webkit-transform: rotate(0deg);
                        -o-transform: rotate(0deg);
                        -moz-transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                        -ms-transform: rotate(360deg);
                        -webkit-transform: rotate(360deg);
                        -o-transform: rotate(360deg);
                        -moz-transform: rotate(360deg);
                    }
                }

                @keyframes rotate-loading {
                    0% {
                        transform: rotate(0deg);
                        -ms-transform: rotate(0deg);
                        -webkit-transform: rotate(0deg);
                        -o-transform: rotate(0deg);
                        -moz-transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                        -ms-transform: rotate(360deg);
                        -webkit-transform: rotate(360deg);
                        -o-transform: rotate(360deg);
                        -moz-transform: rotate(360deg);
                    }
                }

                @-moz-keyframes rotate-loading {
                    0% {
                        transform: rotate(0deg);
                        -ms-transform: rotate(0deg);
                        -webkit-transform: rotate(0deg);
                        -o-transform: rotate(0deg);
                        -moz-transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                        -ms-transform: rotate(360deg);
                        -webkit-transform: rotate(360deg);
                        -o-transform: rotate(360deg);
                        -moz-transform: rotate(360deg);
                    }
                }

                @-webkit-keyframes rotate-loading {
                    0% {
                        transform: rotate(0deg);
                        -ms-transform: rotate(0deg);
                        -webkit-transform: rotate(0deg);
                        -o-transform: rotate(0deg);
                        -moz-transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                        -ms-transform: rotate(360deg);
                        -webkit-transform: rotate(360deg);
                        -o-transform: rotate(360deg);
                        -moz-transform: rotate(360deg);
                    }
                }

                @-o-keyframes rotate-loading {
                    0% {
                        transform: rotate(0deg);
                        -ms-transform: rotate(0deg);
                        -webkit-transform: rotate(0deg);
                        -o-transform: rotate(0deg);
                        -moz-transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                        -ms-transform: rotate(360deg);
                        -webkit-transform: rotate(360deg);
                        -o-transform: rotate(360deg);
                        -moz-transform: rotate(360deg);
                    }
                }

                @keyframes loading-text-opacity {
                    0% {
                        opacity: 0
                    }

                    20% {
                        opacity: 0
                    }

                    50% {
                        opacity: 1
                    }

                    100% {
                        opacity: 0
                    }
                }

                @-moz-keyframes loading-text-opacity {
                    0% {
                        opacity: 0
                    }

                    20% {
                        opacity: 0
                    }

                    50% {
                        opacity: 1
                    }

                    100% {
                        opacity: 0
                    }
                }

                @-webkit-keyframes loading-text-opacity {
                    0% {
                        opacity: 0
                    }

                    20% {
                        opacity: 0
                    }

                    50% {
                        opacity: 1
                    }

                    100% {
                        opacity: 0
                    }
                }

                @-o-keyframes loading-text-opacity {
                    0% {
                        opacity: 0
                    }

                    20% {
                        opacity: 0
                    }

                    50% {
                        opacity: 1
                    }

                    100% {
                        opacity: 0
                    }
                }

                .loading-container,
                .loading {
                    height: 100px;
                    position: relative;
                    width: 100px;
                    border-radius: 100%;
                }


                .loading-container {
                    margin: 40px auto
                }

                .loading {
                    border: 2px solid transparent;
                    border-color: transparent #2b9a50 transparent #356ba5;
                    -moz-animation: rotate-loading 1.5s linear 0s infinite normal;
                    -moz-transform-origin: 50% 50%;
                    -o-animation: rotate-loading 1.5s linear 0s infinite normal;
                    -o-transform-origin: 50% 50%;
                    -webkit-animation: rotate-loading 1.5s linear 0s infinite normal;
                    -webkit-transform-origin: 50% 50%;
                    animation: rotate-loading 1.5s linear 0s infinite normal;
                    transform-origin: 50% 50%;
                }

                .loading-container:hover .loading {
                    border-color: transparent #E45635 transparent #E45635;
                }

                .loading-container:hover .loading,
                .loading-container .loading {
                    -webkit-transition: all 0.5s ease-in-out;
                    -moz-transition: all 0.5s ease-in-out;
                    -ms-transition: all 0.5s ease-in-out;
                    -o-transition: all 0.5s ease-in-out;
                    transition: all 0.5s ease-in-out;
                }

                #loading-text {
                    -moz-animation: loading-text-opacity 2s linear 0s infinite normal;
                    -o-animation: loading-text-opacity 2s linear 0s infinite normal;
                    -webkit-animation: loading-text-opacity 2s linear 0s infinite normal;
                    animation: loading-text-opacity 2s linear 0s infinite normal;
                    color: #0f0e0e;
                    font-family: "Helvetica Neue, " Helvetica", ""arial";
                    font-size: 10px;
                    font-weight: bold;
                    margin-top: 45px;
                    opacity: 0;
                    position: absolute;
                    text-align: center;
                    text-transform: uppercase;
                    top: 0;
                    width: 100px;
                }
                .spinner-overlay {
                    position: fixed; /* Or absolute if it's within a section */
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
            

    </style>
       
    </div>
        </div>
        <div>

        <div class="float-md-right">
        <div class="table-responsive">
                    <table class="table datatable-button-html5-columns" width="100%" style="padding:40px;" id="studentnotenrolled">
                        @csrf
                        <h1>Students Not Enrolled Listing</h1>
                        <thead>
                        <tr>
                            <th width="50%">Name</th>
                            <th width="30%">ADM_No</th>
                            <th width="20%">Action</th>
                        </tr>
                        </thead>

                        <tbody>

                        </tbody>

                    </table>
                </div>
                
            
        </div>

        <div class="float-md-left">
        <div class="table-responsive">
            <table class="table datatable-button-html5-columns" width="100%" style="padding:40px;" id="studentenrolled">
                @csrf
                <h1>Students Enrolled Listing</h1>
                <thead>
                    <tr>
                        <th width="50%">Name</th>
                        <th width="30%">ADM_No</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>

        </div>
    </div>
</div>
<script  type="text/javascript">
     $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});

//Loading exams
$('#year').change(function(){
year = $(this).val();
// alert(year);
if(year)
{
$.ajax({
    type: "GET",
    url: "{{url('year/semester') }}?year="+year,
    beforeSend: function() {
                $('#loading').show(); // Show spinner
            },
    success:function(data){
        // console.log(data);
        $('#exam_id').empty();
        $('#my_class_id').empty();
        $('#section_id').empty();
        $('#exam_id').append('<option>Please select</option>');
        $.each(data, function(key){
            $('#exam_id').append('<option value="' + data[key].term +'">'+data[key].name + '</option>');
        });
        $('#loading').hide(); // Hide spinner
    }

});
}
else{
$('#exam_id').empty();
$('#loading').hide(); // Hide spinner
}
})


//loading courses for selection
$('#level').change(function(){
var department_id  = $('#department_id').val();
var level = $(this).val();
var exam = $('#exam_id').val();
var year = $('#year').val();
// alert(exam);
if(department_id && level && exam && year)
{
$.ajax({
    type: "GET",
    url: "{{url('department/subject')}}",
    data: {
        'department_id':department_id,
        'level':level,
        'exam':exam,
        'year':year
    },
    beforeSend: function() {
                $('#loading').show(); // Show spinner
            },
    success:function(data){
        // console.log(data);
        $('#subject_id').empty();
        $('#subject_id').append('<option>Please select</option>');
        $.each(data, function(key){
            $('#subject_id').append(
                '<option value="' + data[key].id + '">' +
                data[key].subject + ' session-' + data[key].session +
                ' -- ' + (data[key].teacher ? data[key].teacher : '') +
                '</option>'
            );
        });
        $('#loading').hide(); // Hide spinner
    }

});
}
else{
$('#subject_id').empty();
$('#loading').hide(); // Hide spinner
}
}) 
</script>





<script type="text/javascript">
    // Initialize DataTables
    var enrolledTable = $('#studentenrolled').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 10,
        columns: [
            { data: 'name' },
            { data: 'adm_no' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });
    
    var notEnrolledTable = $('#studentnotenrolled').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 10,
        columns: [
            { data: 'name' },
            { data: 'adm_no' },
            { data: 'action', orderable: false, searchable: false }
        ],
    });

    // Loading exams
    $('#subject_id').change(function(){
        loadEnrolledStudents();
        loadNotEnrolledStudents();
    });

    // Function to load enrolled students
    function loadEnrolledStudents() {
        var level = $('#level').val();
        var subject = $('#subject_id').val();
        var department = $('#department_id').val();
        var exam = $('#exam_id').val();
        var year = $('#year').val();
        
        if (!subject) return;
        
        $.ajax({
            type: "GET",
            url: "{{url('teacheradd/enrolled')}}", 
            data: {
                'level': level,
                'department': department,
                'exam': exam,
                'year': year,
                'subject': subject
            },
            beforeSend: function() {
                $('#loading').show();
            },
            success: function(data) {
                // Clear and redraw the table with new data
                enrolledTable.clear();
                
                $.each(data, function(index, student) {
                    enrolledTable.row.add({
                        'name': student.name,
                        'adm_no': student.adm_no,
                        'action': `<button class="btn btn-danger drop" data-course-id="${student.id}">Remove</button>`
                    });
                });
                
                enrolledTable.draw();
                $('#loading').hide();
            }
        });
    }

    // Function to load not enrolled students
    function loadNotEnrolledStudents() {
        var level = $('#level').val();
        var subject = $('#subject_id').val();
        var department = $('#department_id').val();
        var exam = $('#exam_id').val();
        var year = $('#year').val();
        
        if (!subject) return;
        
        $.ajax({
            type: "GET",
            url: "{{url('teacheradd/unenrolled')}}", 
            data: {
                'level': level,
                'department': department,
                'exam': exam,
                'year': year,
                'subject': subject
            },
            beforeSend: function() {
                $('#loading').show();
            },
            success: function(data) {
                // Clear and redraw the table with new data
                notEnrolledTable.clear();
                
                $.each(data, function(index, student) {
                    notEnrolledTable.row.add({
                        'name': student.name,
                        'adm_no': student.adm_no,
                        'action': `<button class="btn btn-primary enroll" data-course-id="${student.id}">Add</button>`
                    });
                });
                
                notEnrolledTable.draw();
                $('#loading').hide();
            }
        });
    }

    // Handle enroll button click
    $('#studentnotenrolled').on('click', '.enroll', function() {
        var studentId = $(this).data('course-id');
        var subject = $('#subject_id').val();
        var exam = $('#exam_id').val();
        var year = $('#year').val();

        
        $.ajax({
            url: "{{ route('teacheradd.enroll') }}",
            method: 'POST',
            data: {
                studentId: studentId, // Assuming this is actually the student ID
                subject: subject,
                term_id: exam,
                year: year,
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function() {
                $('#loading').show();
            },
            success: function(response) {
                // console.log(response);
                if (response.success) {
                    loadEnrolledStudents();
                    loadNotEnrolledStudents();
                }
                $('#loading').hide();
            }
        });
    });

    // Handle drop button click
    $('#studentenrolled').on('click', '.drop', function() {
        var enrollmentId = $('#subject_id').val(); // Assuming this is the enrollment ID
        var studentId = $(this).data('course-id');
        
        $.ajax({
            url: "{{ route('teacheradd.drop') }}",
            method: 'DELETE',
            data: {
                studentId: studentId,
                enrollmentId: enrollmentId,
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function() {
                $('#loading').show();
            },
            success: function(response) {
                if (response.success) {
                    loadEnrolledStudents();
                    loadNotEnrolledStudents();
                }
                $('#loading').hide();
            }
        });
    });
</script>

@endsection