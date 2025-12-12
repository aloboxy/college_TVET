<form method="post" action="{{ route('students.promote_selector') }}">
    @csrf
    <div class="row">
        <div class="col-md-10">
            <fieldset>

                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="pass" class="col-form-label font-weight-bold">Fail/Pass:</label>
                            <select required id="pass" name="pass" class="form-control">

                                <option value="0">Passed</option>
                                <option value="1">Failed</option>

                            </select>
                        </div>
                    </div>


                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="session" class="col-form-label font-weight-bold">Year:</label>
                            <select required id="year" name="year" class="form-control">
                                <option value="">Academic Year</option>

                            @foreach($years as $y)
                                <option value="{{$y->year }}">{{ $y->year}}</option>
                            @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="exam_id" class="col-form-label font-weight-bold">Semester:</label>
                            <select required id="exam_id" name="exam_id" class="form-control">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fc" class="col-form-label font-weight-bold">From Class:</label>
                            <select required onchange="getClassSections(this.value, '#fs')" id="fc" name="fc" class="form-control select">
                                <option value="">Select Class</option>
                                @foreach($my_classes as $c)
                                    <option {{ ($selected && $fc == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fs" class="col-form-label font-weight-bold">From Section:</label>
                            <select required id="fs" name="fs" data-placeholder="Select Class First" class="form-control select">
                                @if($selected && $fs)
                                    <option value="{{ $fs }}">{{ $sections->where('id', $fs)->first()->name }}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tc" class="col-form-label font-weight-bold">To Class:</label>
                            <select required onchange="getClassSections(this.value, '#ts')" id="tc" name="tc" class="form-control select">
                                <option value="">Select Class</option>
                                @foreach($my_classes as $c)
                                    <option {{ ($selected && $tc == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="ts" class="col-form-label font-weight-bold">To Section:</label>
                            <select required id="ts" name="ts" data-placeholder="Select Class First" class="form-control select">
                                @if($selected && $ts)
                                    <option value="{{ $ts }}">{{ $sections->where('id', $ts)->first()->name }}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                </div>

            </fieldset>
        </div>

        <div class="col-md-2 mt-4">
            <div class="text-right mt-1">
                <button type="submit" class="btn btn-primary">Manage Promotion <i class="icon-paperplane ml-2"></i></button>
            </div>
        </div>

    </div>

</form>

<script>
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
            $('#exam_id').append('<option value="' + data[key].id +'">'+data[key].name + '</option>');
        });
    }

});
}
else{
$('#exam_id').empty();
}
})
</script>
