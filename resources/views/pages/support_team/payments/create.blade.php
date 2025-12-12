@extends('layouts.master')
@section('page_title', 'Create Payment')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Create Payment</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <form class="ajax-store" method="post" action="{{ route('payments.store') }}">
                    @csrf
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Title <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input name="title" value="{{ old('title') }}" required type="text" class="form-control" placeholder="Eg. School Fees">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="year" class="col-lg-3 col-form-label font-weight-semibold">Year <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select required id="year" name="year" data-placeholder="Select Year" class="form-control select">
                                <option value=""></option>
                                @foreach ($years as $y)
                                <option value="{{ $y->year }}" {{ old('year') == $y->year ? 'selected' : '' }}>{{ $y->year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="term_id" class="col-lg-3 col-form-label font-weight-semibold">Semester <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select required id="term_id" name="term_id" data-placeholder="Select Semester" class="form-control select">
                                <option value=""></option>
                                <option value="1" {{ old('term_id') == 1 ? 'selected' : '' }}>First Semester</option>
                                <option value="2" {{ old('term_id') == 2 ? 'selected' : '' }}>Second Semester</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="department_id" class="col-lg-3 col-form-label font-weight-semibold">Department <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select required id="department_id" name="department_id" data-placeholder="Select Department" class="form-control select">
                                <option value=""></option>
                                @foreach ($departments as $d)
                                <option value="{{ $d->id }}" data-code="{{ $d->code }}" {{ old('department_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="class-cohort-group" style="display: none;">
                        <div class="form-group row">
                            <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Class</label>
                            <div class="col-lg-9">
                                <select id="my_class_id" name="my_class_id[]" class="form-control select-search" multiple>
                                    @if(old('my_class_id'))
                                        @foreach(old('my_class_id') as $classId)
                                            <option value="{{ $classId }}" selected>{{ \App\Models\MyClass::find($classId)->name ?? '' }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="section_id" class="col-lg-3 col-form-label font-weight-semibold">Cohort</label>
                            <div class="col-lg-9">
                                <select id="section_id" name="section_id" data-placeholder="Select Class First" class="form-control select">
                                    @if(old('section_id'))
                                        <option value="{{ old('section_id') }}" selected>{{ \App\Models\Section::find(old('section_id'))->name ?? '' }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="method" class="col-lg-3 col-form-label font-weight-semibold">Payment Method <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select class="form-control select" name="method" id="method" required>
                                <option value="Cash" {{ old('method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Online" {{ old('method') == 'Online' ? 'selected' : '' }}>Online</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="amount" class="col-lg-3 col-form-label font-weight-semibold">Amount <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input class="form-control" value="{{ old('amount') }}" required name="amount" id="amount" type="number" min="0" step="0.01">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-lg-3 col-form-label font-weight-semibold">Payment Currency</label>
                        <div class="col-lg-9">
                            <input class="form-control" value="{{ old('description', 'NGN') }}" name="description" id="description" type="text">
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

<script type="text/javascript">
$(document).ready(function() {
    // Initialize select2
    $('.select').select2();
    $('.select-search').select2();

    // Department change handler
    $('#department_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        const departmentCode = selectedOption.data('code');
        const departmentId = $(this).val();

        if (departmentCode === 'LS' || departmentCode === 'NA') {
            $('#class-cohort-group').show();
            loadClasses(departmentId);
        } else {
            $('#class-cohort-group').hide();
            $('#my_class_id').val(null).trigger('change');
            $('#section_id').val(null).trigger('change');
        }
    }).trigger('change');

    // Class change handler
    $('#my_class_id').change(function() {
        const classId = $(this).val();
        if (classId && classId.length > 0) {
            loadSections(classId[0]); // Take first class if multiple selected
        } else {
            $('#section_id').empty().trigger('change');
        }
    });

    // Load classes for department
    function loadClasses(departmentId) {
        if (departmentId) {
            $.ajax({
                type: "GET",
                url: "{{ url('department/class') }}?department_id=" + departmentId,
                success: function(data) {
                    $('#my_class_id').empty();
                    $.each(data, function(key, value) {
                        $('#my_class_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    
                    // Preselect old values if any
                    @if(old('my_class_id'))
                        $('#my_class_id').val(@json(old('my_class_id'))).trigger('change');
                    @endif
                }
            });
        } else {
            $('#my_class_id').empty();
        }
    }

    // Load sections for class
    function loadSections(classId) {
        if (classId) {
            $.ajax({
                type: "GET",
                url: "{{ url('department/class/section') }}?class_id=" + classId,
                success: function(data) {
                    $('#section_id').empty();
                    $.each(data, function(key, value) {
                        $('#section_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    
                    // Preselect old value if any
                    @if(old('section_id'))
                        $('#section_id').val(@json(old('section_id'))).trigger('change');
                    @endif
                }
            });
        } else {
            $('#section_id').empty();
        }
    }
});
</script>
@endsection