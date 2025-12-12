@extends('layouts.master')
@section('page_title', 'Student Information')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Students List</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
    @php
         $transfer = session('transfer');
    @endphp

@if($transfer)
    <div class="alert alert-info">
        Selected College: <strong>@php $college=DB::table('colleges')->where('id',$transfer['college'])->first()->name @endphp {{ $college }}</strong><br>
        Department: <strong>@php $department=DB::table('class_types')->where('id',$transfer['department'])->first()->name @endphp {{ $department }}</strong><br>
        Major: <strong>@php $major=$transfer['major'] @endphp {{ $major }}</strong><br>
        Minor: <strong>@php $minor=$transfer['minor'] @endphp {{ $minor }}</strong><br>
        Cohort: <strong>@php $cohort=DB::table('sections')->where('id',$transfer['cohort'])->first()->name  ?? '' @endphp {{ $cohort}}</strong><br>
        Class: <strong>@php $class=DB::table('my_classes')->where('id',$transfer['class'])->first()->name ?? '' @endphp {{ $class}}</strong><br>
        Level: <strong>@php $level=$transfer['level'] @endphp {{ $level }}</strong>
    </div>
@endif

    {{-- Blade flash message (e.g., from redirect) --}}
    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- AJAX alert container --}}
    <div id="statusMessage"></div>

<span class="text-danger">Please note the college selected well be assigned to selected students. To change go back to select college</span>

            <div class="tab-content">
                <div class="table-responsive">
                    <button id="processSelectedBtn" class="btn btn-success mb-3">Process Selected Students</button>
                    <a href="{{ route('bulk.transfer.select.list') }}" class="btn btn-primary mb-3">Select College</a>
                    <table class="table table-bordered student-table" width="100%" style="padding:0px;" id="student">
                        @csrf
                        <thead>
                        <tr>
                            <th width="5%">
                                <input type="checkbox" id="selectAllCheckbox">
                            </th>
                            <th width="20%">Name</th>
                            <th width="5%">ADM_No</th>
                            <th width="15%">Program</th>
                            <th width="10%" >Cell #</th>
                            <th width="10%">Email</th>
                            <th width="20%">Status</th>
                        </tr>
                        </thead>

                         <tbody>

                        </tbody>

                    </table>
                </div>


            </div>
        </div>
    </div>

    {{--Student List Ends--}}
  <script type="text/javascript">
        // Initialize DataTable
        var table = $(".student-table").DataTable({
            processing: true,
            serverSide: true,
            dom: 'lBfrtip',
            ajax: "{{ route('bulk.transfer.students') }}",
            columns: [
                { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'adm_no', name: 'adm_no' },
                { data: 'Program', name: 'Program' },
                { data: 'cell', name: 'cell' },
                { data: 'email', name: 'email' },
                { data: 'Status', name: 'Status' },
            ],
        });

        // Select All Checkbox Handler
        $('#selectAllCheckbox').on('click', function () {
            $('.student_checkbox').prop('checked', this.checked);
        });

        // Process Selected Button Click
        $('#processSelectedBtn').on('click', function () {
    var selectedIds = [];

    $('.student_checkbox:checked').each(function () {
        selectedIds.push($(this).val());
    });

    if (selectedIds.length === 0) {
        alert('Please select at least one student.');
        return;
    }

    // Disable button & show loading text
    var btn = $(this);
    btn.prop('disabled', true).text('Processing...');

    $.ajax({
        url: "{{ route('bulk.process.students') }}",
        method: "POST",
        data: {
            _token: '{{ csrf_token() }}',
            ids: selectedIds
        },
        success: function (response) {
        pop({
            title: 'Success!',
            msg: response.message,
            type: 'success',
            timer: 3000
        });
        table.ajax.reload();
    },
    error: function (xhr) {
        pop({
            title: 'Error',
            msg: 'Something went wrong. Please try again.',
            type: 'error'
        });
    },
    complete: function () {
        btn.prop('disabled', false).text('Process Selected Students');
    }
    });
});

        setTimeout(function() {
    $('#statusMessage').fadeOut('slow');
}, 5000); // hide after 5 seconds

</script>


@endsection
