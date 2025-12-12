<div>
    {{-- Because she competes with no one, no one can compete with her. --}}

    <div class="col-mb-2">
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

    <div  class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="exam_id" class="col-form-label font-weight-bold">Exam:</label>
                    <select required id="exam_id" name="exam_id" class="form-control" wire:model.live="selectedExam">
                        <option value="">Select Exam</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id}}">{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


        <div class="col-md-2">
            <div class="form-group">
                <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                <select required class="select-search form-control" id="my_class_id" name="my_class_id" wire:model.live="selectedClass">
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="section_id" class="col-form-label font-weight-bold">Cohort:</label>
                <select data-placeholder="Select Class First" required class="form-control select" id="section_id" name="section_id" wire:model.live="selectedSection">
                    <option value="">Select a Cohort</option>
                @foreach($sections as $sec)
                    <option value="{{ $sec->id }}">{{ $sec->name ?? 'unknown'}}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group" >
                <label for="student_id" class="col-form-label font-weight-bold">Student Name</label>
                <select data-placeholder="Select Class First" required class="select-search form-control" id="student_id" name="student_id" wire:model.live="selectedStudent">
                    <option value="">Select Course</option>
                    @foreach($students as $student)
                    <option value="{{ $student->user->id }}">{{ $student->user->name ?? 'unknown'}}-{{ $student->adm_no }}</option>
                @endforeach
                </select>
            </div>
            <input type="search" wire:model.live="search" class="form-control float-end mx-2" placeholder="Search for ID only" style="width: 230px" />

        </div>



            <div class="text-center" wire:loading>
                <div class="loading-container">
                <div class="loading"></div>
                <div id="loading-text">Processing</div>
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

             <div class="table-responsive">
                 <table class="table datatable-button-html5-columns">
                             <thead>
                             <tr>
                                 <th>#</th>
                                 <th>Course Name</th>
                                 <th>Teacher</th>
                                 <th>Semester</th>
                                 <th>Session</th>
                                 <th width="10%">Room</th>
                                 <th>Day</th>
                                 <th>Time From</th>
                                 <th>Time To</th>
                             </tr>
                             </thead>
@if(!is_null($selectedExam && $selectedStudent))
                             <tbody>

                                @foreach($courses as $s)
                                 <tr wire:key="{{$s->id}}">
                                     <td>{{ $loop->iteration }}</td>
                                     <td>{{ $s->subject->name ?? 'unknown' }}</td>
                                     <td>{{ $s->teacher->name ?? 'unknown' }}</td>
                                     <td>
                                         @if($s->term_id == 1)
                                         {{'First Semester'}}
                                         @else
                                         {{ 'Second Semester' }}
                                         @endif
                                     </td>
                                     <td>{{ $s->session ?? 'TBA'}}</td>
                                     <td width="10%">{{ $s->room ?? 'TBA'}}</td>
                                     <td>{{ $s->day ?? 'Unknown'}}</td>
                                     <td>{{Carbon\Carbon::parse($s->time_from)->format('g:i A') ?? 'unknown' }}</td>
                                     <td>{{Carbon\Carbon::parse($s->time_to)->format('g:i A') ?? 'unknown'}}</td>
                                 </tr>
                                 @endforeach
                             </tbody>
            @endif
                         </table>
                     </div>
</div>
