
   <div class="row col-md-12">
        <div class="col-mb-2">
            <div class="form-group">
                <label for="session" class="col-form-label font-weight-bold">Year:</label>
                <select required id="year" name="year" class="form-control">
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

        <div class="col-mb-2 mx-1">
            <div class="form-group">
                <label for="exam_id" class="col-form-label font-weight-bold">Semester:</label>
                <select required id="exam_id" name="exam_id" class="form-control">
                    <option value="">Select Semester</option>
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="my_class_id" class="col-form-label font-weight-bold">Department:</label>
                <select required class="select-search form-control" id="department_id" name="department_id" >
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

          <div class="col-md-2">
            <div class="form-group">
                <label for="section_id" class="col-form-label font-weight-bold">Cohort:</label>
                <select  class="form-control select" id="section_id" name="section_id">
                    <option value="">Please Select Section</option>
                </select>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="form-group">
                <label for="level" class="col-form-label font-weight-bold">Level:</label>
                <select  class="form-control select" id="level" name="level">
                    <option value="">Please Select Level</option>
                </select>
            </div>
        </div>

             <div class="col-md-2">
            <div class="form-group">
                <label for="num_sub" class="col-form-label font-weight-bold">#of Subjects:</label>
                <input type="number" class="form-control" id="num_sub" name="num_sub">
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
       


<div class="col-md-1 mt-4">
<div class="text-right mt-1">
<button type="submit" class="btn btn-primary">Get Results <i class="icon-paperplane ml-2"></i></button>
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

$('#exam_id').change(function(){
    var exam = $(this).val();
    $('#department_id').empty();
    $('#department_id').append('<option>Please select</option>');
    $('#level').empty();
    $('#level').append('<option>Please select</option>');
// console.log(exam);
    if(exam)
    {
        $.ajax({
            type: "GET",
            url: "{{url('exam/department') }}",
            beforeSend: function() {
                        $('#loading').show(); // Show spinner
                    },
            success:function(data){
                // console.log(data);
                $('#department_id').empty();
                $('#department_id').append('<option>Please select</option>');
                $.each(data, function(key){
                    $('#department_id').append('<option value="' + data[key].id +'">'+data[key].name + '</option>');
                });
                $('#loading').hide(); // Hide spinner
            }

        });
    }
    else{
    $('#department_id').empty();
    $('#loading').hide(); // Hide spinner
    }
})

$('#department_id').change(function(){
    var level = ['Freshmen', 'Sophomore', 'Junior', 'Senior'];
    var department_id  = $('#department_id').val();
    var exam = $('#exam_id').val();
    var year = $('#year').val();

    if(department_id && exam && year)
    {

    $('#level').empty(); // Clear existing options
    $('#level').append('<option>Please select</option>');
    $.each(level, function(index, value){
        $('#level').append('<option value="' + value + '">' + value + '</option>');
    });
    }
    else{
        $('#level').empty();
    }
});


$(document).on('change', '#department_id', function () {
    var department_id  = $('#department_id').val();

    $('section_id').empty();
    $('section_id').append('<option>Please select</option>');
     if(department_id)
    {
        $.ajax({
            type: "GET",
            url: "{{url('section/department') }}?department_id="+department_id,
            beforeSend: function() {
                        $('#loading').show(); // Show spinner
                    },
            success:function(data){
                // console.log(data);
                $('#section_id').empty();
                $('#section_id').append('<option>Please select</option>');
                $.each(data, function(key){
                    $('#section_id').append('<option value="' + data[key].id +'">'+data[key].name + '</option>');
                });
                $('#loading').hide(); // Hide spinner
            }

        });
    }
    else{
    $('#section_id').empty();
    $('#loading').hide(); // Hide spinner
    }
    
});


//loading courses for selection
// $('#level').change(function(){
// var department_id  = $('#department_id').val();
// var level = $(this).val();
// var exam = $('#exam_id').val();
// var year = $('#year').val();
// // alert(exam);
// if(department_id && level && exam && year)
// {
// $.ajax({
//     type: "GET",
//     url: "{{url('department/course')}}",
//     data: {
//         'department_id':department_id,
//         'level':level,
//         'exam':exam,
//         'year':year
//     },
//     beforeSend: function() {
//                 $('#loading').show(); // Show spinner
//             },
//     success:function(data){
//         //console.log(data);
//         $('#subject_id').empty();
//         $('#subject_id').append('<option>Please select</option>');
//         $.each(data, function(key){
//             $('#subject_id').append(
//                 '<option value="' + data[key].id + '">' +
//                 data[key].subject + ' session-' + data[key].session +'--' +data[key].name +
//                 '</option>'
//             );
//         });
//         $('#loading').hide(); // Hide spinner
//     }

// });
// }
// else{
// $('#subject_id').empty();
// $('#loading').hide(); // Hide spinner
// }
// }) 
</script>