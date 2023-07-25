<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify Email</title>
</head>
<body>
    <h1>Mitra Email Verification</h1>
    <p>Hello {{ $mitra->namaMitra }}</p>
    <p>Please Click The Button Below to Verify your Email</p>
    <a href="{{URL::temporarySignedRoute('verification.verify', now()->addMinutes(30), ['id' => $mitra->id, 'hash' => sha1($mitra->id)])}}" class="button button-primary" data-method="get">Verify Email Address</a>
</body>
</html>