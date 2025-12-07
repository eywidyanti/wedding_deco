<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Dekorasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/galeri.css">
</head>

<body>
    <div class="container">
        <div class="title">Detail Dekorasi</div>

        <div class="content">
            <form>
                <div class="produk-detail">
                    <div class="input-box">
                        <strong>Nama User:</strong>
                        <span>{{ $dekor->user->name ?? '-' }}</span>
                    </div>

                    <div class="input-box">
                        <strong>Kategori:</strong>
                        <span>{{ $dekor->kategori->nama_kategori ?? '-' }}</span>
                    </div>

                    <div class="input-box">
                        <strong>Nama Dekorasi:</strong>
                        <span>{{ $dekor->nama }}</span>
                    </div>

                    <div class="input-box">
                        <strong>Tema:</strong>
                        <span>{{ $dekor->tema ?? '-' }}</span>
                    </div>

                    <div class="input-box">
                        <strong>Gaya:</strong>
                        <span>{{ $dekor->gaya ?? '-' }}</span>
                    </div>

                    <div class="input-box">
                        <strong>Warna:</strong>
                        <span>{{ $dekor->warna ?? '-' }}</span>
                    </div>

                    <div class="input-box">
                        <strong>Harga:</strong>
                        <span>Rp {{ number_format($dekor->harga, 0, ',', '.') }}</span>
                    </div>

                    <div class="input-box">
                        <strong>Deskripsi:</strong>
                        <p style="margin-top:5px;">{{ $dekor->deskripsi }}</p>
                    </div>

                    <div class="input-box">
                        <strong>Gambar:</strong><br>
                        @if ($dekor->gambar)
                            <img src="/img/admin/gambarDekor/{{ $dekor->gambar }}" width="300px" style="margin-top:10px;">
                        @else
                            <p>- Tidak ada gambar -</p>
                        @endif
                    </div>
                </div>

                <div class="btn-container" style="margin-top: 20px;">
                    <a class="btn1" style="text-decoration:none;" href="{{ route('dekor.index') }}">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
