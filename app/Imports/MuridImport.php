<?php

namespace App\Imports;

use App\Models\Murid;
use App\Models\Rayon;
use App\Models\Tahun;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MuridImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Temukan ID Rayon berdasarkan nama
        $rayon = Rayon::where('nama_rayon', $row['rayon_id'])->first();
        $rayonId = $rayon ? $rayon->id : null;

        // Temukan ID Tahun berdasarkan tahun pertama atau tahun kedua
        $tahun = Tahun::where('tahun_pertama', $row['thn_id'])->orWhere('tahun_kedua', $row['thn_id'])->first();
        $tahunId = $tahun ? $tahun->id : null;

        // Membuat ID Murid Otomatis
        $murid = Murid::latest()->first();
        $nubRow = $murid ? ((int)substr($murid->mrd_id, -3) + 1) : 1;
        $year = date('Y');
        $formattedNubRow = str_pad($nubRow, 3, '0', STR_PAD_LEFT);

        $mrd_id = 'PNSA' . '-' . $year . '-' . $rayonId . "-" . $formattedNubRow;

        $user = User::create([
            'name' => $row['nama'],
            'email' => $row['email'],
            'password' => bcrypt($row['tgl']),
            'remember_token' => Str::random(10),
            'role' => 'murid', // Berikan nilai default untuk kolom 'role'
        ]);
        

        // Return objek Murid yang baru dibuat
        return new Murid([
            'rayon_id' => $rayonId,
            'thn_id' => $tahunId,
            'user_id' => $user->id,
            'mrd_id' => $mrd_id,
            'nik' => $row['nik'],
            'nama' => $row['nama'],
            'jns_klmin' => $row['jns_klmin'],
            'email' => $row['email'],
            'foto' => 'default.png',
            'tingkat' => $row['tingkat'],
            'alamat' => $row['alamat'],
            'tmpt' => $row['tmpt'],
            'tgl' => $row['tgl'],
            'is_pelatih' => 0
        ]);
    }
}