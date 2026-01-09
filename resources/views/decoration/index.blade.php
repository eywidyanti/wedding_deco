<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gemini AI Laravel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen p-6 md:p-12">

    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-blue-600 p-6">
            <h1 class="text-white text-2xl font-bold">Gemini AI Image Processor</h1>
            <p class="text-blue-100">Upload gambar dan berikan instruksi ke AI</p>
        </div>

        <div class="p-8">
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('result'))
                <div class="mb-8 p-6 bg-slate-50 border border-slate-200 rounded-xl">
                    <h3 class="font-bold text-slate-800 mb-2 italic">Respons AI:</h3>
                    <div class="prose max-w-none text-slate-700">
                        {!! Str::markdown(session('result')) !!}
                    </div>
                </div>
            @endif

            @if(session('generated_image'))
    <div class="mb-8 p-6 bg-green-50 border border-green-200 rounded-xl text-center">
        <h3 class="font-bold text-green-800 mb-4 text-left">Hasil Kreasi AI:</h3>
        
        <img src="data:image/png;base64,{{ session('generated_image') }}" 
             class="mx-auto rounded-lg shadow-2xl max-w-full h-auto border-4 border-white">
        
        <div class="mt-4 flex justify-center gap-4">
            <a href="data:image/png;base64,{{ session('generated_image') }}" 
               download="ai-generated-cat.png" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
               Download Gambar
            </a>
        </div>
    </div>
@endif

            <form action="{{ route('gemini.generate') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Gambar (Base)</label>
                        <input type="file" name="image" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Instruksi AI (Prompt)</label>
                        <textarea name="prompt" rows="4" required class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="Contoh: Buat gambar kucing saya sedang makan di restoran mewah..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-lg shadow-blue-200">
                        Proses Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>