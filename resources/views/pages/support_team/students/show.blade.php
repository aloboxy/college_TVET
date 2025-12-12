@extends('layouts.master')
@section('page_title', 'Student Profile - '.$sr->user->name)
@section('content')
<div class="row">
    <div class="col-md-3 text-center">
        <div class="card">
            <div class="card-body">
                <img style="width: 90%; height:90%" src="{{ $sr->user->photo }}" alt="photo" class="rounded-circle">
                <br>
                <h3 class="mt-3">{{ $sr->user->name }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-highlight">
                    <li class="nav-item">
                        <a href="#" class="nav-link active">{{ $sr->user->name }}</a>
                    </li>
                </ul>

                <div class="tab-content">
                    {{--Basic Info--}}
                    <div class="tab-pane fade show active" id="basic-info">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td class="font-weight-bold">Name</td>
                                <td>{{ $sr->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Admission Number</td>
                                <td>{{ $sr->adm_no }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">College</td>
                                <td>{{ $sr->real_department->college->name ?? $sr->department->college->name }}</td>
                            </tr>

                            <tr>
                                <td class="font-weight-bold">Department</td>
                                <td>{{ $sr->real_department->name ?? $sr->department->name}}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Major</td>
                                <td>{{ $sr->major ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Minor</td>
                                <td>{{ $sr->minor ?? 'N/A'}}</td>
                            </tr>
                            @if($sr->my_class != null && $sr->section != null)
                              <tr>
                                <td class="font-weight-bold">My Class</td>
                                <td>{{ $sr->my_class->name}}</td>
                              </tr>
                              <tr>
                                <td class="font-weight-bold">Cohort</td>
                                <td>{{ $sr->section->name}}</td>
                              </tr>
                            @endif
                            <tr>
                                <td class="font-weight-bold">Emergency Contact</td>
                                <td>{{ $sr->user->phone2 ?? 'N/A'}}</td>
                            </tr>

                            <tr>
                                <td class="font-weight-bold">Year Admitted</td>
                                <td>{{ $sr->year_admitted }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Gender</td>
                                <td>{{ $sr->user->gender }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Address</td>
                                <td>{{ $sr->user->address }}</td>
                            </tr>
                            @if($sr->user->email)
                            <tr>
                                <td class="font-weight-bold">Email</td>
                                <td>{{$sr->user->email }}</td>
                            </tr>
                            @endif
                            @if($sr->user->phone)
                                <tr>
                                    <td class="font-weight-bold">Phone</td>
                                    <td>{{$sr->user->phone.' '.$sr->user->phone2 }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="font-weight-bold">Birthday</td>
                                <td>{{$sr->user->dob }}</td>
                            </tr>

                            <tr>
                                <td class="font-weight-bold">Name of Previous School</td>
                                <td>{{ $sr->previous_school }}</td>
                            </tr>
                            @if($sr->user->bg_id)
                            <tr>
                                <td class="font-weight-bold">Blood Group</td>
                                <td>{{$sr->user->blood_group->name }}</td>
                            </tr>
                            @endif
                            @if($sr->user->nal_id)
                            <tr>
                                <td class="font-weight-bold">Nationality</td>
                                <td>{{$sr->user->nationality->name }}</td>
                            </tr>
                            @endif
                            @if($sr->user->state_id)
                            <tr>
                                <td class="font-weight-bold">County Of Origin</td>
                                <td>{{$sr->user->state->name }}</td>
                            </tr>
                            @endif
                            <!--@if($sr->user->lga_id)-->
                            <!--<tr>-->
                            <!--    <td class="font-weight-bold">Community</td>-->
                            <!--    <td>{{$sr->user->lga->name }}</td>-->
                            <!--</tr>-->
                            <!--@endif-->
                            <!--@if($sr->dorm_id)-->
                            <!--     <tr>-->
                            <!--        <td class="font-weight-bold">Dormitory</td>-->
                            <!--        <td>{{$sr->dorm->name.' '.$sr->dorm_room_no }}</td>-->
                            <!--    </tr> -->-->
                            <!--@endif-->

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


    {{--Student Profile Ends--}}

@endsection
