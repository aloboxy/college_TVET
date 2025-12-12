<form method="post" action="{{ route('courselist.get') }}">
    @csrf
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="session" class="col-form-label font-weight-bold">Year:</label>
                <select required id="year" name="year" class="form-control">
                    <option value="">Academic Year</option>
                    @foreach($years as $y)
                    <option value="{{$y->year }}">{{ $y->year}}</option>
                    @endforeach
                </select>
                @if (session()->has('message'))
                <div class="alert alert-warning">
                    {{ session('message') }}
                </div>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="exam_id" class="col-form-label font-weight-bold">Exam:</label>
                <select required id="exam_id" name="exam_id" class="form-control">

                </select>
                @if (session()->has('message'))
                <div class="alert alert-warning">
                    {{ session('message') }}
                </div>
                @endif
            </div>
        </div>


        <div class="col-md-3">
            <div class="form-group">
                <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                <select required class="form-control" id="my_class_id" name="my_class_id"
                    wire:model.live="selectedClass">
                    <option value="">Select Class</option>

                </select>
            </div>
        </div>

        {{-- <div class="col-md-1">
            <div class="form-group">
                <label for="section_id" class="col-form-label font-weight-bold">Semester-Level:</label>
                <select required class="form-control" id="section_id" name="section_id">
                </select>
                @if (session()->has('message'))
                <div class="alert alert-warning">
                    {{ session('message') }}
                </div>
                @endif
            </div>
        </div> --}}

        {{-- <div class="col-md-4">
            <div class="form-group">
                <label for="subject_id" class="col-form-label font-weight-bold">Subject:</label>
                <select data-placeholder="Select Class First" required class="select-search form-control"
                    id="subject_id" name="subject_id">
                    <option value="">Select Course</option>

                </select>

            </div>
        </div> --}}



        <div class="col-md-2 mt-4">
            <div class="text-right mt-1">
                <button type="submit" class="btn btn-primary">Get Records <i class="icon-paperplane ml-2"></i></button>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': $('mete[name="csrf-token"]').attr('content')
}
});




$('#year').change(function(){
year = $(this).val();
// alert(year);
if(year)
{
$.ajax({
    type: "GET",
    url: "{{url('year/semester') }}?year="+year,
    success:function(data){
        // console.log(data);
        $('#exam_id').empty();
        $('#my_class_id').empty();
        $('#section_id').empty();
        $('#exam_id').append('<option>Please select</option>');
        $.each(data, function(key){
            $('#exam_id').append('<option value="' + data[key].term +'">'+data[key].name + '</option>');
        });
    }

});
}
else{
$('#exam_id').empty();
}
})


$('#exam_id').change(function(){
var exam = $(this).val();
// alert(year);
if(exam)
{
$.ajax({
    type: "GET",
    url: "{{url('year/class') }}",
    success:function(data){
        // console.log(data);
        $('#my_class_id').empty();
        $('#subject_id').empty();
        $('#my_class_id').append('<option>Please select</option>');
        $.each(data, function(key){
            $('#my_class_id').append('<option value="' + data[key].id +'">'+data[key].name + '</option>');
        });
    }

});
}
else{
$('#my_class_id').empty();
}
})



$('#my_class_id').change(function(){
var class_id  = $(this).val();
// alert(year);
if(class_id)
{
$.ajax({
    type: "GET",
    url: "{{url('class/section')}}?class="+class_id,
    success:function(data){
        // console.log(data);
        $('#section_id').empty();
        $('#section_id').append('<option>Please select</option>');
        $.each(data, function(key){
            $('#section_id').append('<option value="' + data[key].id +'">'+data[key].name + '</option>');
        });
    }

});
}
else{
$('#section_id').empty();
}
})



$('#section_id').change(function(){
var section_id  = $(this).val();
var class_id = $('#my_class_id').val();
var exam = $('#exam_id').val();
var year = $('#year').val();
// alert(exam);
if(class_id && section_id && exam && year)
{
$.ajax({
    type: "GET",
    url: "{{url('class/subject')}}",
    data: {
        'section_id':section_id,
        'class_id':class_id,
        'exam':exam,
        'year':year
    },
    success:function(data){
        // console.log(data);
        $('#subject_id').empty();
        $('#subject_id').append('<option>Please select</option>');
        $.each(data, function(key){
            $('#subject_id').append('<option value="' + data[key].id +'">'+data[key].name+' '+'session'+'-'+data[key].session +'--'+data[key].teacher+'</option>');
        });
    }

});
}
else{
$('#section_id').empty();
}
})
</script>
