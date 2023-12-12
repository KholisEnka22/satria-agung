<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Murid;
use App\Models\Pelatih;
use App\Models\Rayon;
use App\Models\Tahun;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'jumlahMurid' => Murid::count(),
            'jumlahPelatih' => Pelatih::count(),
            'jumlahRayon' => Rayon::count(),
            'tahunAjaran' => Tahun::all()
        ];
        return view('admin.home', $data);
    }
    // Controller di Laravel
    public function getData()
    {

        $data = Murid::selectRaw('thn_id, SUM(CASE WHEN jns_klmin = "Laki-laki" THEN 1 ELSE 0 END) as male_count, SUM(CASE WHEN jns_klmin = "Perempuan" THEN 1 ELSE 0 END) as female_count')
            ->groupBy('thn_id')
            ->get();


        return response()->json($data);
    }
}
