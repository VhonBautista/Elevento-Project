<table id="admins-table" class="d-none">
    <thead style="overflow-x: scroll !important;">
        <tr>
            <th class="user-data-event-profile">Profile</th>
            <th>Sex</th>
            <th>Role</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($adminsData as $adminData)
        <tr>
            <td>
            <div class="row justify-content-center user-data-event-profile">
                <div class="col-md-3 text-center">
                    <div style="width: 45px; height: 45px; overflow: hidden; border-radius: 50%; margin: 0 auto;">
                        <img src="@if ($adminData->profile_picture == null) {{ asset('asset/blank_profile.jpg') }} @else {{ asset($adminData->profile_picture) }} @endif" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>

                <div class="col-md-8 text-start">
                    <p class="m-0">{{ $adminData->username }}</p>
                    <p class="small text-muted m-0">{{ $adminData->email }}</p>
                </div>
            </div>
            </td>
            <td>{{ $adminData->campusEntity->sex }}</td>
            <td><p class="m-0 px-3 rounded-pill text-light text-center" 
            style="
            width: 100px;
            background-color:
            @switch($adminData->role)
                @case('Admin')
                @case('Co-Admin')
                    #3a6bdd
                    @break
                @case('Organizer')
                    #1bb835
                    @break
                @case('User')
                    #e09e11
                    @break

                @default
                    #aaa
            @endswitch;"
            >{{ $adminData->role }}</p></td>
            <td><p class="m-0 px-3 rounded-pill text-light text-center"
            style="
            width: 100px;
            background-color:
            @switch($adminData->campusEntity->type)
                @case('Employee')
                    #3a6bdd
                    @break
                @case('Student')
                    #e09e11
                    @break
            @endswitch;"
            >{{ $adminData->campusEntity->type }}</p></td>
            <td>
                <div class="nav-item dropdown">
                    <a class="btn btn-dark btn-sm dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Access Type
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <button class="dropdown-item mb-1 make-admin">Disable</button>
                        <button class="dropdown-item mb-1 make-admin">Delete</button>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>