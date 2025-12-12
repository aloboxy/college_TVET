
   <div class="row">
        <div class="col-md-1">
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
               <select required id="department_id" name="department_id" data-placeholder="Select Department" class="form-control select">
                <option value=""></option>
                @foreach ($departments as $d)
                <option value="{{ $d->id }}" data-code="{{ $d->code }}" {{ old('department_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                @endforeach
                </select>
            </div>
        </div>


        <div class="col-md-2" id="class-cohort-group" style="display: none;">
            <div class="form-group row">
                <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Class</label>
                <div class="col-lg-9">
                    <select id="my_class_id" name="my_class_id" class="form-control select-search" >
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

         

            <div class="col-md-2">
                <div class="form-group">
                    <label for="balance_inquiry" class="col-form-label font-weight-bold">Balance Inquiry:</label>
                    <select required class="select-search form-control" id="balance_inquiry" name="balance_inquiry" >
                <option value="" disabled selected>Select Balance Inquiry</option>
                <option value="clear">Clear</option> 
                <option value="unclear">Unclear</option> 
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
       


<div class="col-md-2 mt-4">
<div class="text-right mt-1">
<button type="submit" class="btn btn-primary">Search <i class="icon-paperplane ml-2"></i></button>
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
            $('#exam_id').append('<option value="' + data[key].id +'">'+data[key].name + '</option>');
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

$(document).ready(function() {
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



//loading courses for selection
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