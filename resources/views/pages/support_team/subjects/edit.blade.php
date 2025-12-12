@extends('layouts.master')
@section('page_title', 'Edit Course - '.$s->name. ' ('.$s->department->name.')')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Edit Course for- {{$s->department->name }} Department</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <style>
            .toggle-container {
                margin: 20px 0;
            }

            .toggle-button {
                position: relative;
                display: inline-block;
                width: 100px;
                height: 34px;
            }

            .toggle-checkbox {
                display: none;
            }

            .toggle-label {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: #ccc;
                border-radius: 34px;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .toggle-inner {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 12px;
                color: white;
                transition: opacity 0.3s;
            }

            .toggle-switch {
                position: absolute;
                top: 2px;
                left: 2px;
                width: 30px;
                height: 30px;
                background-color: white;
                border-radius: 50%;
                transition: transform 0.3s;
            }

            .toggle-checkbox:checked+.toggle-label {
                background-color: #4CAF50;
            }

            .toggle-checkbox:checked+.toggle-label .toggle-switch {
                transform: translateX(64px);
            }

            .toggle-checkbox:checked+.toggle-label .toggle-inner::before {
                content: 'Yes';
            }

            .toggle-checkbox:not(:checked)+.toggle-label .toggle-inner::before {
                content: 'No';
            }
        </style>
        <div class="row">
            <div class="col-md-6">
                <form class="ajax-update-h" method="post" action="{{ route('subjects.update', $s->id) }}">
                    @csrf @method('PUT')
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Name <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input name="name" value="{{ $s->name }}" required type="text" class="form-control"
                                placeholder="Name of Subject">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Description</label>
                        <div class="col-lg-9">
                            <input name="slug" value="{{ $s->slug }}" type="text" class="form-control"
                                placeholder="Short Name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Department <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select required data-placeholder="Select Class" class="form-control select" multiple
                                name="department_id[]" id="department_id[]">
                                @foreach($my_classes as $c)
                                <option
                                    value="{{ $c->id }}"
                                    {{ isset($s) && in_array($c->id, explode(',', $s->department_id)) ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option></option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="prerequisite" class="col-lg-3 col-form-label font-weight-semibold">Prerequisite <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select data-placeholder="Select Prerequisite" class="form-control select-search"
                                name="prerequisite_id" id="prerequisite_id">
                                <option value=""></option>
                            @foreach($subjects as $c)
                                <option {{ $s->prerequisite_id ==$c->id ? 'selected' : '' }} value="{{ $c->id
                                    }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="term_id" class="col-lg-3 col-form-label font-weight-semibold">Semester: <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select class="select form-control" id="term_id" name="term_id" required data-fouc
                                data-placeholder="Choose..">
                                <option value=""></option>
                                <option {{ ($s->term_id == 1? 'selected' : '') }} value="1">First Semester</option>
                                <option {{ ($s->term_id == 2 ? 'selected' : '') }} value="2">Second Semester</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="level"
                            class="col-lg-3 col-form-label font-weight-semibold">Level</label>
                        <div class="col-lg-9">
                            <select data-placeholder="Level" class="form-control select" name="level"
                                id="term_id">
                                <option {{ ($s->level =='' ) ? 'selected' : '' }} value="">
                                </option>
                                <option {{ ($s->level == "Freshmen") ? 'selected' : '' }} value="Freshmen">Freshmen
                                </option>
                                <option {{ ($s->level == "Sophomore") ? 'selected' : '' }} value="Sophomore">Sophomore
                                </option>
                                <option {{ ($s->level == "Junior") ? 'selected' : '' }} value="Junior">Junior
                                </option>
                                <option {{ ($s->level == "Senior") ? 'selected' : '' }} value="Senior">Senior
                                </option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Credit Hour</label>
                        <div class="col-lg-9">
                            <input name="credit" value="{{ $s->credit }}" type="text" class="form-control"
                                placeholder="Credit Hour">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="toggle-container col-lg-9">
                            <label for="toggle" class="col-lg-3 col-form-label font-weight-semibold">Clinical:</label>
                            <div class="toggle-button">
                                <input type="checkbox" id="toggle" name="clinical" class="toggle-checkbox"
                                    {{$s->clinical == 1 ? 'checked' : '' }}>
                                <label for="toggle" class="toggle-label">
                                    <span class="toggle-inner"></span>
                                    <span class="toggle-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>




                    {{-- <div class="form-group row">
                        <label for="teacher_id" class="col-lg-3 col-form-label font-weight-semibold">Teacher</label>
                        <div class="col-lg-9">
                            <select data-placeholder="Select Teacher" class="form-control select-search"
                                name="teacher_id" id="teacher_id">
                                <option value=""></option>
                                @foreach($teachers as $t)
                                <option {{ $s->teacher_id == $t->id ? 'selected' : '' }} value="{{ Qs::hash($t->id)
                                    }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit form <i
                                class="icon-paperplane ml-2"></i></button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

{{--subject Edit Ends--}}

@endsection
