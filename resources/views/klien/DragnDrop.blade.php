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

    <div id="dropzone" class="dropzone">Drop Zone</div>
    <button onclick="coba()">Simpan</button>
    <button onclick="cobacart()">Cart</button>

    <hr>

    {{-- === Elemen Dekorasi === --}}
    @foreach ($dekors as $dekor)
        <div class="draggable itemA large-background" id="dekor-{{ $dekor->id }}">
            <img src="img/admin/gambarDekor/{{ $dekor->gambar }}" alt="Image A">
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
