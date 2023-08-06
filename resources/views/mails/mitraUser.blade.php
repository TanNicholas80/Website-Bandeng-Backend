<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Pengajuan Mitra</title>
</head>
<body>
    <div class="container bg-primary">
        <div class="mx-auto">
            <img src="{{ asset('storage/img/logo2.png') }}" alt="Logo D'Bandeng">
        </div>
        <p>Kategori Pesan : <span class="bg-secondary-subtle rounded-pill">{{ $contact->kategoriCn }}</span></p>
        <p>Nama : {{ $contact->nameCn }}</p>
        <p>Email : {{ $contact->emailCn }}</p>
        <p>Pesan : {{ $contact->pesanCn }}</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>