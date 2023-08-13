<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Verify Email & Generate Password</title>
</head>
<body>
    <h1>Mitra Verifikasi Email & Password</h1>
    <p>Hello {{ $mitra->namaMitra }}</p>
    <p>Berikut Merupakan Email & Password untuk Login</p>
    <p>Email    : {{ $mitra->email }}</p>
    <p>Password : {{ $password }}</p>
    <p>Dimohon untuk klik tombol dibawah ini untuk verifikasi Email</p>
    <a href="{{URL::temporarySignedRoute('verification.verify', now()->addMinutes(30), ['id' => $mitra->id, 'hash' => sha1($mitra->id)])}}" class="btn btn-primary" data-method="get">Verify Email Address</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>