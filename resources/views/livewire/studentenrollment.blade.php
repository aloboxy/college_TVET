@extends('layouts.master')
@section('page_title', 'Student Information - '.$my_class->name)
@section('content')
<div class="tab-content">
    <div class="tab-pane fade show active" id="all-students">
        <table class="table datatable-button-html5-columns">
            <thead>
            <tr>
                <th>S/N</th>
                <th>Name</th>
            </tr>
            </thead>
            <tbody>
            @foreach($students as $s)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $s->user->name }}</td>

                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endsection
