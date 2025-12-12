    <div class="row">

        <div class="col-md-1">
            <div class="form-group">
                <label for="session" class="col-form-label font-weight-bold">Year:</label>
                <select required id="year" name="year" class="form-control" wire:model.live="selectedYear">
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

        <div class="col-md-2">
            <div class="form-group">
                <label for="exam_id" class="col-form-label font-weight-bold">Exam:</label>
                <select required id="exam_id" name="exam_id" class="form-control" wire:model.live="selectedExam">
                    <option value=0>Select Exam</option>
                    @foreach($exams as $exam)
                        <option value="{{  $exam->id  }}">{{ $exam->name ?? 'unknown' }}</option>
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
                <option value=0>Select Class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-1">
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

    <div class="col-mb-3">
        <div class="form-group" >
            <label for="subject_id" class="col-form-label font-weight-bold">Subject:</label>
            <select data-placeholder="Select Class First" required class="select-search form-control" id="subject_id" name="subject_id" wire::model.live="selectedSubject({{ $subjects }})">
                <option value="">Select Course</option>
            @foreach($subjects as $s)
                <option value="{{ $s->id }}">{{ $s->subject->name ?? 'unknown'}}-{{ 'session' }}-{{ $s->session }} {{strtoupper($s->user->name )}}</option>
            @endforeach
            </select>
            {{-- {{ $subjects }} --}}
        </div>
    </div>



<div class="col-md-2 mt-4">
    <div class="text-right mt-1">
    <button type="submit" class="btn btn-primary">Manage Marks <i class="icon-paperplane ml-2"></i></button>
    </div>
    </div>
</div>
