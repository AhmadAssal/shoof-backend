<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Your Password</title>
</head>

<body>
    <img src='{{ $message->embed(public_path() . '/Logo.png') }}' style="width: 10rem; height:10rem;">
    <h1>Reset Password Email</h1>
    <p>Hello</p>
    <p>We received a request to change your password. If you didn't send this request, ignore this email.</p>
    <p>If you sent the request, please follow this following link and enter this token: {{ $token }}</p>

    <a href={{ config('app.forget_password') }}> reset your password</a>
</body>

</html>
