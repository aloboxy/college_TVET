@extends('layouts.master')
@section('page_title', 'Bill Class Per Semester')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-cash2 mr-2"></i> Select year</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('payments.generalbill') }}">
                @csrf
                <div class="row">
                    <div class="col-md-2">
                                <div class="form-group">
                                    <label for="year" class="col-form-label font-weight-bold">Select Year <span class="text-danger">*</span></label>
                                    <select data-placeholder="Select Year" required id="year" name="year" class="form-control select">
                                        @foreach($years as $yr)
                                            <option value="{{ $yr->year }}">{{ $yr->year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                    </div>
                    <div class="col-md-2">
                                <div class="form-group">
                                    <label for="term_id" class="col-form-label font-weight-semibold">Semester</label>
                                    <select required id="term_id" name="term_id" data-placeholder="Select Semester" class="form-control select">
                                       <option value=""></option>
                                       <option {{ (old('term_id') == 'First Semester') ? 'selected' : '' }} value="1">First Semester</option>
                                       <option {{ (old('term_id') == 'Second Semester') ? 'selected' : '' }} value="2">Second Semester</option>
                                    </select>
                            </div>
                    </div>


                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                            <select required class="form-control" id="my_class_id" name="my_class_id" wire:model.live="selectedClass">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                <option value="{{$class->id}}">{{$class->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
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
                    </div>


                            <div class="col-md-1 mt-4">
                                <div class="text-right mt-1">
                                    <button type="submit" class="btn btn-primary">Submit <i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </div>


                    </div>
                </div>

            </form>
        </div>



<script>
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
</script>
@endsection
