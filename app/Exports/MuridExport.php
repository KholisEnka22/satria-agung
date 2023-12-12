<?php

namespace App\Exports;

use App\Models\Murid;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MuridExport implements FromCollection, WithHeadings,ShouldAutoSize,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Murid::select(
            'mrd_id',
            'nama',
            'email',
            'nik',
            'jns_klmin',
            'tingkat',
            'alamat',
            'tmpt',
            'tgl',
            'is_pelatih'
        )->get()->map(function ($murid) {
            $murid->is_pelatih = $murid->is_pelatih == 1 ? 'Pelatih' : 'Bukan Pelatih';
            return $murid;
        });
    }

    public function headings(): array
    {
        return [
            'ID Murid',
            'Nama',
            'Email',
            'NIK',
            'Jenis Kelamin',
            'Tingkat',
            'Alamat',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Status Pelatih',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            'A1:J1' => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFFF00']]],
        ];
    }
}
