@extends('layouts.master')
@section('page_title', 'Edit Student')
@section('content')

        <div class="card">
            <div class="card-header bg-white header-elements-inline">
                <h6 id="ajax-title" class="card-title">Please fill The form Below To Edit record of {{ $sr->user->name }}</h6>

                {!! Qs::getPanelOptions() !!}
            </div>
            {{-- ajax-update --}}
            <form method="post" enctype="multipart/form-data" class="wizard-form steps-validation ajax-update " data-reload="#ajax-title" action="{{ route('students.update', Qs::hash($sr->id)) }}" data-fouc>
                @csrf @method('PUT')
                <h6>Personal data</h6>
                <fieldset>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name: <span class="text-danger">*</span></label>
                                <input value="{{ $sr->user->name }}" required type="text" name="name" placeholder="Full Name" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address: <span class="text-danger">*</span></label>
                                <input value="{{ $sr->user->address }}" class="form-control" placeholder="Address" name="address" type="text" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email address: <span class="text-danger">*</span></label>
                                <input value="{{ $sr->user->email  }}" type="email" name="email" class="form-control" placeholder="your@email.com">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender">Gender: <span class="text-danger">*</span></label>
                                <select class="select form-control" id="gender" name="gender" required data-fouc data-placeholder="Choose..">
                                    <option value=""></option>
                                    <option {{ ($sr->user->gender  == 'Male' ? 'selected' : '') }} value="Male">Male</option>
                                    <option {{ ($sr->user->gender  == 'Female' ? 'selected' : '') }} value="Female">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Phone:</label>
                                <input value="{{ $sr->user->phone  }}" type="text" name="phone" class="form-control" placeholder="" >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Emergency Contact:</label>
                                <input value="{{ $sr->user->phone2  }}" type="text" name="phone2" class="form-control" placeholder="" >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="my_parent">Emergency Contact Name: </label>
                                    <input value="{{ $sr->my_parent }}" type="text" name="my_parent" id= "my_parent" placeholder="Full Name" class="form-control">
                                </div>
                            </div>

                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date of Birth:</label>
                                <input name="dob" value="{{ $sr->user->dob  }}" type="text" class="form-control date-pick" placeholder="Select Date...">

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nal_id">Nationality: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..." required name="nal_id" id="nal_id" class="select-search form-control">
                                    <option value=""></option>
                                    @foreach($nationals as $na)
                                        <option {{  ($sr->user->nal_id  == $na->id ? 'selected' : '') }} value="{{ $na->id }}">{{ $na->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="state_id">County Of Origin: </label>
                            <select onchange="getLGA(this.value)"  data-placeholder="Choose.." class="select-search form-control" name="state_id" id="state_id">
                                <option value=""></option>
                                @foreach($states as $st)
                                    <option {{ ($sr->user->state_id  == $st->id ? 'selected' : '') }} value="{{ $st->id }}">{{ $st->name }}</option>
                                @endforeach
                            </select>
                        </div>



                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status: <span class="text-danger">*</span></label>
                                <select class="select form-control" id="status" name="status" required data-fouc data-placeholder="Choose..">
                                    <option value=""></option>
                                    <option {{ ($sr->status  == '1' ? 'selected' : '') }} value="1">Active</option>
                                    <option {{ ($sr->status  == '0' ? 'selected' : '') }} value="0">Dropped</option>
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bg_id">Blood Group: </label>
                                <select class="select form-control" id="bg_id" name="bg_id" data-fouc data-placeholder="Choose..">
                                    <option value=""></option>
                                    @foreach(App\Models\BloodGroup::all() as $bg)
                                        <option {{ ($sr->user->bg_id  == $bg->id ? 'selected' : '') }} value="{{ $bg->id }}">{{ $bg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Upload Passport Photo:</label>
                                <input value="{{ old('photo') }}" accept="image/*" type="file" name="photo" class="form-input-styled" data-fouc>
                                <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb</span>
                            </div>
                        </div>
                    </div>

                </fieldset>

                <h6>Academic Data</h6>
                <fieldset>
                    <div class="row">
                        @if($sr->my_class_id !==NULL)
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="my_class_id">Class: </label>
                                <select onchange="getClassSections(this.value)" name="my_class_id" required id="my_class_id" class="form-control select-search" data-placeholder="Select Class">
                                    <option value=""></option>
                                    @foreach($my_classes as $c)
                                        <option {{ $sr->my_class_id == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="section_id">Section: </label>
                                <select name="section_id" required id="section_id" class="form-control select" data-placeholder="Select Section">
                                    <option value="{{ $sr->section_id }}">{{ $sr->section->name }}</option>
                                </select>
                            </div>
                        </div>
                        @else
                        <div class="col-md-3 college-data" >
                            <div class="form-group">
                                <label for="college_id">College: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..."  name="college_id" id="college_id" class="select-search form-control">
                                    <option value=""></option>
                                    @foreach($college as $college)
                                        <option {{ $sr->college_id == $college->id ? 'selected' : '' }} value="{{ $college->id}}">{{ $college->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 college-data">
                            <div class="form-group">
                                <label for="department_id">Department: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..." required name="department_id" id="department_id" class="select-search form-control">
                                    <option value="">Please select department</option>
                                @foreach($department as $dept)
                                    <option {{ $sr->department_id == $dept->id ? 'selected' : '' }} value="{{ $dept->id}}">{{ $dept->name }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 college-data" >
                            <div class="form-group">
                                <label for="major">Major: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..."  name="major" id="major" class="select-search form-control">
                                @foreach($major as $maj)
                                    <option {{ $sr->major == $maj->major ? 'selected' : '' }} value="{{ $maj->major}}">{{ $maj->major }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 college-data">
                            <div class="form-group">
                                <label for="minor">Minor: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..."  name="minor" id="minor" class="select-search form-control">
                                    @foreach($minor as $min)
                                    <option {{ $sr->minor == $min->minor ? 'selected' : '' }} value="{{ $min->minor}}">{{ $min->minor }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 college-data">
                            <div class="form-group">
                                <label for="level">Level: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..."  name="level" id="level" class="select-search form-control">
                                    <option {{ $sr->level == 'Freshmen' ? 'selected' : '' }} value="Freshmen">Freshmen</option>
                                    <option {{ $sr->level == 'Sophomore' ? 'selected' : '' }}  value="Sophomore">Sophomore</option>
                                    <option {{ $sr->level == 'Junior' ? 'selected' : '' }}  value="Junior">Junior</option>
                                    <option {{ $sr->level == 'Senior' ? 'selected' : '' }}  value="Senior">Senior</option>
                                </select>
                            </div>
                        </div>



                        @endif
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year_admitted">Year Admitted: </label>
                                <select name="year_admitted" data-placeholder="Choose..." id="year_admitted" class="select-search form-control">
                                    <option value=""></option>
                                    @for($y=date('Y', strtotime('- 2 years')); $y<=date('Y'); $y++)
                                        <option {{ ($sr->year_admitted == $y) ? 'selected' : '' }} value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>


                      <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Admission Number: <span class="text-danger">*</span></label>
                                <input value="{{ $sr->adm_no  }}" required type="text" name="adm_no" class="form-control" placeholder="">
                            </div>
                        </div>


                            <div class="col-md-6">
                            <div class="form-group">
                                <label>Name of Previous School: <span class="text-danger">*</span></label>
                                <input value="{{ $sr->previous_school }}" type="text" id="previous_school" name="previous_school" class="form-control">
                                </div>
                            </div>

                    </div>
                    <script>
                $('#college_id').change(function(){
                var college_id = $(this).val();
                if (college_id) {
                    $.ajax({
                        type: "GET",
                        url: "{{url('colleges/department')}}?college="+college_id,
                        success: function (data) {
                            // console.log(college_id);
                            $('#department_id').empty().append('<option>Please select</option>');
                            $.each(data, function (key) {
                                $('#department_id').append('<option value="' + data[key].id + '">' + data[key].name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#department_id').empty();
                }
            });



            $('#department_id').change(function(){
                var class_id = $(this).val();
                if (class_id) {
                    $.ajax({
                        type: "GET",
                        url: "{{url('colleges/majors')}}?department="+class_id,
                        success: function (data) {
                            // console.log(data);

                            $('#major').empty().append('<option>Please select</option>');
                            $.each(data, function (key) {
                                $('#major').append('<option value="' + data[key].major + '">' + data[key].major + '</option>');
                            });
                        }
                    });
                } else {
                    $('#major').empty();
                }
            });



                $('#major').change(function(){
                var class_id = $(this).val();
                if (class_id) {
                    $.ajax({
                        type: "GET",
                        url: "{{url('colleges/minors')}}?major="+class_id,
                        success: function (data) {
                            // console.log(data,class_id);

                            $('#minor').empty().append('<option>Please select</option>');
                            $.each(data, function (key) {
                                $('#minor').append('<option value="' + data[key].minor + '">' + data[key].minor + '</option>');
                            });
                        }
                    });
                } else {
                    $('#minor').empty();
                }
            });
                    </script>
                </fieldset>

            </form>
        </div>
@endsection
