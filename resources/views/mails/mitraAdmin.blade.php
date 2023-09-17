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
    <div class="container d-flex flex-column justify-content-center">
        <img src="{{ asset('storage/img/logo2.png') }}" alt="Logo D'Bandeng">
        <h1>Join Mitra <span class="text-primary">D'Bandeng</span></h1>
        <p>Hello {{ $contact->nameCn }}</p>
        <p>Perhatikan Syarat Dibawah ini Sebelum Join Kemitraan</p>
        <table class="table table-borderless">
            <tr>
                <td>
                    <ul>
                        <li>Milik warga negara Indonesia (WNI)</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>
                    <ul>
                        <li>Setiap mitra harus membawa keterampilan dan keahlian yang unik ke dalam kemitraan.</li>
                    </ul>
                </td>
                <td>
                    <ul>
                        <li>Para mitra harus memiliki visi yang sejalan dan nilai-nilai yang serupa dalam menjalankan usaha atau proyek bersama.</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>
                    <ul>
                        <li>Berdiri sendiri dan termasuk usaha mikro.</li>
                    </ul>
                </td>
                <td>
                    <ul>
                        <li>Telah melakukan usaha minimal 3 bulan serta memiliki potensi dan prospek untuk dikembangkan</li>
                    </ul>
                </td>
            </tr>
        </table>
        <a href="https://dbandeng.online/register" class="btn btn-primary">Register Mitra Here</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>