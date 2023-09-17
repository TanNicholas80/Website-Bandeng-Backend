<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Resend Verification Email</title>
</head>
<body>
    <div class="container-fluid d-flex flex-column justify-content-center align-items-center" style="height: 100vh">
        <img src={{ asset('img/check.png') }} alt="email-verified" width="200px" class="mb-3">
        <h3 class="mb-3">{{ $msg }}</h3>
        <a href="https://dbandeng.online" class="btn btn-primary rounded-pill fw-bold" width="140px"><button type="button">Resend Email</button></a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>