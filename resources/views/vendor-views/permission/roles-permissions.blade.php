@extends('vendor-views.layouts.dashboard-main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Role and Permission Management</h3>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Assign Roles to Users -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title mb-2 text-white">Assign Roles to Users</h4>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.administration.assign.role.to.user') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="user">Select User</label>
                                            <select class="form-control" id="user" name="user_id" required>
                                                <option value="">-- Select User --</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->f_name }} {{ $user->l_name }} ({{ $user->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Select Role</label>
                                            <select class="form-control" id="role" name="role_id" required>
                                                <option value="">-- Select Role --</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Assign Role</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Assign Permissions to Roles -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <h4 class="card-title mb-2 text-white">Assign Permissions to Roles</h4>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.administration.assign.permission.to.role') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="roleForPermission">Select Role</label>
                                            <select class="form-control" id="roleForPermission" name="role_id" required>
                                                <option value="">-- Select Role --</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Select Permissions</label>
                                            <div class="permission-checkboxes" style="max-height: 200px; overflow-y: auto;">
                                                @foreach($permissions as $permission)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="permissions[]" 
                                                               value="{{ $permission->id }}" 
                                                               id="permission_{{ $permission->id }}">
                                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success">Assign Permissions</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Assignments Table -->
                    <div class="card mt-4">
                        <div class="card-header bg-info text-white">
                            <h4 class="card-title mb-2 text-white">Current Assignments</h4>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="users-tab" data-bs-toggle="tab" href="#users" role="tab">Users with Roles</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="roles-tab" data-bs-toggle="tab" href="#roles" role="tab">Roles with Permissions</a>
                                </li>
                            </ul>
                            
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="users" role="tabpanel">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Email</th>
                                                <th>Roles</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $user)
                                            {{-- @dd($user->getRoleNames()) --}}
                                                <tr>
                                                    <td>{{ $user->f_name }} {{ $user->l_name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        @foreach($user->getRoleNames() as $role)
                                                            {{-- @dd($role) --}}
                                                            <i class="badge rounded-pill bg-success item-name">{{__($role) }}</i>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        {{-- <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a> --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="roles" role="tabpanel">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th>Role</th>
                                                <th>Permissions</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @dd($roles[1]->permissions); --}}
                                            @foreach($roles as $role)
                                                <tr>
                                                    <td>{{ $role->name }}</td>
                                                    <td>
                                                        <div class="d-flex flex-wrap">

                                                            @foreach($role->permissions as $permission)
                                                                <div class="badge bg-success me-1">{{ $permission->name }}</div>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{-- <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-info">Edit</a> --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
    $(document).ready(function() {
        // Initialize Bootstrap tab functionality
        $('#myTab a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        
        // 
        const roles = @json($roles);
        // Optional: Enhance user experience with select2
        $('#user, #role').select2({
            placeholder: "Select an option",
            allowClear: true
        });

        const rolePermissions = @json(
            $roles->mapWithKeys(function($role) {
                return [$role->id => $role->permissions->pluck('id')];
            })
        );

        $('#roleForPermission').select2({
            placeholder: "Select an option",
            allowClear: true
        }).on('change', function () {
            let selectedRoleId = $(this).val();
            // console.log(selectedRoleId);

            $('input[name="permissions[]"]').prop('checked', false); // Uncheck all

            if (selectedRoleId && rolePermissions[selectedRoleId]) {
                rolePermissions[selectedRoleId].forEach(function (permissionId) {
                    $('#permission_' + permissionId).prop('checked', true);
                });
            }
        });


        console.log(roles);
    });
</script>    
@endpush

