<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;


class DinasController extends Controller
{
    public function dinas_luar(){
        $data['judul'] = 'Dinas Luar';
        $data['sub_judul'] = 'Data Dinas Luar';
        return view('dinas.dinas-luar', $data);
    }

    public function add_dl(){
        $data['judul'] = 'Dinas Luar';
        $data['sub_judul'] = 'Tambah Dinas Luar';
        $data['pegawai'] = DB::select("SELECT * FROM pegawai");
        $cek_sp = DB::select("SELECT id_dinas FROM dinas GROUP BY no_sp");
        $data['no_sp'] = count($cek_sp)+1;
        return view('dinas.dinas-luar-add', $data);
    }

    public function edit_dl($no_sp){
        $no_sp = decrypt($no_sp);
        $data['judul'] = 'Dinas Luar';
        $data['sub_judul'] = 'Ubah Dinas Luar';
        $data['pegawai'] = DB::select("SELECT * FROM pegawai");
        $data['dinas'] = DB::select("SELECT dinas.*, GROUP_CONCAT(DISTINCT pegawai.id SEPARATOR ',') as pegawai_id, GROUP_CONCAT(DISTINCT dinas.id_dinas SEPARATOR ',') as dinas_id FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id JOIN users ON dinas.user_id=users.id_user WHERE no_sp='$no_sp' LIMIT 1");

        return view('dinas.dinas-luar-edit', $data);
    }

    public function data_dl(Request $request)
    {
        if ($request->ajax()) {
            $user_id = Auth::user()->id_user;
            // return $user;
            if(Auth::user()->role == 'user'){
                $data = DB::select("SELECT dinas.*, users.name AS user, GROUP_CONCAT(DISTINCT nama SEPARATOR '- ') as nama FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id JOIN users ON dinas.user_id=users.id_user WHERE user_id = '$user_id' AND YEAR( tanggal)=YEAR(NOW()) GROUP BY no_sp, kegiatan ORDER BY tanggal DESC");
            } else if(Auth::user()->role == 'super') {
                $data = DB::select("SELECT * FROM dinas GROUP BY no_sp");
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                    <a href="'.url('/dinas-luar/edit').'/'.encrypt($row->no_sp).'" class="btn btn-success btn-sm mt-1" 
                    ><i class="las la-pencil-alt fs-3"></i> Ubah</a>
                    <button class="btn btn-info btn-sm mt-1" 
                    ><i class="las la-cloud-download-alt fs-3"></i> SPPD</button>
                    <button class="btn btn-info btn-sm mt-1" 
                    ><i class="las la-cloud-download-alt fs-3"></i> SP</button>
                    <button class="btn btn-danger btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#modalUbah" 
                    ><i class="las la-trash-alt fs-3"></i> Hapus</button>';
                    return $actionBtn;
                })
                ->editColumn('nama', function($row){
                    $nama = str_replace('-', '<br> >', $row->nama);
                    return '>'.$nama;
                })
                ->editColumn('tanggal', function($row){
                    $tanggal = tanggal_indo($row->tanggal);
                    return $tanggal;
                })
                ->editColumn('tanggal_pulang', function($row){
                    $tanggal_pulang = tanggal_indo($row->tanggal_pulang);
                    return $tanggal_pulang;
                })
                ->rawColumns(['action','nama'])
                ->make(true);
        }
    }

    public function create_dl(Request $request){
        $request->validate([
            'pegawai_id' => 'required',
            'no_sp' => 'required',
            'tanggal' => 'required|date',
            'tujuan' => 'required',
            'kegiatan' => 'required',
            'transportasi' => 'required',
            'jam' => 'required',

        ]);

        for ($i=0; $i < count($request->pegawai_id); $i++) { 
            $data[$i] = new Dinas();
            $data[$i]->pegawai_id = $request->pegawai_id[$i];
            $data[$i]->no_sp = $request->no_sp;
            $data[$i]->tanggal = $request->tanggal;
            if($request->tanggal_pulang){
                $data[$i]->tanggal_pulang = $request->tanggal_pulang;
            } else {
                $data[$i]->tanggal_pulang = $request->tanggal;
            }
            $data[$i]->tujuan = $request->tujuan;
            $data[$i]->kegiatan = $request->kegiatan;
            $data[$i]->keterangan = 'Dinas Luar';
            $data[$i]->user_id = Auth::user()->id_user;
            $data[$i]->bulan_input = now('M');
            $data[$i]->transportasi = $request->transportasi;
            $data[$i]->jam = $request->jam;
            $data[$i]->save();
            // echo $request->pegawai_id[$i];
        }

        return redirect()->route('dl')->with('success','Data berhasil disimpan.');
    }

    public function update_dl(Request $request){
        $id_dinas = $request->id_dinas;
        $array_dinas = explode(',', $id_dinas);
        // return $array_dinas;
        for ($i=0; $i < count($array_dinas); $i++) { 
            Dinas::destroy($request->id_dinas[$i]);
        }

        for ($i=0; $i < count($request->pegawai_id); $i++) { 
            $data[$i] = new Dinas();
            $data[$i]->pegawai_id = $request->pegawai_id[$i];
            $data[$i]->no_sp = $request->no_sp;
            $data[$i]->tanggal = $request->tanggal;
            if($request->tanggal_pulang){
                $data[$i]->tanggal_pulang = $request->tanggal_pulang;
            } else {
                $data[$i]->tanggal_pulang = $request->tanggal;
            }
            $data[$i]->tujuan = $request->tujuan;
            $data[$i]->kegiatan = $request->kegiatan;
            $data[$i]->keterangan = 'Dinas Luar';
            $data[$i]->user_id = Auth::user()->id_user;
            $data[$i]->bulan_input = now('M');
            $data[$i]->transportasi = $request->transportasi;
            $data[$i]->jam = $request->jam;
            $data[$i]->save();
            // echo $request->pegawai_id[$i];
        }

        return redirect()->route('dl')->with('success','Data berhasil disimpan.');
    }

    public function cek_dinas($pegawai_id, $tgl, $tgl_pulang){
        // return 'hmmmm';
        $dinas = DB::select("SELECT * FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id WHERE pegawai_id='$pegawai_id' AND tanggal BETWEEN '$tgl' AND '$tgl_pulang'");
        if($dinas){
            $data['pegawai'] = 1;
            $data['pegawai_id'] = $dinas[0]->id;
            $data['pesan'] = 'Maaf! Pegawai atas nama '.$dinas[0]->nama.' sedang melakukan '.$dinas[0]->keterangan.' pada tanggal '.tanggal_indo($dinas[0]->tanggal).' di '.$dinas[0]->tujuan;
        } else {
            $data['pegawai'] = 0;
        }
        return response()->json($data, 200);
    }

    public function cek_dinas2($pegawai_id, $tgl, $tgl_pulang, $no_sp){
        $array_pegawai = explode(',', $pegawai_id);
        for ($i=0; $i < count($array_pegawai); $i++) { 
            $dinas = DB::select("SELECT * FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id WHERE pegawai_id='$array_pegawai[$i]' AND no_sp != '$no_sp' AND tanggal BETWEEN '$tgl' AND '$tgl_pulang'");
            // return $dinas;
            if($dinas){
                $data['pegawai'] = 1;
                $data['pegawai_id'] = $dinas[0]->id;
                $data['pesan'] = 'Maaf! Pegawai atas nama '.$dinas[0]->nama.' sedang melakukan '.$dinas[0]->keterangan.' pada tanggal '.tanggal_indo($dinas[0]->tanggal).' di '.$dinas[0]->tujuan;
            } else {
                $data['pegawai'] = 0;
            }
        }
        return response()->json($data, 200);
    }
    public function cek_no_sp($no_sp){
        // return 'hmmmm';
        $dinas = DB::select("SELECT * FROM dinas WHERE no_sp='$no_sp'");
        if($dinas){
            $data['no_sp'] = 1;
        } else {
            $data['no_sp'] = 0;
        }
        return response()->json($data, 200);
    }
}
