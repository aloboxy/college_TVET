@extends('layouts.master')
@section('page_title', 'Student Information')
@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Students List</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">


            <div class="tab-content">
                <div class="table-responsive">
                    <table class="table table-bordered student-table" width="100%" style="padding:0px;" id="student">
                        @csrf
                        <thead>
                        <tr>
                            <th width="20%">Name</th>
                            <th width="5%">ADM_No</th>
                            <th width="15%">Program</th>
                            <th width="10%" >Cell #</th>
                            <th width="20%">Email</th>
                            <th width="5%">Status</th>
                            <th width="25%">Action</th>
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
        var table = $(".student-table").DataTable({
            processing: true,
            serverSide: true,
            dom:'lBfrtip',
            ajax: "{{ route('students.list') }}",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'adm_no', name: 'adm_no' },
                { data: 'Program', name: 'Program' },
                { data: 'cell', name: 'cell' },
                { data: 'email', name: 'email' },
                { data: 'Status', name: 'Status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    </script>

@endsection
