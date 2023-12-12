<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tahun extends Model
{
    use HasFactory;

    protected $fillable = ['tahun_pertama', 'tahun_kedua'];
    protected $dates = ['tahun_pertama', 'tahun_kedua'];


    public function murid()
    {
        return $this->hasMany(Murid::class);
    }
    public function kategoriPembayaran()
    {
        return $this->hasMany(KategoriPembayaran::class);
    }
}
