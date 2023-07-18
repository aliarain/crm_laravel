<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- currently using it --> 
    <title>{{env('APP_NAME')}} | @yield('title')</title>
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/') }}public/frontend/assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}public/frontend/assets/slick.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}public/frontend/assets/slick-theme.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}public/frontend/assets/animate.min.css">
    <link rel="stylesheet" href="{{ asset('public/vendors/') }}/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('public/frontend/css/frontend.css') }}">
    <link rel="icon" type="image/x-icon" href="{{url('public/assets/images/favicon.png')}}">
</head>
<body class="dark-theme">