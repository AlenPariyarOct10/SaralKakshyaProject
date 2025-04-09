<nav class="p-4 space-y-1">
    <a href="{{route("superadmin.index")}}" class="sidebar-item {{(Route::is('superadmin.index')?'active':'')}}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{route("superadmin.testimonial.index")}}" class="sidebar-item {{Route::is("admin.testimonial.index")? "active":""}}">
        <i class="fa-solid fa-star-half-stroke"></i>
        <span>Testimonial</span>
    </a>
    <a href="{{route("superadmin.admin-management")}}" class="sidebar-item {{(Route::is('superadmin.admin-management')?'active':'')}}">
        <i class="fas fa-user-shield"></i>
        <span>Admin Management</span>
    </a>
    <a href="institute-management.html" class="sidebar-item">
        <i class="fas fa-university"></i>
        <span>Institute Management</span>
    </a>
    <a href="reports.html" class="sidebar-item">
        <i class="fas fa-chart-bar"></i>
        <span>Reports & Analytics</span>
    </a>

    <a href="settings.html" class="sidebar-item">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
    </a>
    <a href="notifications.html" class="sidebar-item">
        <i class="fas fa-bell"></i>
        <span>Notifications</span>
        <span class="ml-auto inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">8</span>
    </a>

    <div class="pt-4 mt-4 border-t dark:border-gray-700">
        <a href="profile.html" class="sidebar-item">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
        <a href="index.html" class="sidebar-item text-red-500 dark:text-red-400">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</nav>
