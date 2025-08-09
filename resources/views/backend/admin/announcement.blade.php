
@extends("backend.layout.admin-dashboard-layout")
@section("title")
    Announcements
@endsection

@section('username')
    {{$user->fname}} {{$user->lname}}
@endsection

@section('fname')
    {{$user->fname}}
@endsection
@section('lname')
    {{$user->lname}}
@endsection
@section('profile_picture')
    {{$user->profile_picture}}
@endsection

@section('content')
    <!-- Main Content Area -->
    @livewire('admin.show-announcement')
@endsection
