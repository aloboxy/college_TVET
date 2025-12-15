<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
    <!-- Sidebar mobile toggler -->
    @if(Auth::user()->status == 1)
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="{{ route('my_account') }}">
                            <img
                                src="{{ Auth::user()->photo ?? Qs::getSetting('logo') }}"
                                onerror="this.onerror=null; this.src='{{ Qs::getSetting('logo') }}';"
                                width="38" height="38"
                                class="rounded-circle"
    alt="{{ Auth::user()->name ?? 'User' }}'s profile photo">

                        </a>
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">{{ Auth::user()->name }}</div>
                        <div class="font-size-xs opacity-50">
                            <i class="icon-user font-size-sm"></i> &nbsp;{{ ucwords(str_replace('_', ' ',
                            Auth::user()->user_type)) }}<br>
                            @if(Auth::user()->user_type == 'student')

                                        @php
                                            $student = \App\Models\StudentRecord::where('user_id', Auth::user()->id)->first();
                                        @endphp
                                    <span>
                                    Department: {{ $student->department->name ?? $student->real_department->name }}
                                    </span><br>
                                    <span>
                                    Major: {{ $student->major }}
                                    </span><br>
                                    @if(!$student->my_class)
                                    @else
                                    <span>
                                    Level: {{ $student->my_class->name }}
                                    </span><br>
                                    @endif
                                    @if(!$student->section)
                                    @else
                                    <span>
                                    Cohort: {{ $student->section->name }}
                                    </span><br>
                                    @endif
                            @endif
                        </div>

                    </div>

                </div>

            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ (Route::is('dashboard')) ? 'active' : '' }}">
                        <i class="icon-home4"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{--Academics--}}
                @if(Qs::userIsTeamSAT())
                <li
                    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['tt.index', 'ttr.edit', 'ttr.show', 'ttr.manage']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Academics</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">

                        {{--Timetables--}}
                        <li class="nav-item"><a href="{{ route('tt.index') }}"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['tt.index']) ? 'active' : '' }}">Timetables</a>
                        </li>
                        {{--Library--}}
                        @can('library.view')
                        <li class="nav-item"><a href="{{ route('library.index') }}"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['library.index', 'library.create', 'library.edit']) ? 'active' : '' }}">Library</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif

                {{--Administrative--}}
                @if(Qs::userIsTeamSA())
                <li
                    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.invoice', 'payments.receipts', 'payments.edit', 'payments.manage', 'payments.show',]) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="#" class="nav-link"><i class="icon-office"></i> <span> Administrative</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Administrative">

                        {{--Payments--}}
                        @can('users.view')
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}"
                                   class="nav-link {{ in_array(Route::currentRouteName(), ['users.index', 'users.show', 'users.edit']) ? 'active' : '' }}"><i
                                        class="icon-users4"></i> <span> Users</span></a>
                            </li>
                        @endcan

                        {{--Roles & Permissions--}}
                        @if(Auth::user()->can('permissions.manage') || Qs::userIsSuperAdmin())
                            <li class="nav-item">
                                <a href="{{ route('roles.index') }}"
                                   class="nav-link {{ in_array(Route::currentRouteName(), ['roles.index', 'roles.edit', 'roles.create']) ? 'active' : '' }}"><i
                                        class="icon-lock"></i> <span> Manage Roles</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('permissions.index') }}"
                                   class="nav-link {{ in_array(Route::currentRouteName(), ['permissions.index', 'permissions.edit', 'permissions.create']) ? 'active' : '' }}"><i
                                        class="icon-safe"></i> <span> Manage Permissions</span></a>
                            </li>
                        @endif

                        @can('payments.manage')
                        <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['pins.create', 'pins.index']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                            <a href="#" class="nav-link"> <span> Access</span></a>

                            <ul class="nav nav-group-sub" data-submenu-title="Manage Pins">
                                {{--Access--}}
                                    <li class="nav-item">
                                        <a href="{{ route('access.index') }}"
                                           class="nav-link {{ (Route::is('access.index')) ? 'active' : '' }}">Give Access</a>
                                    </li>

                                    <li class="nav-item">
                                        <a onclick="confirmDeleteAccess()" href="#"
                                           class="nav-link {{ (Route::is('access.delete')) ? 'active' : '' }}">Reset Access</a>
                                           <form method="post" id="item-delete-access" action="{{ route('access.delete') }}" class="hidden">@csrf @method('post')</form>
                                    </li>
                            </ul>


                        </li>
                        @endcan


                        {{--Payments--}}




                        {{--Fees--}}

                    </ul>
                </li>
                @endif

                {{-- @if(Qs::userIsAdministrative()) --}}
                @if(Auth::user()->can('payments.create') || Auth::user()->can('payments.manage'))
                <li
                class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.invoice', 'payments.receipts', 'payments.edit', 'payments.manage', 'payments.show',]) ? 'nav-item-expanded nav-item-open' : '' }} ">
                <a href="#" class="nav-link"><i class="icon-coins"></i> <span> BFO</span></a>

                <ul class="nav nav-group-sub" data-submenu-title="BFO">
                    <li
                    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.edit', 'payments.manage', 'payments.show', 'payments.invoice']) ? 'nav-item-expanded' : '' }}">

                    <a href="#"
                        class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.create', 'payments.manage', 'payments.show', 'payments.invoice','accounting.index_student_records']) ? 'active' : '' }}">Payments</a>

                    <ul class="nav nav-group-sub">
                    <li class="nav-item"><a href="{{ route('accounting.index_student_records') }}"
                                class="nav-link {{ Route::is('accounting.index_student_records') ? 'active' : '' }}">
                                Financial Record Report</a></li>
                        <li class="nav-item"><a href="{{ route('payments.create') }}"
                                class="nav-link {{ Route::is('payments.create') ? 'active' : '' }}">
                                Create Payment</a></li>
                        <li class="nav-item"><a href="{{ route('payments.index') }}"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.show']) ? 'active' : '' }}">
                                Manage Payments</a></li>
                        <li class="nav-item"><a href="{{ route('payments.manage') }}"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['payments.manage', 'payments.invoice', 'payments.receipts']) ? 'active' : '' }}">
                                Studen Payments</a></li>
                        <li class="nav-item"><a href="{{ route('payments.bill') }}"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['payments.bill']) ? 'active' : '' }}">Bill
                                Students Per Class & Year/Semester</a></li>
                    </ul>

                </li>

                </ul>
                </li>
                @endif

                {{--Manage Payments--}}



                {{--College Management--}}
                @if(Auth::user()->can('college.manage') || Auth::user()->can('department.manage') || Auth::user()->can('major.manage') || Auth::user()->can('minor.manage'))
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['major.index','departments.index', 'departments.edit', 'college.index', 'college.edit','major.edit','major.index','minor.edit','minor.index']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                        <a href="#" class="nav-link"><i class="icon-office"></i> <span> College Management</span></a>

                        <ul class="nav nav-group-sub" data-submenu-title="College Management">
                            @can('department.manage')
                                <li class="nav-item">
                                    <a href="{{ route('departments.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['departments.index','departments.edit']) ? 'active' : '' }}"><i
                                            class="icon-windows"></i> <span> Departments</span></a>
                                </li>
                            @endcan

                            @can('college.manage')
                                <li class="nav-item">
                                    <a href="{{ route('college.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['college.index','college.edit']) ? 'active' : '' }}"><i
                                            class="icon-cogs"></i> <span> College</span></a>
                                </li>
                            @endcan

                            @can('major.manage')
                                <li class="nav-item">
                                    <a href="{{ route('major.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['major.index','major.edit']) ? 'active' : '' }}"><i
                                            class="icon-pencil"></i> <span> Major</span></a>
                                </li>
                            @endcan

                            @can('minor.manage')
                                <li class="nav-item">
                                    <a href="{{ route('minor.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['minor.index','minor.edit']) ? 'active' : '' }}"><i
                                            class="icon-book"></i> <span> Minor</span></a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                {{--Manage Students--}}
                @if(Auth::user()->can('students.create') || Auth::user()->can('students.view') || Auth::user()->can('students.promote') || Auth::user()->can('students.graduate'))
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['courselist.planned_course_list','students.create', 'students.list', 'students.edit', 'students.show', 'students.promotion', 'students.promotion_manage', 'students.graduated','bulk.transfer.select.list']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                        <a href="#" class="nav-link"><i class="icon-users"></i> <span> Students Management</span></a>

                        <ul class="nav nav-group-sub" data-submenu-title="Manage Students">
                            {{--Admit Student--}}
                            @can('students.create')
                                <li class="nav-item">
                                    <a href="{{ route('students.create') }}"
                                       class="nav-link {{ (Route::is('students.create')) ? 'active' : '' }}">Admit Student</a>
                                </li>
                            @endcan

                            {{--Student Information--}}
                            @can('students.view')
                                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.list', 'students.edit', 'students.show']) ? 'nav-item-expanded nav-item-open' : '' }}">
                                    <a href="#" class="nav-link {{ Route::is('students.list') ? 'active' : '' }}">General Student</a>
                                    <ul class="nav nav-group-sub">
                                        <li class="nav-item">
                                            <a href="{{ route('students.list')}}" class="nav-link {{ Route::is('students.list') ? 'active' : '' }}">Student List</a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.group', 'students.edit', 'students.show']) ? 'nav-item-expanded' : '' }}">
                                    <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['students.group', 'students.edit', 'students.show']) ? 'active' : '' }}">Student Information By class</a>
                                    <ul class="nav nav-group-sub">
                                        @foreach(App\Models\MyClass::orderBy('name')->get() as $c)
                                            <li class="nav-item"><a href="{{ route('students.group', $c->id) }}" class="nav-link ">{{ $c->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endcan

                        <li>
                            <a style="color:bisque" href="{{ route('bulk.transfer.select.list')}}" class="nav-link {{ Route::is('bulk.transfer.select.list') ? 'active' : '' }}">Bulk Student Transfer</a>
                        </li>
                        @if(Qs::userIsTeamSA())
                        <li
                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.group', 'students.edit', 'students.show']) ? 'nav-item-expanded' : '' }}">
                            <a href="#"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['students.group', 'students.edit', 'students.show']) ? 'active' : '' }}">Student
                                Information By class</a>
                            <ul class="nav nav-group-sub">
                                @foreach(App\Models\MyClass::orderBy('name')->get() as $c)
                                <li class="nav-item"><a href="{{ route('students.group', $c->id) }}"
                                        class="nav-link ">{{ $c->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @endif



                            @can('students.promote')
                                {{--Student Promotion--}}
                                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.promotion', 'students.promotion_manage']) ? 'nav-item-expanded' : '' }}">
                                    <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion', 'students.promotion_manage' ]) ? 'active' : '' }}">Student Promotion</a>
                                    <ul class="nav nav-group-sub">
                                        <li class="nav-item"><a href="{{ route('students.promotion') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion']) ? 'active' : '' }}">Promote Students</a></li>
                                        <li class="nav-item"><a href="{{ route('students.promotion_manage') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion_manage']) ? 'active' : '' }}">Manage Promotions</a></li>
                                    </ul>
                                </li>
                            @endcan

                            @can('students.graduate')
                                {{--Student Graduated--}}
                                <li class="nav-item"><a href="{{ route('students.graduated') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.graduated' ]) ? 'active' : '' }}">Students Graduated</a></li>
                                <li class="nav-item">
                                    <a href="{{ route('courselist.planned_course_list') }}" class="nav-link {{ (Route::is('courselist.planned_course_list')) ? 'active' : '' }}">Find Inactive Students</a>
                                </li>
                            @endcan

                    </ul>
                </li>
                @endif

                {{--Manage Classes--}}
                @can('academics.manage')
                    <li class="nav-item">
                        <a href="{{ route('classes.index') }}"
                           class="nav-link {{ in_array(Route::currentRouteName(), ['classes.index','classes.edit']) ? 'active' : '' }}"><i
                                class="icon-windows2"></i> <span> Classes</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('sections.index') }}"
                           class="nav-link {{ in_array(Route::currentRouteName(), ['sections.index','sections.edit',]) ? 'active' : '' }}"><i
                                class="icon-fence"></i> <span>Cohorts</span></a>
                    </li>
                @endcan

                {{--Manage Subjects--}}
                @can('subjects.manage')
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['courselist.index','showEnrolled','addstudent','subjects.edit','subjects.index','courses.create','courses.index']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                        <a href="#" class="nav-link"><i class="icon-pin"></i>  <span>Course Management</span></a>

                        <ul class="nav nav-group-sub" data-submenu-title="Course Planning">
                            <li class="nav-item">
                                <a href="{{ route('subjects.index') }}"
                                   class="nav-link {{ in_array(Route::currentRouteName(), ['subjects.index','subjects.edit',]) ? 'active' : '' }}"><span>Create Course Per Department</span></a>
                            </li>
                            {{--Generate Pins--}}
                            <li class="nav-item">
                                <a href="{{ route('courses.create') }}"
                                   class="nav-link {{ (Route::is('courses.create')) ? 'active' : '' }}">Create Course</a>
                            </li>

                            {{--    Valid/Invalid Pins  --}}
                            <li class="nav-item">
                                <a href="{{ route('courses.index') }}"
                                   class="nav-link {{ (Route::is('courses.index')) ? 'active' : '' }}">Course Listing</a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ route('teacheradd.index') }}"
                                   class="nav-link {{ (Route::is('teacheradd.index')) ? 'active' : '' }}">Student Course Enrollment</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('showEnrolled') }}"
                                   class="nav-link {{ (Route::is('showEnrolled')) ? 'active' : '' }}">Student Course</a>
                            </li>
                        </ul>
                    </li>
                @endcan




                {{-- Resit --}}



                {{--Exam--}}
                @if(Auth::user()->can('exams.view') || Auth::user()->can('marks.view') || Auth::user()->can('marks.create'))
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['resit.selector','resits','pass.failed','exams.index', 'exams.edit', 'grades.index', 'grades.edit', 'marks.index', 'marks.downloadindex','marks.manage', 'marks.bulk', 'marks.Pass', 'marks.show', 'marks.batch_fix','marks.download','repeat.index','repeat.selector','duplicate.index']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                        <a href="#" class="nav-link"><i class="icon-books"></i> <span> Exams/Grades Management</span></a>

                        <ul class="nav nav-group-sub" data-submenu-title="Exams/Grades Management">

                            {{--Exam list--}}
                            @can('exams.view')
                                <li class="nav-item">
                                    <a href="{{ route('exams.index') }}"
                                       class="nav-link {{ (Route::is('exams.index')) ? 'active' : '' }}">Exam List</a>
                                </li>
                            @endcan

                            @can('grades.manage')
                                <li class="nav-item">
                                    <a href="{{ route('grades.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['grades.index', 'grades.edit']) ? 'active' : '' }}">Grades Structure</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('duplicate.index') }}" class="nav-link {{ (Route::is('duplicate.index')) ? 'active' : '' }}">Clean Duplicates Grades</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('repeat.index') }}" class="nav-link {{ (Route::is('repeat.index')) ? 'active' : '' }}">Get Repeat List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('pass.failed') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['pass.failed']) ? 'active' : '' }}">Passed or Failed List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('marks.batch_fix') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.batch_fix']) ? 'active' : '' }}">Batch Fix</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('resit.selector') }}" class="nav-link {{ in_array(Route::currentRouteName(),['resit.selector']) ? 'active' : ''}}"><span>Resit List</span></a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('resits') }}" class="nav-link {{ in_array(Route::currentRouteName(),['resits']) ? 'active' : ''}}"><span>Resit Grade Entry</span></a>
                                </li>
                            @endcan

                            {{--Marks Manage--}}
                            @can('marks.create')
                                <li class="nav-item">
                                    <a href="{{ route('marks.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['marks.index']) ? 'active' : '' }}">Grades Entry</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('marks.downloadindex') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['marks.downloadindex']) ? 'active' : '' }}">Grade Roster</a>
                                </li>
                            @endcan

                            {{--Marksheet--}}
                            @can('marksheet.view')
                                <li class="nav-item">
                                    <a href="{{ route('marks.bulk') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['marks.bulk', 'marks.show']) ? 'active' : '' }}">Gradesheet</a>
                                </li>
                            @endcan

                            @if(Auth::user()->can('mark.request_change') || Auth::user()->can('mark.approve_dept') || Auth::user()->can('mark.approve_college'))
                                <li class="nav-item">
                                    <a href="{{ route('grade_requests.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['grade_requests.index']) ? 'active' : '' }}">Grade Change Requests</a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif


                {{--End Exam--}}

                @include('pages.'.Qs::getUserType().'.menu')

                {{--Manage Account--}}
                <li class="nav-item">
                    <a href="{{ route('my_account') }}"
                        class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}"><i
                            class="icon-user"></i> <span>My Account</span></a>
                </li>

                @if(Qs::userIsStudent())
                    <li class="nav-item">
                        <a href="{{ route('library.index') }}"
                           class="nav-link {{ in_array(Route::currentRouteName(), ['library.index']) ? 'active' : '' }}"><i
                                class="icon-books"></i> <span>Library</span></a>
                    </li>
                @endif
                {{--For Staff--}}
                @if(Qs::userIsTeamSAT())
                    <li class="nav-item">
                        <a href="{{ route('users.show', Auth::user()->id) }}"
                           class="nav-link {{ in_array(Route::currentRouteName(), ['users.show']) ? 'active' : '' }}"><i
                                class="icon-user"></i> <span>My Profile</span></a>
                    </li>
                @endif

            </ul>
        </div>
    </div>
    @else
    <h1 class="text-danger text-Center">Your Account is Deactivated please visit the School Admin</h1>
    @endif
</div>
