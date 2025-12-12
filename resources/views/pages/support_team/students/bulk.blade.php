@extends('layouts.master')
@section('page_title', 'Student Information')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Students List</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">


            <div class="tab-content">
                <div class="table-responsive">
                    <button id="processSelectedBtn" class="btn btn-success mb-3">Process Selected Students</button>
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

            $.ajax({
                url: "{{ route('bulk.process.students') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    ids: selectedIds
                },
                success: function (response) {
                    alert(response.message);
                    table.ajax.reload();
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert("Something went wrong. Please try again.");
                }
            });
        });
</script>


@endsection
