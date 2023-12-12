<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Murid extends Model
{
    use HasFactory;

    protected $fillable = ['rayon_id', 'thn_id', 'user_id', 'mrd_id','tingkat', 'nik', 'nama', 'email', 'jns_klmin', 'alamat', 'tmpt', 'tgl', 'foto'];
   
    public static function getTingkatEnumValues()
    {
        $column = 'tingkat';

        $enumValues = Schema::getColumnType('murids', $column);
        preg_match('/^enum\((.*)\)$/', $enumValues, $matches);
        $enumValues = explode(',', $matches[1]);
        $enumValues = array_map(fn($value) => trim($value, "'"), $enumValues);

        return $enumValues;
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function rayon()
    {
        return $this->belongsTo(Rayon::class, 'rayon_id');
    }
    public function tahun()
    {
        return $this->belongsTo(Tahun::class, 'thn_id');
    }
    public function tagihans()
    {
        return $this->hasMany(Tagihan::class);
    }

}
