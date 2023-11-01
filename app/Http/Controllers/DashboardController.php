<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        $data['judul'] = "Dashboard";
        $data['sub_judul'] = "Dashboard";
        $data['dl_bidang'] = DB::select("SELECT bidang, COUNT(id_dinas) jml FROM bidang b JOIN pegawai p ON b.id_bidang=p.bidang_id JOIN dinas d ON p.id_pegawai=d.pegawai_id WHERE d.keterangan='Dinas Luar' GROUP BY id_bidang ORDER BY jml DESC");
        $data['dd_bidang'] = DB::select("SELECT bidang, COUNT(id_dinas) jml FROM bidang b JOIN pegawai p ON b.id_bidang=p.bidang_id JOIN dinas d ON p.id_pegawai=d.pegawai_id WHERE d.keterangan='Dinas Dalam' GROUP BY id_bidang ORDER BY jml DESC");
        $data['dl_pegawai'] = DB::select("SELECT nama, COUNT(id_dinas) jml FROM pegawai p JOIN dinas d ON p.id_pegawai=d.pegawai_id WHERE d.keterangan='Dinas Luar' GROUP BY id_pegawai ORDER BY jml DESC LIMIT 10");
        $data['dd_pegawai'] = DB::select("SELECT nama, COUNT(id_dinas) jml FROM pegawai p JOIN dinas d ON p.id_pegawai=d.pegawai_id WHERE d.keterangan='Dinas Dalam' GROUP BY id_pegawai ORDER BY jml DESC LIMIT 10");

        return view('dashboard', $data);
    }

    public function rekap(){
        $tanggalAwal = "2023-10-01";
        $tanggalAkhir = date('Y-m-t', strtotime($tanggalAwal));

        $pegawai = Pegawai::all();
        $dataKehadiran = [];

        foreach ($pegawai as $pg) {
            $dataKehadiran[$pg->id_pegawai] = Dinas::where('pegawai_id', $pg->id_pegawai)
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->select('tanggal', 'keterangan')
                ->orderBy('tanggal')
                ->get();
        }

        return $dataKehadiran;
        }
}
