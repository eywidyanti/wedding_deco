<?php

namespace App\Http\Controllers\Rekomendasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;



class CBFController extends Controller
{

    public function fromDrag(Request $request)
    {
        $desc = $request->input('description');
        if (!$desc) {
            return response()->json(['error' => 'Deskripsi tidak dikirim.'], 400);
        }

        $path = storage_path('app/public/cbf/pinterest_detail.csv');
        $data = array_map('str_getcsv', file($path));
        $headers = array_shift($data);

        // Lewati baris yang kolomnya tidak lengkap
        $items = [];
        foreach ($data as $row) {
            if (count($row) == count($headers)) {
                $items[] = array_combine($headers, $row);
            }
        }

        $descriptions = array_column($items, 'description');
        $descriptions[] = $desc; // tambahkan input user

        // === Preprocessing ===
        $processed = array_map(fn($text) => $this->preprocess($text), $descriptions);

        // === Vectorizer dan TF-IDF ===
        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
        $vectorizer->fit($processed);
        $tempProcessed = $processed;
        $vectorizer->transform($tempProcessed);

        $tfidf = new TfIdfTransformer($tempProcessed);
        $tfidf->transform($tempProcessed);

        // === Hitung cosine similarity ===
        $targetIndex = count($tempProcessed) - 1;
        $targetVector = $tempProcessed[$targetIndex];

        $similarities = [];
        foreach ($tempProcessed as $i => $vec) {
            if ($i == $targetIndex) continue;
            $similarity = $this->cosineSimilarity($targetVector, $vec);
            $similarities[] = [
                'description' => $items[$i]['description'],
                'similarity' => $similarity
            ];
        }

        usort($similarities, fn($a, $b) => $b['similarity'] <=> $a['similarity']);
        return response()->json(array_slice($similarities, 0, 5));
    }

    private function preprocess($text)
    {
        // Case folding
        $text = strtolower($text);

        // Hapus karakter non-huruf
        $text = preg_replace('/[^a-z\s]/', '', $text);

        // Stopword removal
        $stopwordFactory = new StopWordRemoverFactory();
        $stopword = $stopwordFactory->createStopWordRemover();
        $text = $stopword->remove($text);

        // Stemming
        $stemmerFactory = new StemmerFactory();
        $stemmer = $stemmerFactory->createStemmer();
        $text = $stemmer->stem($text);

        // Tokenisasi
        $tokens = preg_split('/\s+/', trim($text));

        return implode(' ', $tokens);
    }


    private function cosineSimilarity(array $vecA, array $vecB)
    {
        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        $length = count($vecA);
        

        for ($i = 0; $i < $length; $i++) {
            $dotProduct += $vecA[$i] * $vecB[$i];
            $normA += $vecA[$i] ** 2;
            $normB += $vecB[$i] ** 2;
        }

        if ($normA == 0 || $normB == 0) {
            return 0.0;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }









}
