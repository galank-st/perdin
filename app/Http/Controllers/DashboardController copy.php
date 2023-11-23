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
        $bulan = 10;
        $tahun = date('Y');
        $opd_id = Auth::user()->opd_id;
        $data['jml_hari'] = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        $data['judul'] = "Rekap";
        $data['sub_judul'] = "Rekap Data";
        $data['dinas'] = DB::select("SELECT id_pegawai, nama, nip FROM pegawai WHERE pegawai.opd_id='$opd_id'");
        // return $data['dinas'];
        foreach ($data['dinas'] as $p) {
            $dinas = DB::select("SELECT keterangan, DAY(tanggal) tgl, DAY(tanggal_pulang) tgl_p FROM dinas WHERE pegawai_id='$p->id_pegawai'  AND pegawai.opd_id='$opd_id'");
            $selisih = $dinas[0]->tgl-$dinas[0]->tgl_p;
            $p->dinas = [];
            for ($i=0; $i < $data['jml_hari']; $i++) { 
                // return $dinas[$i]->tgl;
                if(isset($dinas[$i]->tgl) && $dinas[$i]->tgl=$i+1) {
                    $a['x'.$i+1] = $dinas[$i]->keterangan;
                } else {
                    $a['x'.$i+1] = "0";
                }
            }
            array_push($p->dinas, (object) $a);
        }       
        return $data['dinas'];
        // return $data['dinas'][0]->dinas[0]->x1;
        return view('rekap.rekap', $data);
    }
}
