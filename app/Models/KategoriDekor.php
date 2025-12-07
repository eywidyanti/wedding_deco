<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriDekor extends Model
{
    use HasFactory;

    protected $table = 'kategori_dekor';
    protected $fillable = ['nama_kategori', 'deskripsi'];

    public function dekors()
    {
        return $this->hasMany(Dekor::class, 'kategori_id');
    }
}
