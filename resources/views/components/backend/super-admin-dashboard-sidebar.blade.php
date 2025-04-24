<nav class="p-4 space-y-1">
    <a href="{{route("superadmin.index")}}" class="sidebar-item {{(Route::is('superadmin.index')?'active':'')}}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{route("superadmin.testimonial.index")}}" class="sidebar-item {{Route::is("superadmin.testimonial.index")? "active":""}}">
        <i class="fa-solid fa-star-half-stroke"></i>
        <span>Testimonial</span>
    </a>
    <a href="{{route("superadmin.admin-management")}}" class="sidebar-item {{(Route::is('superadmin.admin-management')?'active':'')}}">
        <i class="fas fa-user-shield"></i>
        <span>Admin Management</span>
    </a>
    <a href="{{route("superadmin.institute.index")}}" class="sidebar-item {{(Route::is('superadmin.institute.index')?'active':'')}}">
        <i class="fas fa-university"></i>
        <span>Institute Management</span>
    </a>
    <a href="reports.html" class="sidebar-item">
        <i class="fas fa-chart-bar"></i>
        <span>Reports & Analytics</span>
    </a>

    <a href="{{route("superadmin.setting.index")}}" class="sidebar-item {{(Route::is('superadmin.setting.index')?'active':'')}}">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
    </a>
    <div class="pt-4 mt-4 border-t dark:border-gray-700">
        <a href="{{route("superadmin.profile.index")}}" class="sidebar-item {{(Route::is('superadmin.profile.index')?'active':'')}}">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
        <a href="index.html" class="sidebar-item text-red-500 dark:text-red-400">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</nav>
