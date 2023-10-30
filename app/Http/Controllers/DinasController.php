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
        $cek_sp = DB::select("SELECT id_dinas, no_sp FROM dinas WHERE keterangan='Dinas Luar' GROUP BY no_sp DESC");
        if (!$cek_sp){
            $data['no_sp'] = 1;
        } else {
            $data['no_sp'] = $cek_sp[0]->no_sp+1;            
        }
        return view('dinas.dinas-luar-add', $data);
    }

    public function edit_dl($no_sp){
        $no_sp = decrypt($no_sp);
        $data['judul'] = 'Dinas Luar';
        $data['sub_judul'] = 'Ubah Dinas Luar';
        $data['pegawai'] = DB::select("SELECT * FROM pegawai");
        $data['dinas'] = DB::select("SELECT dinas.*, GROUP_CONCAT(DISTINCT pegawai.id_pegawai SEPARATOR ',') as pegawai_id, GROUP_CONCAT(DISTINCT dinas.id_dinas SEPARATOR ',') as dinas_id FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai JOIN users ON dinas.user_id=users.id_user WHERE no_sp='$no_sp' LIMIT 1");

        return view('dinas.dinas-luar-edit', $data);
    }

    public function data_dl(Request $request)
    {
        if ($request->ajax()) {
            $user_id = Auth::user()->id_user;
            // return $user;
            if(Auth::user()->role == 'user'){
                $data = DB::select("SELECT dinas.*, users.name AS user, GROUP_CONCAT(DISTINCT nama SEPARATOR '- ') as nama FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai JOIN users ON dinas.user_id=users.id_user WHERE keterangan='Dinas Luar' AND YEAR( tanggal)=YEAR(NOW()) GROUP BY no_sp, kegiatan ORDER BY tanggal DESC");
            } else if(Auth::user()->role == 'super' || Auth::user()->role == 'admin') {
                $data = DB::select("SELECT dinas.*, users.name AS user, GROUP_CONCAT(DISTINCT nama SEPARATOR '- ') as nama FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai JOIN users ON dinas.user_id=users.id_user WHERE keterangan='Dinas Luar' AND YEAR( tanggal)=YEAR(NOW()) GROUP BY no_sp, kegiatan ORDER BY tanggal DESC");
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
                    <button class="btn btn-danger btn-sm mt-1 hapus" data-no_sp="'.encrypt($row->no_sp).'"
                    ><i class="las la-trash-alt fs-3"></i> Hapus</button>';
                    if ($row->user_id == Auth::user()->id_user){
                        return $actionBtn;
                    } else {
                        return '';
                    }
                })
                ->editColumn('nama', function($row){
                    $nama = str_replace('-', '<br> >', $row->nama);
                    return '>'.$nama;
                })
                ->editColumn('no_sp', function($row){
                    $no_sp = '090/LD/'.$row->no_sp.'/'.bulan_romawi($row->bulan_input).'/'.date('Y', strtotime($row->tanggal));
                    return $no_sp;
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
            $data[$i]->bulan_input = date('m');
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
        return $array_dinas;
        for ($i=0; $i < count($array_dinas); $i++) { 
            Dinas::destroy($array_dinas[1]);

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
            $data[$i]->keterangan = 'Dinas Dalam';
            $data[$i]->user_id = Auth::user()->id_user;
            $data[$i]->bulan_input = date('m');
            $data[$i]->transportasi = $request->transportasi;
            $data[$i]->jam = $request->jam;
            $data[$i]->save();
            // echo $request->pegawai_id[$i];
        }

        return redirect()->route('dl')->with('success','Data berhasil disimpan.');
    }

    
    public function delete_dl($no_sp){
        $no_sp = decrypt($no_sp);
        DB::table('dinas')
            ->where('keterangan', '=', 'Dinas Luar')
            ->where('no_sp', '=', $no_sp)
            ->delete();
    }

    public function cek_dinas($pegawai_id, $tgl, $tgl_pulang){
        // return 'hmmmm';
        $dinas = DB::select("SELECT * FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai WHERE pegawai_id='$pegawai_id' AND tanggal BETWEEN '$tgl' AND '$tgl_pulang'");
        // return $dinas;
        if($dinas){
            $data['pegawai'] = 1;
            $data['pegawai_id'] = $dinas[0]->id_pegawai;
            $data['pesan'] = 'Maaf! Pegawai atas nama '.$dinas[0]->nama.' sedang melakukan '.$dinas[0]->keterangan.' pada tanggal '.tanggal_indo($dinas[0]->tanggal).' di '.$dinas[0]->tujuan;
        } else {
            $data['pegawai'] = 0;
        }
        return response()->json($data, 200);
    }

    public function cek_dinas2($pegawai_id, $tgl, $tgl_pulang, $no_sp){
        $array_pegawai = explode(',', $pegawai_id);
        for ($i=0; $i < count($array_pegawai); $i++) { 
            $dinas = DB::select("SELECT * FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai WHERE pegawai_id='$array_pegawai[$i]' AND no_sp != '$no_sp' AND tanggal BETWEEN '$tgl' AND '$tgl_pulang'");
            // return $dinas;
            if($dinas){
                $data['pegawai'] = 1;
                $data['pegawai_id'] = $dinas[0]->id_pegawai;
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

    public function dinas_dalam(){
        $data['judul'] = 'Dinas Dalam';
        $data['sub_judul'] = 'Data Dinas Dalam';
        return view('dinas.dinas-dalam', $data);
    }

    public function add_dd(){
        $data['judul'] = 'Dinas Dalam';
        $data['sub_judul'] = 'Tambah Dinas Dalam';
        $data['pegawai'] = DB::select("SELECT * FROM pegawai");
        $cek_sp = DB::select("SELECT id_dinas, no_sp FROM dinas WHERE keterangan='Dinas Dalam' GROUP BY no_sp DESC");
        if (!$cek_sp){
            $data['no_sp'] = 1;
        } else {
            $data['no_sp'] = $cek_sp[0]->no_sp+1;            
        }
        // return $cek_sp;
        return view('dinas.dinas-dalam-add', $data);
    }

    public function edit_dd($no_sp){
        $no_sp = decrypt($no_sp);
        $data['judul'] = 'Dinas Dalam';
        $data['sub_judul'] = 'Ubah Dinas Dalam';
        $data['pegawai'] = DB::select("SELECT * FROM pegawai");
        $data['dinas'] = DB::select("SELECT dinas.*, GROUP_CONCAT(DISTINCT pegawai.id_pegawai SEPARATOR ',') as pegawai_id, GROUP_CONCAT(DISTINCT dinas.id_dinas SEPARATOR ',') as dinas_id FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai JOIN users ON dinas.user_id=users.id_user WHERE no_sp='$no_sp' LIMIT 1");

        return view('dinas.dinas-dalam-edit', $data);
    }

    public function data_dd(Request $request)
    {
        // if ($request->ajax()) {
            $user_id = Auth::user()->id_user;
            // return $user_id;
            if(Auth::user()->role == 'user'){
                $data = DB::select("SELECT dinas.*, users.name AS user, GROUP_CONCAT(DISTINCT nama SEPARATOR '- ') as nama FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai JOIN users ON dinas.user_id=users.id_user WHERE keterangan='Dinas Dalam' AND YEAR( tanggal)=YEAR(NOW()) GROUP BY no_sp, kegiatan ORDER BY tanggal DESC");
            } else if(Auth::user()->role == 'super') {
                $data = DB::select("SELECT dinas.*, users.name AS user, GROUP_CONCAT(DISTINCT nama SEPARATOR '- ') as nama FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai JOIN users ON dinas.user_id=users.id_user WHERE keterangan='Dinas Dalam' AND YEAR( tanggal)=YEAR(NOW()) GROUP BY no_sp, kegiatan ORDER BY tanggal DESC");
            }
            // return $data;
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                    <a href="'.url('/dinas-dalam/edit').'/'.encrypt($row->no_sp).'" class="btn btn-success btn-sm mt-1" 
                    ><i class="las la-pencil-alt fs-3"></i> Ubah</a>
                    <button class="btn btn-info btn-sm mt-1" 
                    ><i class="las la-cloud-download-alt fs-3"></i> SPPD</button>
                    <button class="btn btn-info btn-sm mt-1" 
                    ><i class="las la-cloud-download-alt fs-3"></i> SP</button>
                    <button class="btn btn-danger btn-sm mt-1 hapus" data-no_sp="'.encrypt($row->no_sp).'"
                    ><i class="las la-trash-alt fs-3"></i> Hapus</button>';
                    if ($row->user_id == Auth::user()->id_user){
                        return $actionBtn;
                    } else {
                        return '';
                    }
                })
                ->editColumn('nama', function($row){
                    $nama = str_replace('-', '<br> >', $row->nama);
                    return '>'.$nama;
                })
                ->editColumn('no_sp', function($row){
                    $no_sp = '090/LD/'.$row->no_sp.'/'.bulan_romawi($row->bulan_input).'/'.date('Y', strtotime($row->tanggal));
                    return $no_sp;
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
        // }
    }

    public function create_dd(Request $request){
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
            $data[$i]->keterangan = 'Dinas Dalam';
            $data[$i]->user_id = Auth::user()->id_user;
            $data[$i]->bulan_input = date('m');
            $data[$i]->transportasi = $request->transportasi;
            $data[$i]->jam = $request->jam;
            $data[$i]->save();
            // echo $request->pegawai_id[$i];
        }

        return redirect()->route('dd')->with('success','Data berhasil disimpan.');
    }

    public function update_dd(Request $request){
        $id_dinas = $request->id_dinas;
        $array_dinas = explode(',', $id_dinas);
        for ($i=0; $i < count($array_dinas); $i++) { 
            Dinas::destroy($array_dinas[1]);
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
            $data[$i]->keterangan = 'Dinas Dalam';
            $data[$i]->user_id = Auth::user()->id_user;
            $data[$i]->bulan_input = date('m');
            $data[$i]->transportasi = $request->transportasi;
            $data[$i]->jam = $request->jam;
            $data[$i]->save();
            // echo $request->pegawai_id[$i];
        }

        return redirect()->route('dd')->with('success','Data berhasil disimpan.');
    }

    public function delete_dd($no_sp){
        $no_sp = decrypt($no_sp);
        DB::table('dinas')
            ->where('keterangan', '=', 'Dinas Dalam')
            ->where('no_sp', '=', $no_sp)
            ->delete();
    }

}
