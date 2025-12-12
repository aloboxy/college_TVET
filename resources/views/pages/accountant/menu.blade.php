<li
class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.invoice', 'payments.receipts', 'payments.edit', 'payments.manage', 'payments.show',]) ? 'nav-item-expanded nav-item-open' : '' }} ">
<a href="#" class="nav-link"><i class="icon-coins"></i> <span> BFO</span></a>

<ul class="nav nav-group-sub" data-submenu-title="BFO">
    <li
    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.edit', 'payments.manage', 'payments.show', 'payments.invoice','accounting.index_student_records']) ? 'nav-item-expanded' : '' }}">

    <a href="#"
        class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.create', 'payments.manage', 'payments.show', 'payments.invoice','accounting.index_student_records']) ? 'active' : '' }}">Payments</a>
        
        

    <ul class="nav nav-group-sub">
          <li class="nav-item"><a href="{{ route('accounting.index_student_records') }}"
                                class="nav-link {{ Route::is('accounting.index_student_records') ? 'active' : '' }}">
                                Financial Record Report</a></li>
        <li class="nav-item"><a href="{{ route('payments.create') }}"
                class="nav-link {{ Route::is('payments.create') ? 'active' : '' }}">Create
                Payment</a></li>
        <li class="nav-item"><a href="{{ route('payments.index') }}"
                class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.show']) ? 'active' : '' }}">Manage
                Payments</a></li>
        <li class="nav-item"><a href="{{ route('payments.manage') }}"
                class="nav-link {{ in_array(Route::currentRouteName(), ['payments.manage', 'payments.invoice', 'payments.receipts']) ? 'active' : '' }}">Student
                Payments</a></li>
        <li class="nav-item"><a href="{{ route('payments.bill') }}"
                class="nav-link {{ in_array(Route::currentRouteName(), ['payments.bill']) ? 'active' : '' }}">Bill
                Students Per Class & Year/Semester</a></li>
    </ul>

</li>

</ul>
</li>

