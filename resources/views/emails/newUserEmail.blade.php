<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ app()->getLocale() }}">
<head>
    <title>www.tethys.at"</title>
</head>
<body>
    <h1>{{ $details['title'] }}</h1>

    <p>
    Dear {{$details['admin_name']}},
    </p>
    
    a new user with email {{ $details['email'] }} has been registered to your site.

Thanks,
{{ config('app.name') }}
    <p>Thank you</p>
</body>
</html>
