<div class="card">
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="session" class="col-form-label font-weight-bold">Year:</label>
            <select required id="year" name="year" class="form-control" wire:model.live="selectedYear">
                <option value="">Academic Year</option>
            @foreach($years as $y)
                <option {{ $y->year }}>{{ $y->year}}</option>
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
            <label for="exam_id" class="col-form-label font-weight-bold">Exam:</label>
            <select required id="exam_id" name="exam_id" class="form-control" wire:model.live="selectedExam">
                <option value="">Select Exam</option>
                @foreach($exams as $exam)
                    <option value="{{ $exam->id }}">{{ $exam->name ?? 'unknown' }}</option>
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
        <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
        <select required class="form-control" id="my_class_id" name="my_class_id" wire:model.live="selectedClass">
            <option value="">Select Class</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-2">
    <div class="form-group">
        <label for="section_id" class="col-form-label font-weight-bold">Cohort:</label>
        <select data-placeholder="Select Class First" required class="form-control select" id="section_id" name="section_id" wire:model.live="selectedSection">
            <option value= 0>Select a Cohort</option>
        @foreach($sections as $sec)
            <option value="{{ $sec->id }}">{{ $sec->name ?? 'unknown'}}</option>
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
        <label for="status" class="col-form-label font-weight-bold">Status</label>
        <select select data-placeholder="Select Class First" required class="form-control select" id="status" name="status" wire:model.live="selectedStatus">
            <option value="">Select status</option>
            <option value="0">Pass</option>
            <option value="1">Failed</option>
        </select>
    </div>
</div>
{{-- {{ $selectedSection }}
{{ $selectedExam }}
{{ $selectedClass }} --}}
{{-- {{ $students }} --}}


</div>

<div class="text-center">
    <div class="text-center" wire:loading>
    <div class="loading-container">
    <div class="loading"></div>
    <div id="loading-text">Loading..</div>
</div>


<!--// Link Attribution -->
<style>
    #link {color: #E45635;display:block;font: 12px "Helvetica Neue", Helvetica, Arial, sans-serif;text-align:center; text-decoration: none;}
#link:hover {color: #b82222}

#link, #link:hover {-webkit-transition: color 0.5s ease-out;-moz-transition: color 0.5s ease-out;-ms-transition: color 0.5s ease-out;-o-transition: color 0.5s ease-out;transition: color 0.5s ease-out;}

/** BEGIN CSS **/
        body {background: #f3efef;}
        @keyframes rotate-loading {
            0%  {transform: rotate(0deg);-ms-transform: rotate(0deg); -webkit-transform: rotate(0deg); -o-transform: rotate(0deg); -moz-transform: rotate(0deg);}
            100% {transform: rotate(360deg);-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); -o-transform: rotate(360deg); -moz-transform: rotate(360deg);}
        }

        @-moz-keyframes rotate-loading {
            0%  {transform: rotate(0deg);-ms-transform: rotate(0deg); -webkit-transform: rotate(0deg); -o-transform: rotate(0deg); -moz-transform: rotate(0deg);}
            100% {transform: rotate(360deg);-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); -o-transform: rotate(360deg); -moz-transform: rotate(360deg);}
        }

        @-webkit-keyframes rotate-loading {
            0%  {transform: rotate(0deg);-ms-transform: rotate(0deg); -webkit-transform: rotate(0deg); -o-transform: rotate(0deg); -moz-transform: rotate(0deg);}
            100% {transform: rotate(360deg);-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); -o-transform: rotate(360deg); -moz-transform: rotate(360deg);}
        }

        @-o-keyframes rotate-loading {
            0%  {transform: rotate(0deg);-ms-transform: rotate(0deg); -webkit-transform: rotate(0deg); -o-transform: rotate(0deg); -moz-transform: rotate(0deg);}
            100% {transform: rotate(360deg);-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); -o-transform: rotate(360deg); -moz-transform: rotate(360deg);}
        }

        @keyframes rotate-loading {
            0%  {transform: rotate(0deg);-ms-transform: rotate(0deg); -webkit-transform: rotate(0deg); -o-transform: rotate(0deg); -moz-transform: rotate(0deg);}
            100% {transform: rotate(360deg);-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); -o-transform: rotate(360deg); -moz-transform: rotate(360deg);}
        }

        @-moz-keyframes rotate-loading {
            0%  {transform: rotate(0deg);-ms-transform: rotate(0deg); -webkit-transform: rotate(0deg); -o-transform: rotate(0deg); -moz-transform: rotate(0deg);}
            100% {transform: rotate(360deg);-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); -o-transform: rotate(360deg); -moz-transform: rotate(360deg);}
        }

        @-webkit-keyframes rotate-loading {
            0%  {transform: rotate(0deg);-ms-transform: rotate(0deg); -webkit-transform: rotate(0deg); -o-transform: rotate(0deg); -moz-transform: rotate(0deg);}
            100% {transform: rotate(360deg);-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); -o-transform: rotate(360deg); -moz-transform: rotate(360deg);}
        }

        @-o-keyframes rotate-loading {
            0%  {transform: rotate(0deg);-ms-transform: rotate(0deg); -webkit-transform: rotate(0deg); -o-transform: rotate(0deg); -moz-transform: rotate(0deg);}
            100% {transform: rotate(360deg);-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); -o-transform: rotate(360deg); -moz-transform: rotate(360deg);}
        }

        @keyframes loading-text-opacity {
            0%  {opacity: 0}
            20% {opacity: 0}
            50% {opacity: 1}
            100%{opacity: 0}
        }

        @-moz-keyframes loading-text-opacity {
            0%  {opacity: 0}
            20% {opacity: 0}
            50% {opacity: 1}
            100%{opacity: 0}
        }

        @-webkit-keyframes loading-text-opacity {
            0%  {opacity: 0}
            20% {opacity: 0}
            50% {opacity: 1}
            100%{opacity: 0}
        }

        @-o-keyframes loading-text-opacity {
            0%  {opacity: 0}
            20% {opacity: 0}
            50% {opacity: 1}
            100%{opacity: 0}
        }
        .loading-container,
        .loading {
            height: 100px;
            position: relative;
            width: 100px;
            border-radius: 100%;
        }


        .loading-container { margin: 40px auto }

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
            font-family: "Helvetica Neue, "Helvetica", ""arial";
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
</style>
    </div>

 </div>

@if(!is_null($selectedClass && $selectedSection && $selectedExam && $selectedYear && $selectedStatus))
<div class="card-body">
    <table class="table datatable-button-html5-columns">
        <thead>
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>Exam Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ strtoupper($student->user->name) }}</td>
                <td>
            @if($student->failed == 0)
                {{ 'Pass' }}
                @else
                {{ 'Failed' }}
            @endif
            </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif

</div>





