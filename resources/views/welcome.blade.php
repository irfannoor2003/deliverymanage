<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="0;url={{ route('login') }}">
    <title>{{ __('messages.redirecting') }}</title>
</head>
<body>
    <p>{{ __('messages.redirecting') }} <a href="{{ route('login') }}">{{ __('messages.login') }}</a></p>
</body>
</html>
