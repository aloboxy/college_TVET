@extends('layouts.master')
@section('content')


@if($status == 1)
    <div>
        <div class="card-header header-elements-inline">
            <h1 class="card-title" style="text-align: center;">Course Planning</h1>
        </div>

<div class="container">

@if($planningOpen)
    @if($paid == 1)
    <div class="col-mb-4">
        <label>Department:</label>
        <select id="department" class="form-select select-search" style="width: 200px;">
            <option value="">Select Department</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ $defaultDeptId == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>

        <label class="mt-2">Level:</label>
        <select id="level" class="form-select select-search" style="width: 200px;">
            <option value="" disabled>Select Level</option>
            <option value="Freshmen" {{ $defaultLevel == 'Freshmen' ? 'selected' : '' }}>Freshmen</option>
            <option value="Sophomore" {{ $defaultLevel == 'Sophomore' ? 'selected' : '' }}>Sophomore</option>
            <option value="Junior" {{ $defaultLevel == 'Junior' ? 'selected' : '' }}>Junior</option>
            <option value="Senior" {{ $defaultLevel == 'Senior' ? 'selected' : '' }}>Senior</option>
        </select>
    </div>
<!-- table datatable-button-html5-columns -->
    <div id="courses-area">
    @include('components.enrolled-courses-rows', compact('enrolled_courselist'))     
    </div>

        

    @else
        <h1 class="text-center text-danger">Please Visit the Business office</h1>
    @endif
@else
    <div class="text-center">
        <h1>Planning Closed</h1>
    </div>
@endif

<div class="table-responsive">
        <h2 style="text-align: center;">Planned Courses</h2>
        <table class="table datatable-button-html5-columns table-striped table-sm" width="100%" style="padding:0px;" >
            @csrf
            <thead>
                <tr>
                <th>#</th>
                             <th>Course Name</th>
                             <th>Teacher</th>
                             <th>Semester</th>
                             <th>Session</th>
                             <th width="10%">Room</th>
                             <th>Day</th>
                             <th>Time From</th>
                             <th>Time To</th>
                             @if(Qs::getSetting('planning_open') == 1)
                             <th>Action</th>
                             @endif
                </tr>
            </thead>
            <tbody id="studentplanned">
                @include('components.planned-courses-rows', compact('planned'))
            </tbody>
    </table>
    </div>

<!-- Flash message reused from your original -->
<div id="custom-flash-message" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 24px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); z-index: 9999; min-width: 280px; text-align: center;">
    <span id="flash-message-text" style="font-size: 1.1rem;"></span>
    <br><br>
    <button onclick="hideFlashMessage()" id="flash-ok-button" style="padding: 10px 20px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">OK</button>
</div>

</div>
</div>

@else
    <div>
        <div class="alert alert-warning">
            <strong style="text-align: center">Warning!</strong> <h1 style="text-align: center">You have be dropped.</h1>
        </div>
    </div>
 @endif


<script>

function showFlash(msg, type = 'info') {
    const box = document.getElementById('custom-flash-message');
    const text = document.getElementById('flash-message-text');
    const button = document.getElementById('flash-ok-button');

    text.textContent = msg;
    box.style.backgroundColor = type === 'success' ? '#d1fae5' : type === 'error' ? '#fee2e2' : '#e0f2fe';
    text.style.color = type === 'success' ? '#065f46' : type === 'error' ? '#991b1b' : '#1e40af';
    button.style.backgroundColor = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6';
    box.style.display = 'block';

    setTimeout(hideFlashMessage, 3000);
}

function hideFlashMessage() {
    document.getElementById('custom-flash-message').style.display = 'none';
}


function fetchCourses() {
    const department = $('#department').val();
    const level = $('#level').val();
    // console.log(department,level);
    if (department && level) {
        $.ajax({
            type: "GET",
            url: "/planning/course/list",
            data: {
                department,
                level,
            },
            success: function(data) {
                    $('#courses-area').html(data); // Replace entire table
                    initCourseTable();             // Reinitialize DataTable
                },
            error: function(xhr) {
                let errorMessage = 'An error occurred while fetching courses.';
                if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showFlash(errorMessage, 'error');
            }
        });
    } else {
        $('#course-body').empty();
    }
}

$('#department, #level').change(function() {
    fetchCourses();
});

let courseTable;

function initCourseTable() {
    // Destroy existing DataTable if already initialized
    if ($.fn.DataTable.isDataTable('#courses-table')) {
        $('#courses-table').DataTable().destroy();
    }

    // Reinitialize DataTable
    courseTable = $('#courses-table').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true
    });
}


function enroll(courseId) {
    $.ajax({
        type: "POST",
        url: "/planning/enroll",
        data: {
            course_id: courseId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            showFlash(data.message, 'success');
            fetchCourses();
            reloadStudentPlanned();
        },
        error: function(xhr) {
            let errorMessage = 'An error occurred while enrolling.';
            if (xhr.responseJSON?.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showFlash(errorMessage, 'error');
        }
    });
}

function reloadStudentPlanned() {
    $.ajax({
        type: "GET",
        url: "/planning/planned-courses",
        success: function(data) {
            $('#studentplanned').html(data);
        },
        error: function(xhr) {
            let errorMessage = 'An error occurred while loading planned courses.';
            if (xhr.responseJSON?.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showFlash(errorMessage, 'error');
        }
    });
}

$(document).on('click', '.add-btn', function() {
    const courseId = $(this).data('course-id');
    enroll(courseId);
});

$(document).on('click', '.drop-btn', function() {
    const courseId = $(this).data('course-id');
    if (confirm('Are you sure you want to drop this course?')) {
        $.ajax({
            type: "POST",
            url: "/planning/drop-course",
            data: {
                course_id: courseId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showFlash(response.message, response.type);
                reloadStudentPlanned();
                fetchCourses();
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while dropping the course.';
                if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showFlash(errorMessage, 'error');
            }
        });
    }
});



$(document).ready(function() {
    reloadStudentPlanned();

    // Only run DataTable if table already has rows (on page load)
    if ($('#courses-table tbody tr').length > 0) {
        initCourseTable();
    }

    // Auto-fetch if department/level have defaults
    if ($('#department').val() && $('#level').val()) {
        fetchCourses();
    }
});

</script>

@endsection

