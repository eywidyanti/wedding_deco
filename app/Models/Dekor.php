<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dekor extends Model
{
    protected $table = 'dekor';
    protected $fillable = ['user_id', 'kategori_id', 'nama', 'slug', 'gambar', 'harga', 'deskripsi', 'tema', 'gaya', 'warna'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paket()
    {
        return $this->hasMany(Paket::class)->using(PaketDekor::class);
    }

    public function bookingDetail()
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function paketDekors()
    {
        return $this->hasMany(PaketDekor::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriDekor::class, 'kategori_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
