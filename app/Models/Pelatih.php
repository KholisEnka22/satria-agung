<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatih extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mrd_id',
        'nama_pelatih',
        'jns_klmin',
        'nik',
        'email',
        'tingkat',
        'alamat',
        'tmpt',
        'tgl',
        'rayon_id',
        'foto',
    ];

    public function rayon()
    {
        return $this->hasMany(Rayon::class, 'rayon_id');
    }
}
