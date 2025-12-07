<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Org</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
        rel="stylesheet">

    <!-- icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- style -->
    <link rel="stylesheet" href="/css/style.css">

    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- midtrans -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-d9lenCMr2S90uRk-"></script>
</head>

<body>
    <!-- Navbar started -->
    <nav class="navbar" x-data>
        <a href="#" class="navbar-logo"> Littlemee<span>Deco</span>.</a>

        <div class="navbar-nav">
            <a href="#home">Home</a>
            <a href="#paket">Paket</a>
            <a href="#produk">Dekor</a>
            <a href="#galeri">Galeri</a>
        </div>

        <div class="navbar-extra">
            <a href="{{ route('login') }}" id="users"><i data-feather="users"></i></a>
            <a id="furniture-menu"><i data-feather="menu"></i></a>
        </div>

    </nav>
    <!-- Navbar end -->

    <!-- hero section start -->
    <section class="hero" id="home">
        @if (session('success'))
            <div class="notifikasi.success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="notifikasi.error">
                {{ session('error') }}
            </div>
        @endif

        <main class="content">
            <h1> Mari Belanja Kebutuhan <span>Wedding</span></h1>
            <a href="{{ route('login') }}" class="cta">Pesan Sekarang</a>
        </main>
    </section>
    <!-- hero section end -->

    <!-- Menu start-->
    <section id="paket" class="produk">

        <h2>Paket<span> Kami</span></h2>

        <div class="filter-container">
            <div class="filter-section">
                <form action="{{ route('cari') }}" method="GET" id="filter-form">
                    @csrf
                    <label for="filter-date">Cari tanggal dekor anda:</label>
                    <input type="date" id="filter-date" name="date" value="{{ request('date') }}">
                    <button type="submit" id="filter-button">Cari</button>
                </form>
            </div>
        </div>

        <div class="row">
            {{-- @php
                $paketGroupedDekors = $dekors->groupBy('paket_id');
                $paketTotalHarga = $paketGroupedDekors->map(function ($items) {
                    $totalHarga = 0;

                    foreach ($items as $item) {
                        $hargaStok = $item->harga * $item->stok;
                            $jumlah = $hargaStok * 0.1;
                            $totalHarga += $hargaStok - $jumlah;

                    }

                    return $totalHarga;
                });

                $uniquePakets = $dekors->unique('paket_id')->pluck('paket');
            @endphp --}}

            @foreach ($pakets as $pak)
                <div class="col-md-4">
                    <div class="produk-card">
                        <div class="produk-icons">
                            <a href="{{ route('add.to.cart', $pak->slug) }}"><i data-feather="shopping-cart"></i></a>
                            <a href="{{ route('detailwc', $pak->slug) }}" class="item-detail-button"><i
                                    data-feather="eye"></i></a>
                        </div>
                        <div class="produk-image">
                            @if (!empty($pak->gambar))
                                <img src="img/admin/gambarpaket/{{ $pak->gambar }}" alt="Produk 1">
                            @else
                                <img src="img/admin/images.png" alt="Produk 1">
                            @endif
                        </div>
                        <div class="produk-content">
                            <h3>{{ $pak->nama }}</h3>
                            <div class="produk-price">{{ formatRupiah($pak->harga) }}</div>
                            <hr>
                            <h4>Deskripsi</h4>
                            <p>{{ $pak->deskripsi }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </section>
    <!-- paket end -->

    <!-- dekor start -->
    <section id="produk" class="produk">
        <h2>Dekor<span> Dekorasi</span></h2>

        <a href="{{ route('DragnDrop') }}"><button class="btndnd btnn">
                Visualisasikan Kreativitas Anda
            </button></a>

        <div class="row">
            @if ($dekorsFromPaketDekor->isEmpty() && $dekors->isEmpty())
                <p>Tidak ada dekorasi tersedia pada tanggal ini.</p>
            @elseif ($dekors->isNotEmpty())
                @foreach ($dekors as $dekorasi)
                    <div class="produk-card">
                        <div class="produk-icons">
                            <a href="{{ route('add.to.cart', $dekorasi->slug) }}"><i
                                    data-feather="shopping-cart"></i></a>
                        </div>
                        <div class="produk-image">
                            <img src="img/admin/gambardekor/{{ $dekorasi->gambar }}" alt="{{ $dekorasi->nama }}">
                        </div>
                        <div class="produk-content">
                            <h3>{{ $dekorasi->nama }}</h3>
                            <div class="produk-price">{{ formatRupiah($dekorasi->harga) }}</div>
                            <h4>Deskripsi</h4>
                            <p>{{ $dekorasi->deskripsi }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                @foreach ($dekorsFromPaketDekor as $dekor)
                    <div class="produk-card">
                        <div class="produk-icons">
                            <a href="{{ route('add.to.cart', $dekor->slug) }}"><i data-feather="shopping-cart"></i></a>
                        </div>
                        <div class="produk-image">
                            <img src="img/admin/gambardekor/{{ $dekor->gambar }}" alt="{{ $dekor->nama }}">
                        </div>
                        <div class="produk-content">
                            <h3>{{ $dekor->nama }}</h3>
                            <div class="produk-price">{{ formatRupiah($dekor->harga) }}</div>
                            <h4>Deskripsi</h4>
                            <p>{{ $dekor->deskripsi }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>
    <!-- dekor end -->

    <!-- galeri start -->
    <section id="galeri" class="galeri">
        <h2>Galeri<span> Dekorasi</span></h2>

        <div class="card-container">
            @foreach ($galeris as $galeri)
                <div class="card">
                    <div class="gallery">
                        <div class="gallery-wrapper">
                            <div class="gallery-slide">
                                <img src="img/admin/gambarGaleri/{{ $galeri->gambar }}" alt="Produk 1">
                            </div>
                            <div class="gallery-slide">
                                <img src="img/admin/gambarGaleri/{{ $galeri->gambar1 }}" alt="Produk 1">
                            </div>
                            <div class="gallery-slide">
                                <img src="img/admin/gambarGaleri/{{ $galeri->gambar2 }}" alt="Produk 1">
                            </div>
                            <div class="gallery-slide">
                                <video width="320" height="240" controls>
                                    <source src="img/admin/videoGaleri/{{ $galeri->video }}" type="video/mp4">
                                </video>
                            </div>
                        </div>
                        <button class="gallery-prev">❮</button>
                        <button class="gallery-next">❯</button>
                    </div>
                    <div class="card-content">
                        <h3>Deskripsi</h3>
                        <p>{{ $galeri->deskripsi }}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </section>
    <!-- galeri end -->

    <!-- Footer -->
    <footer class="footer-distributed">

        <div class="footer-left">
            <img src="img/logo.jpg">
            <h3>Littlemee.<span>Deco</span></h3>

            <p class="footer-links">
                <a href="#home">Home</a>
                |
                <a href="#produk">Store</a>
                |
                <a href="#galeri">Galeri</a>
            </p>

            <p class="footer-company-name">© 2024 JWDeveloper</p>
        </div>

        <div class="footer-center">
            <div>
                <i class="fa fa-map-marker"></i>
                <p><span>Polagan Kec. Galis</span>
                    Kabupaten Pamekasan, Jawa Timur</p>
            </div>

            <div>
                <i class="fa fa-phone"></i>
                <p><a href="whatsapp://send?text=Hello&phone=+6282337413400">+62 8233-741-3400</a></p>
            </div>
            <div>
                <i class="fa fa-envelope"></i>
                <p><a href="mailto:jakfarjakpeng007@gmail.com">jafarjakpeng007@gmail.com</a></p>
            </div>
        </div>
        <div class="footer-right">
            <p class="footer-company-about">
                <span>Tentang Bisnis</span>
                Kami menyediakan berbagai macam keperluan dekorasi untuk acara bahagia anda.
            </p>
            <div class="footer-icons">
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
                <a href="#"><i class="fa fa-instagram"></i></a>
                <a href="#"><i class="fa fa-linkedin"></i></a>
                <a href="#"><i class="fa fa-youtube"></i></a>
            </div>
            <span style="color: white;">Politeknik Negeri Malang</span>
            <img src="img/logop.png" style="width: 15%;">
        </div>
    </footer>
    <!-- Footer -->



    <!-- icons -->
    <script>
        feather.replace();
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const descriptions = document.querySelectorAll('.produk .produk-content p:last-of-type');

            descriptions.forEach(description => {
                description.addEventListener('click', function() {
                    this.classList.toggle('expanded');
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const galleries = document.querySelectorAll('.gallery');

            galleries.forEach(gallery => {
                const wrapper = gallery.querySelector('.gallery-wrapper');
                const slides = gallery.querySelectorAll('.gallery-slide');
                const prevButton = gallery.querySelector('.gallery-prev');
                const nextButton = gallery.querySelector('.gallery-next');

                let currentIndex = 0;

                function updateGallery() {
                    wrapper.style.transform = `translateX(-${currentIndex * 100}%)`;
                }

                prevButton.addEventListener('click', () => {
                    currentIndex = (currentIndex > 0) ? currentIndex - 1 : slides.length - 1;
                    updateGallery();
                });

                nextButton.addEventListener('click', () => {
                    currentIndex = (currentIndex < slides.length - 1) ? currentIndex + 1 : 0;
                    updateGallery();
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <!-- JS -->
    <script src="js/script.js"></script>
    <script src="js/app.js"></script>
</body>

</html>
