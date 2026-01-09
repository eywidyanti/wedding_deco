<!DOCTYPE html>
<html lang="id">
<head>
    <title>Hasil Visualisasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow">
        <h1 class="text-2xl font-bold mb-4">Hasil Rekomendasi Dekorasi</h1>
        <p class="mb-6 text-gray-600">Tema: <strong>{{ $theme }}</strong></p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="font-bold mb-2">Foto Asli:</h3>
                <img src="{{ $original_image }}" class="rounded shadow w-full">
            </div>
            <div>
                <h3 class="font-bold mb-2">Saran AI:</h3>
                <div class="prose text-gray-800 italic leading-relaxed">
                    {!! nl2br(e($description)) !!}
                </div>
            </div>
        </div>
        
        <div class="mt-8">
            <a href="{{ route('decor.index') }}" class="text-pink-500 underline">Kembali ke awal</a>
        </div>
    </div>
</body>
</html>