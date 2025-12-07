<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag & Drop</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/drag.css">
</head>

<body>
    <h1>Drag & Drop</h1>


    <div class="main-layout">
    <div class="left-panel">
        <div id="dropzone" class="dropzone">Drop Zone</div>
        <div class="button-group">
            <button onclick="coba()">Simpan</button>
            <button onclick="cobacart()">Cart</button>
        </div>
    </div>

    <div id="panel-keterangan" class="right-panel">
        <h3>Keterangan</h3>
        <div id="list-keterangan">
            <p>Belum ada item.</p>
        </div>
    </div>
</div>


    <hr>

    {{-- === Elemen Dekorasi === --}}
    @foreach ($dekors as $dekor)
        <div class="draggable itemA large-background" id="dekor-{{ $dekor->id }}" data-deskripsi="{{ $dekor->deskripsi }}">
            <img src="img/admin/gambarDekor/{{ $dekor->gambar }}" alt="Image A">
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

    <script>
        let itemDrop = [];

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
                // const x = el.getAttribute("data-x") || 0;
                // const y = el.getAttribute("data-y") || 0;

                html += `
            <li style="margin-bottom: 8px;">
                <strong>${name}</strong><br>
                <span>${deskripsi}</span><br>
                
            </li>
        `;
            });

            // ID: ${id}<br>
            //     Posisi: (X: ${x}, Y: ${y})

            html += "</ul>";
            container.innerHTML = html;
        }


        function coba() {
            const elemen = itemDrop.map(id => {
                const el = document.getElementById(id);
                const x = parseFloat(el.getAttribute('data-x')) || 0;
                const y = parseFloat(el.getAttribute('data-y')) || 0;
                return `{${id},${y},${x}}`;
            });
            const posisi = elemen.join(',');
            const url = "{{ url('DragnDrop/simpan') }}?x=" + encodeURIComponent(posisi);
            window.open(url, '_self');
        }

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
    </script>

    {{-- Library dan Script --}}
    <script src="https://unpkg.com/interactjs/dist/interact.min.js"></script>
    <script src="js/drag.js"></script>

    <script>
        // Pastikan Interact direset setelah posisi dari DB di-load
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                if (typeof resetInteract === 'function') {
                    resetInteract();
                }
            }, 500);
        });
    </script>
</body>

</html>
