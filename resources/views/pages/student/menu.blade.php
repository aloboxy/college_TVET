{{--Marksheet--}}
<div class="sidebar sidebar-dark bg-success sidebar-main sidebar-expand-md">
 @if(Auth::user()->status == 1)
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
          <span>{{ Auth::user()->name }}</span>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();" class="dropdown-item"><i class="icon-switch2"></i></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
            <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
            
        </a>
        
        
    </div>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<li class="nav-item">
    <a href="{{ route('students.show', Qs::hash(Auth::user()->student_record->id)) }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.show']) ? 'active' : '' }}"><i class="icon-user"></i> My Profile</a>
    <a href="{{ route('marks.year_select', Qs::hash(Auth::user()->id)) }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.show', 'marks.year_selector', 'pins.enter']) ? 'active' : '' }}"><i class="icon-book"></i> Gradesheet</a>
    <a href="{{ route('students.ledger', Qs::hash(Auth::user()->id)) }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.ledger']) ? 'active' : '' }}"><i class="icon-books"></i> Grade Sheet Ledger</a>
    <a href="{{ route('payments.studentfess', Qs::hash(Auth::user()->id)) }}" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.manage', 'payments.studentfess', 'payments.receipts']) ? 'active' : '' }}"><i class="icon-pencil"></i> Student Fees Record</a>
</li>

<li class="nav-item">
    <a href="{{ route('planning.view') }}" class="nav-link {{ (Route::is('planning.view')) ? 'active' : '' }}">
        <i class="icon-pen"></i>
        <span>Plan Course</span>
    </a>
</li>

<li class="nav-item">
    <a href="{{(Auth::user()->student_record->department->whatsapplink) ?? ''}} " target="_blank"><i class="fa fa-whatsapp" style="font-size:24px;color:#25d366"></i> WhatsApp chatroom</a>
</li>

</div>



@else
    <h1 class="text-danger text-Center">Your Account is Deactivated please visit the School Admin</h1>
@endif
