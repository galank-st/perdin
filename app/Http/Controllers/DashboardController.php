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
        $data['jml_hari'] = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        $data['judul'] = "Rekap";
        $data['sub_judul'] = "Rekap Data";
        $data['dinas'] = DB::select("SELECT id_pegawai, nama, nip FROM pegawai");
        foreach ($data['dinas'] as $p) {
            // $dinas = DB::select("SELECT keterangan, DAY(tanggal) tgl, DAY(tanggal_pulang) tgl_p FROM dinas WHERE pegawai_id='4'");
            $dinas = DB::select("SELECT keterangan, DAY(tanggal) tgl, DAY(tanggal_pulang) tgl_p FROM dinas WHERE pegawai_id='$p->id_pegawai'");
            // return $dinas[0]->tgl;
            $p->dinas = [];
            for ($i=0; $i < $data['jml_hari']; $i++) { 
                $a['x'.$i+1] = "0";

            }

            foreach ($dinas as $d) {
                $a['x'.$d->tgl] = $d->keterangan;
                $a['x'.$d->tgl_p] = $d->keterangan;
                $sel = $d->tgl_p-$d->tgl;
                if($sel != 0) {
                    for ($x=1; $x < $sel; $x++) { 
                        if ($d->tgl+1 <= $d->tgl_p){
                            $a['x'.$d->tgl+$x] = $d->keterangan;
                        }
                    }
                }
            }

            array_push($p->dinas, (object) $a);
        }       
        // return $data['dinas'];
        // return $data['dinas'][0]->dinas[0]->x1;
        return view('rekap.rekap', $data);
    }
}
