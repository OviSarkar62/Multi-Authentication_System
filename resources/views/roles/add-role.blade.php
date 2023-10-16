@extends('layouts.app')
@section('content')
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h1 class="mb-4 text-center">Add Roles</h1>
        </div>

        <div class="col-md-6 offset-md-3 mt-5 mb-5">
            <div class="card" id="card">
                <div class="card-header">Add Roles</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('store.roles') }}" id="roleForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Role Name</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">Permissions</label>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="checkPermissionAll" value="1">
                                <label class="form-check-label" for="checkPermissionAll">All</label>
                            </div>
                            <hr>
                            @php $i = 1; @endphp
                            @foreach ($permission_groups as $group)
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="{{ $i }}Management" value="{{ $group->name }}" onclick="checkPermissionByGroup('role-{{ $i }}-management-checkbox', this)">
                                        <label class="form-check-label" for="{{ $i }}Management">{{ $group->name }}</label>
                                    </div>
                                </div>

                                <div class="col-9 role-{{ $i }}-management-checkbox">
                                    @php
                                    $permissions = App\Models\Admin::getpermissionsByGroupName($group->name);
                                    $j = 1;
                                    @endphp
                                    @foreach ($permissions as $permission)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" id="checkPermission{{ $permission->id }}" value="{{ $permission->name }}">
                                        <label class="form-check-label" for="checkPermission{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                    @php $j++; @endphp
                                    @endforeach
                                    <br>
                                </div>
                            </div>
                            @php $i++; @endphp
                            @endforeach
                        </div>

                        <div class="form-group mb-5 mt-3">
                            <button type="submit" class="btn btn-info btn-block" id="btnRegister">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    /**
         * Check all the permissions
         */
         $("#checkPermissionAll").click(function(){
             if($(this).is(':checked')){
                 // check all the checkbox
                 $('input[type=checkbox]').prop('checked', true);
             }else{
                 // un check all the checkbox
                 $('input[type=checkbox]').prop('checked', false);
             }
         });

         function checkPermissionByGroup(className, checkThis){
            const groupIdName = $("#"+checkThis.id);
            const classCheckBox = $('.'+className+' input');

            if(groupIdName.is(':checked')){
                 classCheckBox.prop('checked', true);
             }else{
                 classCheckBox.prop('checked', false);
             }
            implementAllChecked();
         }

         function checkSinglePermission(groupClassName, groupID, countTotalPermission) {
            const classCheckbox = $('.'+groupClassName+ ' input');
            const groupIDCheckBox = $("#"+groupID);

            // if there is any occurance where something is not selected then make selected = false
            if($('.'+groupClassName+ ' input:checked').length == countTotalPermission){
                groupIDCheckBox.prop('checked', true);
            }else{
                groupIDCheckBox.prop('checked', false);
            }
            implementAllChecked();
         }

         function implementAllChecked() {
             const countPermissions = {{ count($all_permissions) }};
             const countPermissionGroups = {{ count($permission_groups) }};

            //  console.log((countPermissions + countPermissionGroups));
            //  console.log($('input[type="checkbox"]:checked').length);

             if($('input[type="checkbox"]:checked').length >= (countPermissions + countPermissionGroups)){
                $("#checkPermissionAll").prop('checked', true);
            }else{
                $("#checkPermissionAll").prop('checked', false);
            }
         }


</script>
@endsection