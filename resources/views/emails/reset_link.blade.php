<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title> {{ config('settings.app.company_name') }} - @yield('title')</title>
</head>

<body>
    <p>Hi {{ $user->name }},</p>
    <p>{{ _trans('common.Reset your password') }}</p>
    <p>
        {{ _trans('common.This is your verification code') }} <b>{{ $user->verification_code }}</b>
    </p>
</body>

</html>
