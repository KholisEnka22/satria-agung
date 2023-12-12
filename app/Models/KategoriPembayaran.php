<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPembayaran extends Model
{
    use HasFactory;

    protected $fillable = ['thn_id','nama_tagihan','jumlah'];

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class);
    }
    public function tahun()
    {
        return $this->belongsTo(Tahun::class, 'thn_id');
    }
}
