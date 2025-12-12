<div>
    <div class="row">
        <div class="col-mb-2">
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
                <select required id="exam_id" name="exam_id" class="form-control" wire:model.live="selectedExam">
                    <option value="">Select Exam</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="my_class_id" class="col-form-label font-weight-bold">Department:</label>
                <select required class="select-search form-control" id="department_id" name="department_id" wire:model.live="selectedDepartment">
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="level" class="col-form-label font-weight-bold">Level:</label>
                <select required class="form-control select" id="level" name="level" wire:model.live="selectedLevel">
                    <option value="">Please Select Level</option>
                    @foreach($levels as $level)
                        <option value="{{ $level }}">{{ $level }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="subject_id" class="col-form-label font-weight-bold">Subject:</label>
                <select required class="select-search form-control" id="subject_id" name="subject_id" wire:model.live="selectedSubject">
                    <option value="">Select Course</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">
                            {{ $subject->subject->name ?? 'unknown' }} - session-{{ $subject->session }} {{ $subject->user->name ?? 'unknown' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

       
    </div>
    <div  wire:loading>
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

    @if (session()->has('message'))
        <div class="alert alert-success text-center">
            {{ session('message') }}
        </div>
    @endif

    @if(!is_null($selectedSubject))
    <div>
        <div class="float-md-right">
            <input type="search" wire:model="search" class="form-control float-end mx-2" placeholder="Search for ID only" style="width: 230px" />
            <table class="table datatable-button-html5-columns">
                <h1>Class Listing</h1>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Adm_No</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $su)
                        <tr wire:key="{{ $su->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $su->name }}</td>
                            <td>{{ $su->username }}</td>
                            <td>
                                <button type="button" wire:click="enrolled({{ $su->id }})" class="btn btn-success" wire:loading.attr="disabled">
                                    <span wire:loading.remove>Add</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                {{ $students->links() }}
            </div>
        </div>

        <div class="float-md-left">
            <input type="search" wire:model.live="search2" class="form-control float-end mx-2" placeholder="Search for ID only" style="width: 230px" />
            <table class="table datatable-button-html5-columns">
                <h1>Course Listing</h1>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Adm_No</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($com as $c)
                        <tr wire:key="{{ $c->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $c->user->name }}</td>
                            <td>{{ $c->user->username }}</td>
                            <td>
                                <button type="button" wire:click="delete({{ $c->id }})" class="btn btn-danger" wire:loading.attr="disabled">
                                    <span wire:loading.remove>Drop Course</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $com->links() }}
        </div>
    </div>
    @endif
    <div id="custom-flash-message" style="
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    z-index: 9999;
    min-width: 280px;
    text-align: center;
">
    <span id="flash-message-text" style="font-size: 1.1rem;"></span>
    <br><br>
    <button onclick="hideFlashMessage()" id="flash-ok-button" style="
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
    ">OK</button>
</div>


<script>
    window.addEventListener('course-flash', event => {
        const box = document.getElementById('custom-flash-message');
        const message = document.getElementById('flash-message-text');
        const button = document.getElementById('flash-ok-button');

        const { message: msg, type } = event.detail;

        message.textContent = msg;

        // Set styles based on type
        switch (type) {
            case 'success':
                box.style.backgroundColor = '#d1fae5';
                box.style.color = '#065f46';
                button.style.backgroundColor = '#10b981';
                button.style.color = '#ffffff';
                break;
            case 'error':
                box.style.backgroundColor = '#fee2e2';
                box.style.color = '#991b1b';
                button.style.backgroundColor = '#ef4444';
                button.style.color = '#ffffff';
                break;
            default:
                box.style.backgroundColor = '#e0f2fe';
                box.style.color = '#1e40af';
                button.style.backgroundColor = '#3b82f6';
                button.style.color = '#ffffff';
        }

        box.style.display = 'block';

        setTimeout(() => {
            Livewire.emit('refreshpage');
            hideFlashMessage();
        }, 3000);
    });

    function hideFlashMessage() {
        const box = document.getElementById('custom-flash-message');
        box.style.display = 'none';
    }
</script>
</div>
