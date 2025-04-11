<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaralKakshya - Smart Classroom Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{asset('js/swiper.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/swiper.css')}}">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        .gradient-bg {
            background: linear-gradient(90deg, #0ea5e9 0%, #14b8a6 100%);
        }

        .hero-pattern {
            background-color: #f8fafc;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%230ea5e9' fill-opacity='0.05'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z'/%3E%3Cpath d='M6 5V0H5v5H0v1h5v94h1V6h94V5H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="font-sans text-gray-800">
<!-- Navigation -->
<nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <i class="fas fa-graduation-cap text-primary-600 text-2xl mr-2"></i>
                    <span class="font-bold text-xl text-primary-700">SaralKakshya</span>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="#features" class="border-transparent text-gray-500 hover:border-primary-500 hover:text-primary-600 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Features
                    </a>
                    <a href="#how-it-works" class="border-transparent text-gray-500 hover:border-primary-500 hover:text-primary-600 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        How It Works
                    </a>
                    <a href="#testimonials" class="border-transparent text-gray-500 hover:border-primary-500 hover:text-primary-600 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Testimonials
                    </a>
                    <a href="#contact" class="border-transparent text-gray-500 hover:border-primary-500 hover:text-primary-600 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Contact
                    </a>
                </div>
            </div>
            @if(!Auth::user())
                <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                    <a href="{{route('student.login')}}" class="inline-flex items-center px-4 py-2 border border-primary-500 text-sm font-medium rounded-md text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-user-graduate mr-2"></i> Student Login
                    </a>
                    <a href="{{route('teacher.login')}}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-chalkboard-teacher mr-2"></i> Teacher Login
                    </a>
                </div>
            @endif

            @if (Auth::guard('admin')->check())
                <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                    <a href="{{route('admin.logout')}}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fa fa-sign-out mr-2" aria-hidden="true"></i>Admin Logout
                    </a>
                </div>
            @elseif (Auth::guard('teacher')->check())
                <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                    <a href="{{route('teacher.logout')}}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fa fa-sign-out mr-2" aria-hidden="true"></i>Teacher Logout
                    </a>
                </div>
            @elseif (Auth::guard('student')->check())
                <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                    <a href="{{route('student.logout')}}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fa fa-sign-out mr-2" aria-hidden="true"></i>Student Logout
                    </a>
                </div>
            @elseif (Auth::guard('super_admin')->check())
                <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                    <a href="{{route('superadmin.logout')}}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fa fa-sign-out mr-2" aria-hidden="true"></i>SuperAdmin Logout
                    </a>
                </div>
            @endif


            <div class="-mr-2 flex items-center sm:hidden">
                <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500">
                    <span class="sr-only">Open main menu</span>
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="sm:hidden hidden" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <a href="#features" class="text-gray-600 hover:bg-primary-50 hover:text-primary-600 block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium">
                Features
            </a>
            <a href="#how-it-works" class="text-gray-600 hover:bg-primary-50 hover:text-primary-600 block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium">
                How It Works
            </a>
            <a href="#testimonials" class="text-gray-600 hover:bg-primary-50 hover:text-primary-600 block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium">
                Testimonials
            </a>
            <a href="#contact" class="text-gray-600 hover:bg-primary-50 hover:text-primary-600 block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium">
                Contact
            </a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="flex items-center px-4 space-x-3">
                <a href="{{route('student.login')}}" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-primary-500 text-sm font-medium rounded-md text-primary-600 bg-white hover:bg-primary-50">
                    <i class="fas fa-user-graduate mr-2"></i> Student Login
                </a>
                <a href="{{route('teacher.login')}}" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                    <i class="fas fa-chalkboard-teacher mr-2"></i> Teacher Login
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left">
                <h1>
                        <span class="block text-sm font-semibold uppercase tracking-wide text-primary-600">
                            Smart Classroom Management
                        </span>
                    <span class="mt-1 block text-4xl tracking-tight font-extrabold sm:text-5xl xl:text-6xl">
                            <span class="block text-gray-900">Transform Your</span>
                            <span class="block text-primary-600">Classroom Experience</span>
                        </span>
                </h1>
                <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-xl lg:text-lg xl:text-xl">
                    SaralKakshya makes classroom management simple and effective. Engage students, track progress, and streamline administrative tasks—all in one platform.
                </p>
                <div class="mt-8 sm:max-w-lg sm:mx-auto sm:text-center lg:text-left lg:mx-0">
                    <p class="text-base font-medium text-gray-900">
                        Get started today. Choose your role:
                    </p>
                    <div class="mt-5 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="{{route('student.login')}}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 md:py-4 md:text-lg md:px-10">
                                <i class="fas fa-user-graduate mr-2"></i> Student Login
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="{{route('teacher.login')}}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200 md:py-4 md:text-lg md:px-10">
                                <i class="fas fa-chalkboard-teacher mr-2"></i> Teacher Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-12 relative sm:max-w-lg sm:mx-auto lg:mt-0 lg:max-w-none lg:mx-0 lg:col-span-6 lg:flex lg:items-center">
                <div class="relative mx-auto w-full rounded-lg shadow-lg lg:max-w-md">
                    <div class="relative block w-full bg-white rounded-lg overflow-hidden">
                        <img class="w-full" src="{{asset("assets/images/logo-square.png")}}" alt="Dashboard preview">
                        <div class="absolute inset-0 w-full h-full flex items-center justify-center">
                            <button type="button" class="flex items-center justify-center h-16 w-16 rounded-full bg-white text-primary-600 shadow-lg hover:text-primary-500 focus:outline-none">
                                <i class="fas fa-play text-xl"></i>
                                <span class="sr-only">Play video</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-4">
            <div class="bg-primary-50 overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        Schools Using SaralKakshya
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-primary-600">
                        500+
                    </dd>
                </div>
            </div>
            <div class="bg-primary-50 overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        Active Students
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-primary-600">
                        50,000+
                    </dd>
                </div>
            </div>
            <div class="bg-primary-50 overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        Teachers Empowered
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-primary-600">
                        5,000+
                    </dd>
                </div>
            </div>
            <div class="bg-primary-50 overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        Assignments Completed
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-primary-600">
                        1M+
                    </dd>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div id="features" class="py-16 bg-gray-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base font-semibold text-primary-600 tracking-wide uppercase">Features</h2>
            <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight">
                Everything you need for modern education
            </p>
            <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">
                SaralKakshya provides a comprehensive suite of tools designed for today's educational needs.
            </p>
        </div>

        <div class="mt-16">
            <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                <div class="relative">
                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white">
                        <i class="fas fa-video"></i>
                    </div>
                    <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Virtual Classrooms</p>
                    <div class="mt-2 ml-16 text-base text-gray-500">
                        Conduct live classes with interactive whiteboards, screen sharing, and breakout rooms for group activities.
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Assignment Management</p>
                    <div class="mt-2 ml-16 text-base text-gray-500">
                        Create, distribute, and grade assignments digitally. Set deadlines and provide feedback efficiently.
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Progress Tracking</p>
                    <div class="mt-2 ml-16 text-base text-gray-500">
                        Monitor student performance with detailed analytics and generate comprehensive reports.
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white">
                        <i class="fas fa-comments"></i>
                    </div>
                    <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Communication Tools</p>
                    <div class="mt-2 ml-16 text-base text-gray-500">
                        Built-in messaging, announcements, and discussion forums for seamless communication.
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Scheduling & Attendance</p>
                    <div class="mt-2 ml-16 text-base text-gray-500">
                        Manage class schedules, track attendance, and send automated notifications for absences.
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Mobile Accessibility</p>
                    <div class="mt-2 ml-16 text-base text-gray-500">
                        Access all features on-the-go with our mobile-responsive design and dedicated apps.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<div id="how-it-works" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base font-semibold text-primary-600 tracking-wide uppercase">How It Works</h2>
            <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight">
                Simple for everyone
            </p>
            <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">
                SaralKakshya is designed to be intuitive for all users, whether you're a teacher, student, or administrator.
            </p>
        </div>

        <div class="mt-16">
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                <div>
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white mx-auto">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="mt-5 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">For Teachers</h3>
                        <ul class="mt-4 text-base text-gray-500 space-y-3">
                            <li class="flex items-start">
                                    <span class="h-6 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    </span>
                                Create and manage virtual classrooms
                            </li>
                            <li class="flex items-start">
                                    <span class="h-6 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    </span>
                                Assign and grade homework digitally
                            </li>
                            <li class="flex items-start">
                                    <span class="h-6 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    </span>
                                Track student attendance and performance
                            </li>
                            <li class="flex items-start">
                                    <span class="h-6 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    </span>
                                Communicate with students and parents
                            </li>
                        </ul>
                        <div class="mt-8">
                            <a href="{{route('teacher.login')}}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700">
                                <i class="fas fa-sign-in-alt mr-2"></i> Teacher Login
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-10 lg:mt-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white mx-auto">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="mt-5 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">For Students</h3>
                        <ul class="mt-4 text-base text-gray-500 space-y-3">
                            <li class="flex items-start">
                                    <span class="h-6 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    </span>
                                Attend virtual classes from anywhere
                            </li>
                            <li class="flex items-start">
                                    <span class="h-6 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    </span>
                                Submit assignments and receive feedback
                            </li>
                            <li class="flex items-start">
                                    <span class="h-6 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    </span>
                                Access study materials and resources
                            </li>
                            <li class="flex items-start">
                                    <span class="h-6 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    </span>
                                Track your own progress and grades
                            </li>
                        </ul>
                        <div class="mt-8">
                            <a href="{{route('student.login')}}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700">
                                <i class="fas fa-sign-in-alt mr-2"></i> Student Login
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mt-10 lg:mt-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white mx-auto">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="mt-5 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">For Administrators</h3>
                        <ul class="mt-4 text-base text-gray-500 space-y-3">
                            <li class="flex items-start">
                                    <span class="h-6 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    </span>
                                Manage school-wide implementation
                            </li>
                            <li class="flex items-start">
                                    <span class="h-6 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    </span>
                                Monitor teacher and student activities
                            </li>
                            <li class="flex items-start">
                                    <span class="h-6 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    </span>
                                Generate comprehensive reports
                            </li>
                            <li class="flex items-start">
                                    <span class="h-6 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    </span>
                                Customize system to school needs
                            </li>
                        </ul>
                        <div class="mt-8">
                            <a href="{{route('admin.login')}}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700">
                                <i class="fas fa-sign-in-alt mr-2"></i> Admin Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Testimonials Section -->
<div id="testimonials" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base font-semibold text-primary-600 tracking-wide uppercase">Testimonials</h2>
            <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight">
                Trusted by educators nationwide
            </p>
            <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">
                See what teachers, students, and administrators are saying about SaralKakshya.
            </p>
        </div>
        <div class="mt-12 ">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper mb-5">
                    @forelse($testimonial as $row)
                        <!-- Each Testimonial is a Slide -->
                        <div class="swiper-slide flex justify-center">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden p-8 w-full max-w-sm min-h-[240px] flex flex-col">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <img class="h-12 w-12 rounded-full" src="{{ asset('storage/'.$row->profile_picture) }}" alt="Teacher avatar">
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-bold text-gray-900">{{ $row->user_name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $row->designation }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex-grow">
                                    <p class="text-gray-600 line-clamp-5">"{{ $row->description }}"</p>
                                </div>
                                <div class="mt-4 flex text-yellow-400">
                                    @for($i = 0; $i < $row->stars ; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">No testimonials available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="gradient-bg">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
            <span class="block">Ready to transform your classroom?</span>
            <span class="block text-secondary-200">Start using SaralKakshya today.</span>
        </h2>
        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
            <div class="inline-flex rounded-md shadow">
                <a href="signup.html" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-primary-600 bg-white hover:bg-gray-50">
                    Get started
                </a>
            </div>
            <div class="ml-3 inline-flex rounded-md shadow">
                <a href="#contact" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-800 hover:bg-primary-900">
                    Contact us
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Contact Section -->
<div id="contact" class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base font-semibold text-primary-600 tracking-wide uppercase">Contact Us</h2>
            <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight">
                Get in touch
            </p>
            <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">
                Have questions? Our team is here to help you get started with SaralKakshya.
            </p>
        </div>

        <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-2">
            <div>
                <form class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full name</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" class="py-3 px-4 block w-full shadow-sm focus:ring-primary-500 focus:border-primary-500 border-gray-300 rounded-md" placeholder="Your name">
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1">
                            <input type="email" name="email" id="email" class="py-3 px-4 block w-full shadow-sm focus:ring-primary-500 focus:border-primary-500 border-gray-300 rounded-md" placeholder="your@email.com">
                        </div>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <div class="mt-1">
                            <input type="tel" name="phone" id="phone" class="py-3 px-4 block w-full shadow-sm focus:ring-primary-500 focus:border-primary-500 border-gray-300 rounded-md" placeholder="Phone number">
                        </div>
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <div class="mt-1">
                            <textarea id="message" name="message" rows="4" class="py-3 px-4 block w-full shadow-sm focus:ring-primary-500 focus:border-primary-500 border-gray-300 rounded-md" placeholder="How can we help you?"></textarea>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Send message
                        </button>
                    </div>
                </form>
            </div>
            <div class="bg-gray-50 rounded-lg p-8">
                <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                <p class="mt-2 text-base text-gray-500">
                    Our support team is available Monday through Friday, 9am to 5pm.
                </p>
                <dl class="mt-8 space-y-6">
                    <dt><span class="sr-only">Phone number</span></dt>
                    <dd class="flex text-base text-gray-500">
                        <i class="fas fa-phone-alt flex-shrink-0 h-6 w-6 text-primary-600"></i>
                        <span class="ml-3">+977 9816699413</span>
                    </dd>
                    <dt><span class="sr-only">Email</span></dt>
                    <dd class="flex text-base text-gray-500">
                        <i class="fas fa-envelope flex-shrink-0 h-6 w-6 text-primary-600"></i>
                        <span class="ml-3">info@saralkakshya.com</span>
                    </dd>
                    <dt><span class="sr-only">Address</span></dt>
                    <dd class="flex text-base text-gray-500">
                        <i class="fas fa-map-marker-alt flex-shrink-0 h-6 w-6 text-primary-600"></i>
                        <span class="ml-3">
                                Budhanilkantha<br>
                                Kathmandu<br>
                                Nepal
                            </span>
                    </dd>
                </dl>
                <div class="mt-8">
                    <div class="flex space-x-6">
                        <a href="#" class="text-primary-600 hover:text-primary-500">
                            <span class="sr-only">Facebook</span>
                            <i class="fab fa-facebook-f text-2xl"></i>
                        </a>
                        <a href="#" class="text-primary-600 hover:text-primary-500">
                            <span class="sr-only">Twitter</span>
                            <i class="fab fa-twitter text-2xl"></i>
                        </a>
                        <a href="#" class="text-primary-600 hover:text-primary-500">
                            <span class="sr-only">Instagram</span>
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                        <a href="#" class="text-primary-600 hover:text-primary-500">
                            <span class="sr-only">LinkedIn</span>
                            <i class="fab fa-linkedin-in text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-800">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
        <div class="xl:grid xl:grid-cols-3 xl:gap-8">
            <div class="space-y-8 xl:col-span-1">
                <div class="flex items-center">
                    <i class="fas fa-graduation-cap text-white text-2xl mr-2"></i>
                    <span class="font-bold text-xl text-white">SaralKakshya</span>
                </div>
                <p class="text-gray-300 text-base">
                    Making classroom management simple and effective for schools across Nepal.
                </p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white">
                        <span class="sr-only">Facebook</span>
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <span class="sr-only">Twitter</span>
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <span class="sr-only">Instagram</span>
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <span class="sr-only">LinkedIn</span>
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <span class="sr-only">YouTube</span>
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
            <div class="mt-12 grid grid-cols-2 gap-8 xl:mt-0 xl:col-span-2">
                <div class="md:grid md:grid-cols-2 md:gap-8">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">
                            Solutions
                        </h3>
                        <ul class="mt-4 space-y-4">
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    For Schools
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    For Teachers
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    For Students
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    For Parents
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-12 md:mt-0">
                        <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">
                            Support
                        </h3>
                        <ul class="mt-4 space-y-4">
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    Help Center
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    Tutorials
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    Documentation
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    Contact Us
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="md:grid md:grid-cols-2 md:gap-8">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">
                            Company
                        </h3>
                        <ul class="mt-4 space-y-4">
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    About
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    Blog
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    Careers
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    Press
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-12 md:mt-0">
                        <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">
                            Legal
                        </h3>
                        <ul class="mt-4 space-y-4">
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    Privacy
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    Terms
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    Cookie Policy
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-base text-gray-400 hover:text-white">
                                    Data Protection
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-12 border-t border-gray-700 pt-8">
            <p class="text-base text-gray-400 xl:text-center">
                &copy; 2025 SaralKakshya. All rights reserved.
            </p>
        </div>
    </div>
</footer>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,  // Show 1 slide at a time on small screens
            spaceBetween: 20,
            loop: true, // Enable infinite loop

            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                640: { slidesPerView: 1 }, // Mobile: 1 slide
                768: { slidesPerView: 2 }, // Tablet: 2 slides
                1024: { slidesPerView: 3 } // Desktop: 3 slides
            }
        });
    });
</script>



<script>

    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });

                // Close mobile menu if open
                document.getElementById('mobile-menu').classList.add('hidden');
            }
        });
    });
</script>
</body>
</html>
