<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag &amp; Drop</title>

    <!-- css -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/drag.css">

</head>

<body>
    <h1>Drag &amp; Drop</h1>

    <!-- dropzone -->
    <p></p>
    <div id="dropzone" class="dropzone">Drop Zone</div>
    <button onclick="coba()">Simpan</button>
    <button onclick="cobacart()">Cart</button>

    <hr>

    <!-- Part 3 -->
    @foreach ($dekors as $dekor)
    <div class="draggable itemA large-background" id="dekor-{{ $dekor->id }}">
        <img src="img/admin/gambarDekor/{{ $dekor->gambar }}" alt="Image A">
    </div>

    @endforeach

    @if (!$position->isEmpty())
        @foreach ($pecah as $item)
            <!-- <div>{{ $item }}</div> -->
            @php
                $dekorPecah = explode(',', $item);
                $idDekor = str_replace('{', '', $dekorPecah[0]);
                $kiri = str_replace('', '', $dekorPecah[1]);
                $kanan = str_replace('}', '', $dekorPecah[2]);
            @endphp
        @endforeach

        @php
            $i = 0;
        @endphp

    @endif

    <script>
        let itemDrop = [];

        function coba() {
            var dropzone = document.getElementById('dropzone').getBoundingClientRect();
            var elemen = [];
            itemDrop.forEach(element => {
                elemen.push({
                    id: element,
                    el: document.getElementById(element)
                })
            });
            var posisi = elemen.reduce(function(result, item) {
                var rect = item.el.getBoundingClientRect();
                result.push(`{${item.id},${rect.top},${rect.left}}`);
                return result;
            }, []).join(',');

            var url = "{{ url('DragnDrop/simpan') }}?x=" + encodeURIComponent(posisi);
            window.open(url, '_self');
        }

        function cobacart() {
            var dropzone = document.getElementById('dropzone').getBoundingClientRect();
            var elemen = [];
            itemDrop.forEach(element => {
                elemen.push({
                    id: element,
                    el: document.getElementById(element)
                })
            });
            var posisi = elemen.reduce(function(result, item) {
                var rect = item.el.getBoundingClientRect();
                result.push(`${item.id}#${rect.top}#${rect.left}`);
                return result;
            }, []).join(',');

            var url = "{{ url('dragndropcart') }}?x=" + encodeURIComponent(posisi);
            window.open(url, '_self');
        }


        @if (!$position->isEmpty())
            @foreach ($pecah as $item)
                @php
                    $dekorPecah = explode(',', $item);
                    $idDekor = str_replace('{', '', $dekorPecah[0]);
                    $kiri = str_replace('', '', $dekorPecah[1]);
                    $kanan = str_replace('}', '', $dekorPecah[2]);
                @endphp

                document.getElementById('{{ $idDekor }}').style.position = "absolute";
                document.getElementById('{{ $idDekor }}').style.left = '{{ $kanan }}px';
                document.getElementById('{{ $idDekor }}').style.top = '{{ $kiri }}px';
            @endforeach
        @endif




        function reset() {
            @if (!$position->isEmpty())
                @foreach ($pecah as $item)
                    @php
                        $dekorPecah = explode(',', $item);
                        $idDekor = str_replace('{', '', $dekorPecah[0]);
                        $kiri = str_replace('', '', $dekorPecah[1]);
                        $kanan = str_replace('}', '', $dekorPecah[2]);
                    @endphp

                    document.getElementById('{{ $idDekor }}').style.position = "absolute";
                    document.getElementById('{{ $idDekor }}').style.left = '0px';
                @endforeach
            @endif
        }
    </script>

    <script src="js/drag.js"></script>
    <script src="https://unpkg.com/interactjs/dist/interact.min.js"></script>
</body>

</html>
