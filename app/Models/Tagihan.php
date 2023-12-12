<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $fillable = ['kategori_id','jumlah','status','mrd_id'];

    public function murid()
    {
        return $this->belongsTo(Murid::class,'mrd_id');
    }

    // Relasi Many-to-One dengan KategoriPembayaran
    public function kategori()
    {
        return $this->belongsTo(KategoriPembayaran::class);
    }
}
