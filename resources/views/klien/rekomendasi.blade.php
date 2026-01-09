<h2>Rekomendasi Berdasarkan Deskripsi:</h2>

<p><strong>Deskripsi Target:</strong> {{ $target['description'] }}</p>

<h3>Top 5 Deskripsi Mirip:</h3>
<ol>
@foreach ($rekomendasi as $item)
    <li>
        <p>{{ $item['description'] }}</p>
        <small>Kemiripan: {{ number_format($item['similarity'], 4) }}</small>
    </li>
@endforeach
</ol>
