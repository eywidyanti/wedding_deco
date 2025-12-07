<?php

namespace Database\Seeders;

use App\Models\KategoriDekor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriDekorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Backdrop', 'deskripsi' => 'Latar belakang dekor utama di area pelaminan atau panggung.'],
            ['nama_kategori' => 'Kursi Pelaminan', 'deskripsi' => 'Kursi utama untuk pengantin atau tamu kehormatan.'],
            ['nama_kategori' => 'Bunga Lantai', 'deskripsi' => 'Hiasan bunga di area lantai atau jalan masuk.'],
            ['nama_kategori' => 'Vas Bunga', 'deskripsi' => 'Wadah bunga untuk meja atau sudut ruangan.'],
            ['nama_kategori' => 'Lampu', 'deskripsi' => 'Dekorasi pencahayaan untuk menambah suasana.'],
            ['nama_kategori' => 'Hiasan', 'deskripsi' => 'Aksesoris tambahan seperti tirai, pita, dan ornamen.'],
        ];

        foreach ($kategori as $item) {
            KategoriDekor::create($item);
        }
    }
}
