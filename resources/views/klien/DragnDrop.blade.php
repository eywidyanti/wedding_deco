<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag & Drop</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="{{ asset('css/drag.css') }}">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/klien.css">
    <style>
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        #hasil-visual {

            text-align: left;
        }

        .hasil-container {
            display: inline-block;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 756px;
            max-width: 800px;
        }

        .hasil-container img {
            border-radius: 10px;
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .hasil-text {
            margin-top: 15px;
            color: #2d3748;
            text-align: left;
            line-height: 1.5;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 5px solid #ccc;
            border-top: 5px solid #38a169;
            border-radius: 50%;
            margin: 20px auto;
            animation: spin 1s linear infinite;
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <a href="#" class="navbar-logo"> Littlemee<span>Deco</span>.</a>

        <div class="navbar-nav">
            <a href="home">Home</a>
            <a href="#paket">Paket</a>
            <a href="#produk">Dekor</a>
            <a href="#galeri">Galeri</a>
            <a href="{{ route('booking.success') }}">Pembayaran</a>
            <a href="{{ route('payment') }}">Penyewaan</a>
        </div>


        <div class="profile-container">
            <img src="{{ Auth::user()->gambar ? asset('img/admin/fotoUser/' . Auth::user()->gambar) : 'default.jpg' }}"
                class="user-pic" id="profilePic">
            <div class="dropdown" id="dropdownMenu">
                <a href="{{ route('profilKlien') }}" class="dropdown-item">Profil</a>
                <a href="" class="dropdown-item"
                    onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

    </nav>
    <main>
    <h1 style="margin-bottom: 25px;">Drag & Drop</h1>

    <div class="main-layout">
        <div class="left-panel">
            <div id="dropzone" class="dropzone">Drop Zone</div>

            <div class="button-group">
                <div class="left-buttons">
                    <button onclick="window.location.href='/home'" style="background-color: grey;">Kembali</button>
                </div>
                <div class="right-buttons">
                    <button onclick="cobacart()" style="background-color: rgb(250, 180, 49);">Keranjang</button>
                    <button id="btnRekomVisual">Lihat Rekomendasi</button>
                </div>
            </div>
        </div>


        <div id="hasil-visual" class="right-panel">
            <h3>Keterangan</h3>
            <div id="list-keterangan" class="text-align: left;">
                <p>Belum ada item yang dipilih.</p>
            </div>
        </div>
    </div>

    <hr style="border:1px solid #32373e; margin:20px 0;">
    {{-- === Elemen Dekorasi === --}}
    @foreach ($dekors as $dekor)
        <div class="draggable itemA" id="dekor-{{ $dekor->id }}" data-deskripsi="{{ $dekor->deskripsi }}">
            <img src="{{ url('img/admin/gambarDekor/' . $dekor->gambar) }}" alt="Dekor">
            <div class="nama-item">{{ $dekor->nama }}</div>
        </div>
    @endforeach

    {{-- === Muat Posisi dari Database === --}}
    @if (!$position->isEmpty())
        @foreach ($pecah as $item)
            @php
                if (!empty($item)) {
                    $dekorPecah = explode(',', $item);
                    if (count($dekorPecah) >= 3) {
                        $idDekor = str_replace('{', '', trim($dekorPecah[0]));
                        $top = trim($dekorPecah[1]);
                        $left = str_replace('}', '', trim($dekorPecah[2]));
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            @endphp
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const el = document.getElementById('{{ $idDekor }}');
                    if (el) {
                        el.style.transform = `translate({{ $left }}px, {{ $top }}px)`;
                        el.setAttribute('data-x', {{ $left }});
                        el.setAttribute('data-y', {{ $top }});
                    }
                });
            </script>
        @endforeach
    @endif
        </main>
    <script src="https://unpkg.com/interactjs/dist/interact.min.js"></script>
    <script src="{{ asset('js/drag.js') }}"></script>
    <script src="js/klien.js"></script>

    <script>
        let itemDrop = [];

                // === Simpan ke cart ===
        function cobacart() {
            const elemen = itemDrop.map(id => {
                const el = document.getElementById(id);
                const x = parseFloat(el.getAttribute('data-x')) || 0;
                const y = parseFloat(el.getAttribute('data-y')) || 0;
                return `${id}#${y}#${x}`;
            });
            const posisi = elemen.join(',');
            const url = "{{ url('dragndropcart') }}?x=" + encodeURIComponent(posisi);
            window.open(url, '_self');
        }


        function updateKeterangan() {
            const container = document.getElementById("list-keterangan");
            if (itemDrop.length === 0) {
                container.innerHTML = "<p>Belum ada item.</p>";
                return;
            }

            let html = "<ul>";
            itemDrop.forEach(id => {
                const el = document.getElementById(id);
                const name = el.querySelector(".nama-item").innerText;
                const deskripsi = el.getAttribute("data-deskripsi") || "-";
                html += `<li><strong>${name}</strong><br><span>${deskripsi}</span></li>`;
            });
            html += "</ul>";
            container.innerHTML = html;
        }

        // === Tombol lihat rekomendasi ===
        document.getElementById("btnRekomVisual").addEventListener("click", async function() {
            if (itemDrop.length === 0) {
                alert("Silakan drag minimal 1 item ke Drop Zone terlebih dahulu!");
                return;
            }

            const hasilDiv = document.getElementById("hasil-visual");
            const deskripsiGabung = itemDrop.map(id => {
                const el = document.getElementById(id);
                return el.getAttribute("data-deskripsi") || "";
            }).join(" ");

            hasilDiv.innerHTML = `
                <div class="hasil-container">
                    <div class="spinner"></div>
                    <p style="color:gray;">Proses mencari rekomendasi dekorasi mirip...</p>
                </div>
            `;

            try {
                const rekomRes = await fetch("/rekomendasi/drag", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        description: deskripsiGabung
                    })
                });

                const rekomData = await rekomRes.json();

                if (Array.isArray(rekomData) && rekomData.length > 0) {
                    // tampilkan semua rekomendasi + tombol lihat visual
                    let rekomListHTML =
                        "<h3 style='color:#2b6cb0; margin-bottom:6px; text-align:center; '>Hasil Rekomendasi</h3>";
                    rekomListHTML += "<ol style='padding-left:20px; '>";
                    rekomData.forEach((item, i) => {
                        rekomListHTML += `
                            <li style="margin-bottom:10px;">
                                <strong>${item.name || "Rekomendasi " + (i + 1)}</strong>: ${item.description}
                                <br>
                                <button class="btnLihatVisual" data-index="${i}"
                                    style="margin-top:5px; background:#3182ce; color:white; padding:5px 10px; border:none; border-radius:6px; cursor:pointer;">
                                    Lihat Visualisasi
                                </button>
                            </li>`;
                    });
                    rekomListHTML += "</ol>";

                    hasilDiv.innerHTML = `
                        <div class="hasil-container">
                            ${rekomListHTML}
                            <div id="visualSection" style="margin-top:20px;"></div>
                        </div>
                    `;

                    // Event: klik tombol lihat visualisasi
                    document.querySelectorAll(".btnLihatVisual").forEach(btn => {
                        btn.addEventListener("click", async e => {
                            const index = e.target.dataset.index;
                            const rekomDipilih = rekomData[index];
                            const visualDiv = document.getElementById("visualSection");

                            visualDiv.innerHTML = `
                                <div class="spinner"></div>
                                <p style="color:gray;">AI sedang membuat visualisasi untuk rekomendasi ke-${parseInt(index) + 1}...</p>
                            `;

                            // buat form data
                            const formData = new FormData();
                            formData.append(
                                "prompt",
                                "Buatkan visualisasi dekorasi pernikahan dengan semua elemen gambar hasil drag-drop, " +
                                "detailnya harus sama dan tambahkan rincian berdasarkan rekomendasi berikut: " +
                                rekomDipilih.description
                            );
                            formData.append("_token", document.querySelector(
                                'meta[name="csrf-token"]').content);

                            // tambahkan gambar hasil drag
                            const imagePromises = itemDrop.map(async (id) => {
                                const el = document.getElementById(id);
                                const imgEl = el.querySelector("img");
                                if (imgEl) {
                                    const response = await fetch(imgEl.src);
                                    const blob = await response.blob();
                                    formData.append("images[]", blob,
                                        `${id}.jpg`);
                                }
                            });
                            await Promise.all(imagePromises);

                            const res = await fetch("{{ route('visual.generate') }}", {
                                method: "POST",
                                body: formData,
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });

                            if (!res.ok) throw new Error("Gagal menghubungi server AI");

                            const data = await res.json();

                            if (data.generated_image) {
                                visualDiv.innerHTML = `
                                    <hr style="border:1px solid #e2e8f0; margin:20px 0;">
                                    <h3 style="color:#2f855a;">Hasil Visualisasi Rekomendasi ${parseInt(index) + 1}</h3>
                                    <img src="data:image/png;base64,${data.generated_image}" alt="Hasil Dekorasi">
                                    <div class="hasil-text">
                                        <strong>Visual AI:</strong>
                                        <p>${data.result || "AI telah menghasilkan tata letak dekorasi berdasarkan rekomendasi ini."}</p>
                                    </div>
                                    <a href="data:image/png;base64,${data.generated_image}" download="rekomendasi-${index + 1}.png"
                                        style="background:#38a169; color:white; padding:8px 16px; border-radius:8px; text-decoration:none; font-weight:bold; display:inline-block; margin-top:10px;">
                                        Download Gambar
                                    </a>
                                `;
                            } else {
                                visualDiv.innerHTML =
                                    `<p style='color:orange;'>Gagal menampilkan gambar rekomendasi ${index + 1}.</p>`;
                            }
                        });
                    });
                } else {
                    hasilDiv.innerHTML = `
                        <div class="hasil-container">
                            <p style='color:orange;'>Tidak ada rekomendasi yang ditemukan.</p>
                        </div>
                    `;
                }
            } catch (err) {
                hasilDiv.innerHTML = `
                    <div class="hasil-container" style="border-color:red;">
                        <p style='color:red; font-weight:bold;'>Terjadi Kesalahan!</p>
                        <p style='font-size:12px; color:gray;'>${err.message}</p>
                        <button onclick="location.reload()" style="margin-top:10px;">Muat Ulang Halaman</button>
                    </div>
                `;
            }
        });
    </script>
</body>

</html>
