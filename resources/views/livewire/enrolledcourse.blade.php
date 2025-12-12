<div>
    @if(Qs::getSetting('planning_open') == 1)
    <div class="col-md-5">
        @if($paid == 1)
        <div >
            <label><strong>Department :</strong></label>
            <select id='department' wire:key="$selectedDepartment" wire:model.live="selectedDepartment"
                class="form-control" style="width: 200px">
                <option value="">Please Select Department</option>
                @foreach ($department as $dept )
                <option value="{{$dept->id}}">{{$dept->name}}</option>
                @endforeach
            </select>
        </div>

        <div >
            <label><strong>Level :</strong></label>
            <select id='selectedLevel' wire:key="$selectedLevel" wire:model.live="selectedLevel"
                class="form-control" style="width: 200px">
                <option value="">Please Select Level</option>
                <option value="Freshmen">Freshmen</option>
                <option value="Sophomore">Sophomore</option>
                <option value="Junior">Junior</option>
                <option value="Senior">Senior</option>
            </select>
        </div>
    </div>
        @else
            <h1 style="text-align: center; color:#b82222">{{ "Please Visit the Business office" }}</h1>
        @endif

    <br>


    <div class="text-center">
        <h1>Courses For Planning</h1>
        <div class="text-center" wire:loading>
            <div class="loading-container">
                <div class="loading"></div>
                <div id="loading-text">Processing</div>
            </div>

            @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif


            @if (session()->has('message'))
            <div class="alert alert-warning">
                {{ session('message') }}
            </div>
            @endif


            <!--// Link Attribution -->
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
            </style>
        </div>

    </div>

    @if(!is_null($selectedLevel))
    <div>
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Course Name</th>
                    <th>Teacher</th>
                    <th>Semester</th>
                    <th width="10%">Session</th>
                    <th>Room</th>
                    <th>Day</th>
                    <th>Time From</th>
                    <th>Time To</th>
                    <th>Capacity</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($subjects as $s)
                <tr wire:key="{{$s->id}}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $s->subject->name }}</td>
                    <td>{{ $s->teacher->name }}</td>
                    <td>
                        @if($s->term_id == 1)
                        {{'First Semester'}}
                        @else
                        {{ 'Second Semester' }}
                        @endif
                    </td>
                    <td width="10%">{{ 'session' }}-{{ $s->session ?? 'TBA'}}</td>
                    <td width="10%">{{ 'Room' }}-{{ $s->room ?? 'TBA'}}</td>
                    <td>{{ $s->day ?? 'Unknown'}}</td>
                    <td width="10%">{{Carbon\Carbon::parse($s->time_from)->format('g:i A') ?? 'unknown' }}</td>
                    <td width="10%">{{Carbon\Carbon::parse($s->time_to)->format('g:i A') ?? 'unknown'}}</td>
                    <td>{{ $s->capacity }}/{{ $s->total }}</td>
                    <td class="text-center">
                        <button type="button" wire:click="store({{$s->id}})" class="btn btn-success"
                            wire:loading.attr="disabled"><span wire:loading.remove>Add Course</span>
                            <span wire:loading>Adding..</span>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div>
        {{ $subjects->links() }}
    </div>
    @endif
    @else
    <div class="text-center">
        <h1>Planning Closed</h1>
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

</div>

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