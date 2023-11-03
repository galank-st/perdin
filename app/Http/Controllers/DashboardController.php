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
        $data['bulan'] = [
            1 => "Januari",
            2 => "Februari",
            3 => "Maret",
            4 => "April",
            5 => "Mei",
            6 => "Juni",
            7 => "Juli",
            8 => "Agustus",
            9 => "September",
            10 => "Oktober",
            11 => "November",
            12 => "Desember"
        ];

        return view('dashboard', $data);
    }

    public function rekap(){
        $bulan = date('m');
        $tahun = date('Y');
        $data['jml_hari'] = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        $data['judul'] = "Rekap";
        $data['sub_judul'] = "Rekap Data";
        $data['bulan'] = [
            1 => "Januari",
            2 => "Februari",
            3 => "Maret",
            4 => "April",
            5 => "Mei",
            6 => "Juni",
            7 => "Juli",
            8 => "Agustus",
            9 => "September",
            10 => "Oktober",
            11 => "November",
            12 => "Desember"
        ];

        $data['bulan_now'] = $bulan;

        $data['dinas'] = DB::select("SELECT id_pegawai, nama, nip FROM pegawai JOIN jabatan ON pegawai.jabatan_id=jabatan.id_jabatan ORDER BY id_jabatan");
        foreach ($data['dinas'] as $p) {
            // $dinas = DB::select("SELECT keterangan, DAY(tanggal) tgl, DAY(tanggal_pulang) tgl_p FROM dinas WHERE pegawai_id='4'");
            $dinas = DB::select("SELECT keterangan, DAY(tanggal) tgl, DAY(tanggal_pulang) tgl_p FROM dinas WHERE pegawai_id='$p->id_pegawai' AND MONTH(tanggal)='$bulan'");
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

    public function rekapByBulan($bulan){
        $tahun = date('Y');
        $data['jml_hari'] = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        $data['judul'] = "Rekap";
        $data['sub_judul'] = "Rekap Data";
        $data['bulan'] = [
            1 => "Januari",
            2 => "Februari",
            3 => "Maret",
            4 => "April",
            5 => "Mei",
            6 => "Juni",
            7 => "Juli",
            8 => "Agustus",
            9 => "September",
            10 => "Oktober",
            11 => "November",
            12 => "Desember"
        ];
        $data['bulan_now'] = $bulan;

        $data['dinas'] = DB::select("SELECT id_pegawai, nama, nip FROM pegawai JOIN jabatan ON pegawai.jabatan_id=jabatan.id_jabatan ORDER BY id_jabatan");
        foreach ($data['dinas'] as $p) {
            // $dinas = DB::select("SELECT keterangan, DAY(tanggal) tgl, DAY(tanggal_pulang) tgl_p FROM dinas WHERE pegawai_id='4'");
            $dinas = DB::select("SELECT keterangan, DAY(tanggal) tgl, DAY(tanggal_pulang) tgl_p FROM dinas WHERE pegawai_id='$p->id_pegawai' AND MONTH(tanggal)='$bulan'");
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
    public function grafikBulanan(){
        $data= [
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0
        ];

        $grafik = DB::select("SELECT MONTH(tanggal) bulan, COUNT(id_dinas) jml FROM dinas d GROUP BY MONTH(tanggal) ORDER BY MONTH(tanggal)");
        foreach ($grafik as $g) {
            if(isset($g->jml)){
                $data[$g->bulan-1]= $g->jml;
            }
        }
        return response()->json($data, 200);
    }

    public function grafikHarian($bulan){
        $tahun = date('Y');
        $jml_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        $data['hari'] = [];
        for ($x=0; $x < $jml_hari ; $x++) { 
            $data['hari'][$x] = $x+1;
        }

        $dinas = DB::select("SELECT DAY(tanggal) tgl, COUNT(id_dinas) jml FROM dinas WHERE MONTH(tanggal)='$bulan' GROUP BY tgl");

        $data['dinas'] = [];
        for ($x=0; $x <= $jml_hari ; $x++) { 
            $data['dinas'][$x] = 0;
        }

        foreach ($dinas as $d) {
            if(isset($d->jml)){
                $data['dinas'][$d->tgl-1]= $d->jml;
            }
        }

        $grafik = DB::select("SELECT MONTH(tanggal) bulan, COUNT(id_dinas) jml FROM dinas d GROUP BY MONTH(tanggal) ORDER BY MONTH(tanggal)");
        foreach ($grafik as $g) {
            if(isset($g->jml)){
                $data[$g->bulan]= $g->jml;
            }
        }
        return response()->json($data, 200);
    }
}
