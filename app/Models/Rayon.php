<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rayon extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_rayon',
        'id_plth'
    ];
    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class, 'id_plth');
    }
    public function murid()
    {
        return $this->hasMany(Murid::class);
    }
}
