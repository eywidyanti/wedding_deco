@extends('klien.layout')

@section('content')
    <!-- hero section start -->
    <section class="hero" id="home">

        <main class="content">
            @if (session('success'))
                <div class="notifikasi success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="notifikasi error">
                    {{ session('error') }}
                </div>
            @endif
            <h1> Mari Belanja Kebutuhan <span>Wedding</span></h1>
            <a href="#paket" class="cta">Pesan Sekarang</a>
        </main>
    </section>
    <!-- hero section end -->

    <!-- paket -->
    <section id="paket" class="produk">

        <h2>Paket<span> Kami</span></h2>

        <div class="filter-container">
            <div class="filter-section">
                <form action="{{ route('caridekor') }}" method="GET" id="filter-form">
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
                            <a href="{{ route('add.to.cart', $pak->id) }}"><i data-feather="shopping-cart"></i></a>
                            <a href="{{ route('detail', $pak->slug) }}" class="item-detail-button"><i
                                    data-feather="eye"></i></a>
                        </div>
                        <div class="produk-image">
                            @if(!Empty($pak->gambar))
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
                <a href="#produk">Paket</a>
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
                <span>Tentang Bisini</span>
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

    <!-- Modal box item detail -->
    
@endsection
