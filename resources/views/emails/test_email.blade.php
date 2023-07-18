<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    
    <title> {{ config('settings.app.company_name') }}</title>
</head>
<body>
<p>{{ _trans('emailTemplate.Hi,')}}</p>
<p>{{ _trans('emailTemplate.This is test email')}}</p>
<p>
   
    {{ _trans('emailTemplate. You are receiving this email because you have requested to test email setup.')}}
</b>
</p>
</body>
</html>
