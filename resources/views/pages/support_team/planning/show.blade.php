@extends('layouts.master')
@section('page_title', 'Student Inactive/unrolled for planning')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Not Planned/Inactive Students {{ $years }} {{ $my_class->name }} For Semester {{$sem}}</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <a class="col-md-4" href="{{ route('courselist.planned_course_list') }}"><button class="btn btn-primary">Select New Class</button></a>
<div id="statusMessage" style="display:none; color:seagreen !important; font-size:16px; text-align:center"></div>

        <div class="card-body">

          <div class="tab-content">
                    <div class="tab-pane fade show active" id="all-grades">
                        <table class="table datatable-button-html5-columns">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Admission#</th>
                                <th>Department</th>
                                <th>Semester-Level</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($not_enrolled as $gr)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $gr->user->name }}</td>
                                    <td>{{ $gr->adm_no }}</td>
                                    <td>{{ $gr->my_class->name }}</td>
                                    <td>{{ $gr->section->name }}</td>
                                     <td>
                                    <label class="switch">
                                        <input type="checkbox" class="status-toggle" data-id="{{ $gr->user->id }}" {{ $gr->user->status ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <style>
.switch {
    position: relative;
    display: inline-block;
    width: 34px;
    height: 20px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: red;
    transition: .4s;
    border-radius: 20px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 14px;
    width: 14px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: green;
}

input:checked + .slider:before {
    transform: translateX(14px);
}
</style>

<script>
$(document).ready(function() {
    $('.status-toggle').change(function() {
        let studentId = $(this).data('id');
        let isActive = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: `/update-student-status`,
            type: 'POST',
            data: {
                  '_token': '{{ csrf_token() }}',
                'studentId':studentId,
            },
            success: function(response) {
                // alert(response.msg);
               $('#statusMessage').text(response.msg).show();
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
});

</script>


    {{--Class List Ends--}}

@endsection
