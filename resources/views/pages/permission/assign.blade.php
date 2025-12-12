@extends('layouts.master')
@section('page_title', 'Manage Permissions')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Assign Permissions to {{ $user->name }}</h5>
        {!! Qs::getPanelOptions() !!}
    </div>
    
    <div class="container">


    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('permissions.assign', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            @foreach($groupedPermissions as $category => $permissions)
            <div class="form-group">
                <h4 style="text-align: center; color:blueviolet">{{ strtoupper($category) }} {{ strtoupper('Permission Access') }}</h4>
                <div class="form-check">
                    <input type="checkbox" class="select-all" data-category="{{ $category }}">
                    <label><strong>Select All</strong></label>
                </div>

                <div class="row">
                    @foreach($permissions->chunk(ceil($permissions->count() / 2)) as $chunk) <!-- Split into 2 columns -->
                        <div class="col-md-6"> <!-- Column start -->
                            @foreach($chunk as $permission)
                                <div class="form-check">
                                    <input type="checkbox" class="permission-checkbox" name="permissions[]" value="{{ $permission->id }}"
                                           {{ $user->permissions->contains($permission->id) ? 'checked' : '' }} data-category="{{ $category }}">
                                    <label>{{ $permission->description }}</label>
                                </div>
                            @endforeach
                        </div> <!-- Column end -->
                    @endforeach
                </div>
            </div>
        @endforeach

        </div>
        <button type="submit" class="btn btn-primary" >Save Permissions</button>
    </form>
</div>
</div>
   {{--Class List Ends--}}
<script>
    // Select All checkbox functionality
    document.querySelectorAll('.select-all').forEach(function(selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const category = this.getAttribute('data-category');
            const checkboxes = document.querySelectorAll(`.permission-checkbox[data-category="${category}"]`);
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    });
</script>
@endsection
