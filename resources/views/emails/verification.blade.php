<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('email.subject') }}</title>
</head>
<body>

<p>{{ __('email.appeal_to_the_user') }}</p>
<p>{{ __('email.greetings') }} <a href="https://www.youtube.com/watch?v=HnTWkN79PCo&list=RDHnTWkN79PCo&start_radio=1">Lav Ban</a></p>
<p>👉 {{ __('email.verify') }}</p>
<h3>{{ $verificationUrl }}</h3>
<p>{{ __('email.note') }}</p>
<p>{{ __('email.remain_inactive') }}</p>
<p>{{ __('email.ignore_message') }}</p>
<br>
<p>{{ __('email.thanks') }}</p>
<p>{{ __('email.from') }}</p>


</body>
</html>
