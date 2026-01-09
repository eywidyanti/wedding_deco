<?php

namespace App\Http\Controllers\Rekomendasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VisualController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg', 
            'prompt' => 'required|string',
        ]);

        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-image:generateContent?key={$apiKey}";


        $parts = [["text" => $request->prompt]];

        foreach ($request->file('images') as $image) {
            $parts[] = [
                "inline_data" => [
                    "mime_type" => $image->getMimeType(),
                    "data" => base64_encode(file_get_contents($image->getRealPath()))
                ]
            ];
        }

        $payload = [
            "contents" => [["parts" => $parts]],
            "generationConfig" => [
                "responseModalities" => ["IMAGE", "TEXT"],
                "temperature" => 0.7,
            ]
        ];

        try {
            $response = Http::post($url, $payload);
            $data = $response->json();

            if ($response->failed()) {
                return response()->json(['error' => 'API Error: ' . ($data['error']['message'] ?? 'Unknown Error')], 400);
            }

            $generatedImageBase64 = null;
            $responseText = "";

            if (isset($data['candidates'][0]['content']['parts'])) {
                foreach ($data['candidates'][0]['content']['parts'] as $part) {
                    if (isset($part['text'])) {
                        $responseText .= $part['text'];
                    }
                    // Google API sering menggunakan camelCase 'inlineData' di response
                    if (isset($part['inlineData']['data'])) {
                        $generatedImageBase64 = $part['inlineData']['data'];
                    } elseif (isset($part['inline_data']['data'])) {
                        $generatedImageBase64 = $part['inline_data']['data'];
                    }
                }
            }

            return response()->json([
                'generated_image' => $generatedImageBase64,
                'result' => $responseText ?: 'Berhasil diproses.',
                'debug_raw' => $data // Hapus jika sudah stabil
            ]);

        } catch (\Exception $e) {
            Log::error('Gemini Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }
}