<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Dinas;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\Shared\Html;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\SimpleType\Jc;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;


class DinasController extends Controller
{
    public function dinas_luar(){
        $data['judul'] = 'Dinas Luar';
        $data['sub_judul'] = 'Data Dinas Luar';
        return view('dinas.dinas-luar', $data);
    }

    public function add_dl(){
        $opd_id = Auth::user()->opd_id;
        $data['judul'] = 'Dinas Luar';
        $data['sub_judul'] = 'Tambah Dinas Luar';
        $data['pegawai'] = DB::select("SELECT * FROM pegawai WHERE opd_id='$opd_id'");
        $cek_sp = DB::select("SELECT id_dinas, no_sp FROM dinas WHERE keterangan='Dinas Luar' GROUP BY no_sp DESC");
        if (!$cek_sp){
            $data['no_sp'] = 1;
        } else {
            $data['no_sp'] = $cek_sp[0]->no_sp+1;            
        }
        return view('dinas.dinas-luar-add', $data);
    }

    public function edit_dl($no_sp){
        $opd_id = Auth::user()->opd_id;
        $no_sp = decrypt($no_sp);

        $data['judul'] = 'Dinas Luar';
        $data['sub_judul'] = 'Ubah Dinas Luar';
        $data['pegawai'] = DB::select("SELECT * FROM pegawai WHERE opd_id='$opd_id'");
        $data['dinas'] = DB::select("SELECT dinas.*, GROUP_CONCAT(DISTINCT pegawai.id_pegawai SEPARATOR ',') as pegawai_id, GROUP_CONCAT(DISTINCT dinas.id_dinas SEPARATOR ',') as dinas_id FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai JOIN users ON dinas.user_id=users.id_user WHERE no_sp='$no_sp' LIMIT 1");

        return view('dinas.dinas-luar-edit', $data);
    }

    public function data_dl(Request $request)
    {
        if ($request->ajax()) {
            $user_id = Auth::user()->id_user;
            $opd_id = Auth::user()->opd_id;
            // return $user;
            if(Auth::user()->role == 'user'){
                $data = DB::select("SELECT dinas.*, users.name AS user, GROUP_CONCAT(DISTINCT nama SEPARATOR '- ') as nama FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai JOIN users ON dinas.user_id=users.id_user WHERE keterangan='Dinas Luar' AND YEAR( tanggal)=YEAR(NOW()) AND dinas.opd_id='$opd_id' GROUP BY no_sp, kegiatan ORDER BY tanggal DESC");
            } else if(Auth::user()->role == 'admin') {
                $data = DB::select("SELECT dinas.*, users.name AS user, GROUP_CONCAT(DISTINCT nama SEPARATOR '- ') as nama FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai JOIN users ON dinas.user_id=users.id_user WHERE keterangan='Dinas Luar' AND YEAR( tanggal)=YEAR(NOW()) AND dinas.opd_id='$opd_id' GROUP BY no_sp, kegiatan ORDER BY tanggal DESC");
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                    <a href="'.url('/dinas-luar/edit').'/'.encrypt($row->no_sp).'" class="btn btn-success btn-sm mt-1" 
                    ><i class="las la-pencil-alt fs-3"></i> Ubah</a>
                    <button class="btn btn-info btn-sm mt-1 sppd" data-id="'.$row->id_dinas.'" data-no_sp="'.$row->no_sp.'" data-keterangan="'.$row->keterangan.'" data-bs-toggle="modal" data-bs-target="#modalSppd"><i class="las la-cloud-download-alt fs-3"></i> SPPD</button>
                    <button class="btn btn-info btn-sm mt-1 sp" data-no_sp="'.$row->no_sp.'" data-keterangan="'.$row->keterangan.'" data-id="'.$row->id_dinas.'" data-bs-toggle="modal" data-bs-target="#modalSp" 
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

        if (count($array_dinas) == 1) {
            Dinas::destroy($request->id_dinas[0]);
        } else {
            for ($i=0; $i < count($array_dinas); $i++) { 
                Dinas::destroy($array_dinas[$i]);
            }
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
        $opd_id = Auth::user()->opd_id;

        $data['judul'] = 'Dinas Dalam';
        $data['sub_judul'] = 'Tambah Dinas Dalam';
        $data['pegawai'] = DB::select("SELECT * FROM pegawai WHERE opd_id='$opd_id'");
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
        $opd_id = Auth::user()->opd_id;
        $no_sp = decrypt($no_sp);

        $data['judul'] = 'Dinas Dalam';
        $data['sub_judul'] = 'Ubah Dinas Dalam';
        $data['pegawai'] = DB::select("SELECT * FROM pegawai  WHERE opd_id='$opd_id'");
        $data['dinas'] = DB::select("SELECT dinas.*, GROUP_CONCAT(DISTINCT pegawai.id_pegawai SEPARATOR ',') as pegawai_id, GROUP_CONCAT(DISTINCT dinas.id_dinas SEPARATOR ',') as dinas_id FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai JOIN users ON dinas.user_id=users.id_user WHERE no_sp='$no_sp' LIMIT 1");

        return view('dinas.dinas-dalam-edit', $data);
    }

    public function data_dd(Request $request)
    {
        // if ($request->ajax()) {
            $user_id = Auth::user()->id_user;
            $opd_id = Auth::user()->opd_id;
            if(Auth::user()->role == 'user'){
                $data = DB::select("SELECT dinas.*, users.name AS user, GROUP_CONCAT(DISTINCT nama SEPARATOR '- ') as nama FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai JOIN users ON dinas.user_id=users.id_user WHERE keterangan='Dinas Dalam' AND YEAR( tanggal)=YEAR(NOW()) AND dinas.opd_id='$opd_id' GROUP BY no_sp, kegiatan ORDER BY tanggal DESC");
            } else if(Auth::user()->role == 'admin') {
                $data = DB::select("SELECT dinas.*, users.name AS user, GROUP_CONCAT(DISTINCT nama SEPARATOR '- ') as nama FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai JOIN users ON dinas.user_id=users.id_user WHERE keterangan='Dinas Dalam' AND YEAR( tanggal)=YEAR(NOW()) AND dinas.opd_id='$opd_id' GROUP BY no_sp, kegiatan ORDER BY tanggal DESC");
            }
            // return $data;
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                    <a href="'.url('/dinas-dalam/edit').'/'.encrypt($row->no_sp).'" class="btn btn-success btn-sm mt-1" 
                    ><i class="las la-pencil-alt fs-3"></i> Ubah</a>
                    <button class="btn btn-info btn-sm mt-1 sppd" data-no_sp="'.$row->no_sp.'" data-keterangan="'.$row->keterangan.'" data-id="'.$row->id_dinas.'" data-bs-toggle="modal" data-bs-target="#modalSppd"
                    ><i class="las la-cloud-download-alt fs-3"></i> SPPD</button>
                    <button class="btn btn-info btn-sm mt-1 sp" 
                    data-no_sp="'.$row->no_sp.'" data-keterangan="'.$row->keterangan.'" data-id="'.$row->id_dinas.'" data-bs-toggle="modal" data-bs-target="#modalSp"><i class="las la-cloud-download-alt fs-3"></i> SP</button>
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
                    $no_sp = '090/DD/'.$row->no_sp.'/'.bulan_romawi($row->bulan_input).'/'.date('Y', strtotime($row->tanggal));
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

        if (count($array_dinas) == 1) {
            Dinas::destroy($request->id_dinas[0]);
        } else {
            for ($i=0; $i < count($array_dinas); $i++) { 
                Dinas::destroy($array_dinas[$i]);
            }
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

    public function cek_pegawai($no_sp, $keterangan){
        // return 'hmmmm';
        $data = DB::select("SELECT * FROM dinas JOIN pegawai ON dinas.pegawai_id=pegawai.id_pegawai WHERE no_sp='$no_sp' AND keterangan='$keterangan'");
        return response()->json($data, 200);
    }

    public function sppd(Request $request)
    {
        $count_pegawai = count($request->pegawai_id);
        $dinas = Dinas::find($request->id_dinas);
        $opd_id = Auth::user()->opd_id;

        if($dinas->keterangan == 'Dinas Luar'){
            $no_sp = '090/LD/'.$dinas->no_sp.'/'.bulan_romawi($dinas->bulan_input).'/'.date('Y', strtotime($dinas->tanggal));
        } elseif ($dinas->keterangan == 'Dinas Dalam') {
            $no_sp = '090/DD/'.$dinas->no_sp.'/'.bulan_romawi($dinas->bulan_input).'/'.date('Y', strtotime($dinas->tanggal));
        }
        
        if ($count_pegawai == 1){
            $id_pegawai = $request->pegawai_id[0];
            $pegawai = DB::select("SELECT * FROM pegawai JOIN jabatan ON pegawai.jabatan_id=jabatan.id_jabatan WHERE id_pegawai = '$id_pegawai' AND pegawai.opd_id='$opd_id'");

        } else {
            $query = DB::table('pegawai');
            $query->join('jabatan', 'pegawai.jabatan_id','=','jabatan.id_jabatan');
            $query->where('pegawai.opd_id','=',$opd_id);
            for ($i=0; $i < $count_pegawai; $i++) {
                $query->orWhere('id_pegawai', '=', $request->pegawai_id[$i]);
            }
            $pegawai2 = $query->get();
        }
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $sectionStyle = $section->getStyle();
        $sectionStyle->setMarginLeft(900);
        $sectionStyle->setMarginRight(900);
        $sectionStyle->setMarginTop(400);

        $imagePath = public_path('assets/media/misc/kop.jpg'); 
        $imageData = base64_encode(file_get_contents($imagePath));

        $section->addImage(
            base64_decode($imageData),
            array(
                'width' => 500,
            )
        );

        $fontNumber = array('name' => 'Arial',
        'size' => '11');

        $font = array('name' => 'Arial',
            'size' => '12');

        $styleCell = array('valign'=>'center');
        //table untuk nomor dan lembar ke
        $tabl = $section->addTable(array('align' => 'center'));

        $tabl->setWidth('100%');

        $tabl->addRow(500);
        $tabl->addCell(7200, $styleCell)->addText('');
        $cel1 = $tabl->addCell(1400, $styleCell);
        $cel1->addText('Nomor', $fontNumber, array('spaceAfter' => 0));
        $cel1->addText('Lembar ke', $fontNumber);
        $cel2 = $tabl->addCell(150, $styleCell);
        $cel2->addText(':', $fontNumber, array('spaceAfter' => 0));
        $cel2->addText(':', $fontNumber);
        $cel3 = $tabl->addCell(2700, $styleCell);
        $cel3->addText($no_sp, $fontNumber, array('spaceAfter' => 0));
        $cel3->addText('1', $fontNumber);

        $section->addText('SURAT PERINTAH PERJALANAN DINAS', array(
            'bold' => true,
            'underline' => 'single',
            'name' => 'Arial',
            'size' => '12'
        ), array(
            'align' => 'center',
            'spaceAfter' => 0
        ));

        $section->addText('(S P P D)', array(
            'bold' => true,
            'name' => 'Arial',
            'size' => '12'
        ), array(
            'align' => 'center'
        ));

        $styleTable = array('cellMarginRight' => 80, 'cellMarginLeft' => 80, 'cellMarginTop' => 20);
        $phpWord->addTableStyle('myOwnTableStyle', $styleTable);
        $table = $section->addTable('myOwnTableStyle');
        $table->setWidth('100%');

        $cellLeft = array('borderTopSize' => 8, 'borderLeftSize' => 8);
        $cellTop = array('borderTopSize' => 8, 'borderRightSize' => 8);
        $cellRight = array('borderTopSize' => 8, 'borderRightSize' => 8);
        $cellTitik2 = array('borderTopSize' => 8);

        $cellLeftBot = array('borderTopSize' => 8, 'borderLeftSize' => 8, 'borderBottomSize' => 8);
        $cellTopBot = array('borderTopSize' => 8, 'borderRightSize' => 8, 'borderBottomSize' => 8);
        $cellRightBot = array('borderTopSize' => 8, 'borderRightSize' => 8, 'borderBottomSize' => 8);
        $cellTitik2Bot = array('borderTopSize' => 8, 'borderBottomSize' => 8);

        //Entry tabel 2, isi 8 cell
        $table->addRow();
        $table->addCell(250, $cellLeft)->addText('1.', $font);
        $table->addCell(4000, $cellTop)->addText('Pejabat yang memberi perintah', $font, array('align' => 'both'));
        $table->addCell(200, $cellTitik2)->addText(':', $font);
        $table->addCell(6000, $cellRight)->addText('Kepala Bappeda Litbang Kabupaten Pekalongan', $font, array('align' => 'center'));

        $table->addRow(900);
		$table->addCell(250, $cellLeft)->addText('2.', $font);
		$table->addCell(4000, $cellTop)->addText('Nama/ NIP Pegawai yang diperintahkan mengadakan perjalanan dinas', $font, array('align' => 'both'));
		$table->addCell(200, $cellTitik2)->addText(':', $font);
		$c2 = $table->addCell(6400, $cellRight);
        if($count_pegawai == 1){
            $c2->addText($pegawai[0]->nama.' | '.$pegawai[0]->nip, $font, array('align' => 'both','spacing' => 120,'spaceAfter' => 0));	
        } else {
            for ($i=0; $i < $count_pegawai; $i++) { 
                $x = $i+1;
                $c2->addText($x.'. '.$pegawai2[$i]->nama.' | '.$pegawai2[$i]->nip, $font, array('align' => 'both','spacing' => 120,'spaceAfter' => 0));	

            }
        }

        $table->addRow(900);
		$table->addCell(250, $cellLeft)->addText('3.', $font);
		$table->addCell(4000, $cellTop)->addText('Jabatan, Pangkat dan Golongan/ Ruang dari Pegawai yang diperintahkan', $font, array('align' => 'both'));
		$table->addCell(200, $cellTitik2)->addText(':', $font);
		$c3 = $table->addCell(6400, $cellRight);
        if($count_pegawai == 1){
            $c3->addText($pegawai[0]->jabatan.' Bappeda Litbang Kabupaten Pekalongan', $font, array('spaceAfter' => 0,'spacing' => 120));	
        } else {
            for ($i=0; $i < $count_pegawai; $i++) { 
                $x = $i+1;
                $c3->addText($x.'. '.$pegawai2[$i]->jabatan.' Bappeda Litbang Kabupaten Pekalongan', $font, array('spaceAfter' => 0,'spacing' => 120));	
            }
        }

        $table->addRow();
        $table->addCell(250, $cellLeft)->addText('4.', $font);
        $table->addCell(4000, $cellTop)->addText('Perjalanan dinas yang diperintahkan', $font, array('align' => 'both'));
        $table->addCell(200, $cellTitik2)->addText(':', $font);
        $c4 = $table->addCell(6400, $cellRight);
        $c4->addText('a. dari      : KAJEN', $font, array('spaceAfter' => 0,'spacing' => 120 ));
        $c4->addText('b. ke        : '.$dinas->tujuan, $font, array('spaceAfter' => 0,'spacing' => 120));
        $c4->addText('Transportasi menggunakan  : '.$dinas->transportasi, $font, array('spaceAfter' => 0,'spacing' => 120));

        $datetime1 = new DateTime($dinas->tanggal);
        $datetime2 = new DateTime($dinas->tanggal_pulang);
        $interval = $datetime1->diff($datetime2);
        $lama_hari = $interval->days;
        $table->addRow(900);
        $table->addCell(250, $cellLeft)->addText('5.', $font);
        $table->addCell(4000, $cellTop)->addText('Perjalanan Dinas direncanakan', $font, array('align' => 'both'));
        $table->addCell(200, $cellTitik2)->addText(':', $font);
        $c5 = $table->addCell(6400, $cellRight);
        $c5->addText('Selama ( '.$lama_hari.' ) hari', $font, array('spaceAfter' => 0,'spacing' => 120));
        $c5->addText('Dari tanggal  : '.tanggal_indo($dinas->tanggal), $font, array('spaceAfter' => 0,'spacing' => 120));
        $c5->addText('s/d tanggal     : '.tanggal_indo($dinas->tanggal_pulang), $font, array('spaceAfter' => 0,'spacing' => 120));

        $table->addRow();
		$table->addCell(250, $cellLeft)->addText('6.', $font);
		$table->addCell(4000, $cellTop)->addText('Maksud mengadakan perjalanan', $font, array('align' => 'both'));
		$table->addCell(200, $cellTitik2)->addText(':', $font);
		$table->addCell(6400, $cellRight)->addText($dinas->kegiatan, $font, array('spaceAfter' => 0));

		$table->addRow();
		$table->addCell(250, $cellLeft)->addText('7.', $font);
		$table->addCell(4000, $cellTop)->addText('Perhitungan biaya perjalanan', $font, array('align' => 'both'));
		$table->addCell(200, $cellTitik2)->addText(':', $font);
		$c7 = $table->addCell(6400, $cellRight);
        $c7->addText('Dibebankan pada APBD Kabupaten Pekalongan Tahun Anggaran 2023 ', $font);

        $table->addRow();
		$table->addCell(250, $cellLeftBot)->addText('8.', $font);
		$table->addCell(3500, $cellTopBot)->addText('Keterangan', $font);
		$table->addCell(200, $cellTitik2Bot)->addText(':', $font);
		$table->addCell(6700, $cellRightBot)->addText('Lihat sebelah', $font);

		$section->addText('',array(),array('spaceAfter' => 0));

		$tab2 = $section->addTable(array('align' => 'center'));

        $tab2->addRow();
        $tab2->addCell(6500, $styleCell)->addText('');
        $cel3 = $tab2->addCell(4000, $styleCell);
        $cel3->addText('        Kajen,', $font);

        // $section->addText(' ',array('size' => '6'), array('spaceAfter' => 0));

        $tab3 = $section->addTable(array('align' => 'center'));
        $tab3->addRow(500);
        $tab3->addCell(6500, $styleCell)->addText('');
        $cel3 = $tab3->addCell(4000, $styleCell);
        $cel3->addText('KEPALA BAPPEDA LITBANG', 
            array('name' => 'Arial', 'size' => '12', 'bold' => true),
            array('align' => 'center','spaceAfter' => 0) 
        );
        
        $cel3->addText('KABUPATEN PEKALONGAN', 
            array('name' => 'Arial', 'size' => '12', 'bold' => true),
            array('align' => 'center','spaceAfter' => 0)
        );

        $section->addText('');
        $section->addText('',array(), array('spaceAfter' => 0));
        $section->addText('',array(), array('spaceAfter' => 0));

        $tab3 = $section->addTable(array('align' => 'center'));
        $tab3->addRow(500);
        $tab3->addCell(6500, $styleCell)->addText('');
        $cel3 = $tab3->addCell(4000, $styleCell);
        $signer = DB::select("SELECT * FROM signer JOIN pegawai ON signer.pegawai_id=pegawai.id_pegawai WHERE pegawai.opd_id='$opd_id'");
        $cel3->addText($signer[0]->nama, 
                array('name' => 'Arial', 'size' => '12', 'bold' => true,'underline' => 'single'),
                array('align' => 'center','spaceAfter' => 0)
            );
        $cel3->addText('NIP. '.$signer[0]->nip, 
            array('name' => 'Arial', 'size' => '12', 'bold' => true),
            array('align' => 'center','spaceAfter' => 0)
        );

        $filename = public_path('SPPD-'.$dinas->no_sp.'-'.$dinas->keterangan.'.docx'); 

        $phpWord->save($filename);
        return response()->file($filename);
        return response()->download($filename);
    }

    public function sp(Request $request)
    {
        $count_pegawai = count($request->pegawai_id);
        $dinas = Dinas::find($request->id_dinas);
        $opd_id = Auth::user()->opd_id;

        // return $request;
        if($dinas->keterangan == 'Dinas Luar'){
            $no_sp = '090/LD/'.$dinas->no_sp.'/'.bulan_romawi($dinas->bulan_input).'/'.date('Y', strtotime($dinas->tanggal));
        } elseif ($dinas->keterangan == 'Dinas Dalam') {
            $no_sp = '090/DD/'.$dinas->no_sp.'/'.bulan_romawi($dinas->bulan_input).'/'.date('Y', strtotime($dinas->tanggal));
        }
        
        if ($count_pegawai == 1){
            $id_pegawai = $request->pegawai_id[0];
            $pegawai = DB::select("SELECT * FROM pegawai JOIN jabatan ON pegawai.jabatan_id=jabatan.id_jabatan WHERE id_pegawai = '$id_pegawai' AND pegawai.opd_id='$opd_id'");

        } else {
            $query = DB::table('pegawai');
            $query->join('jabatan', 'pegawai.jabatan_id','=','jabatan.id_jabatan');
            $query->where('pegawai.opd_id','=',$opd_id);
            for ($i=0; $i < $count_pegawai; $i++) {
                $query->orWhere('id_pegawai', '=', $request->pegawai_id[$i]);
            }
            $pegawai2 = $query->get();
        }
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $sectionStyle = $section->getStyle();
        $sectionStyle->setMarginLeft(900);
        $sectionStyle->setMarginRight(900);
        $sectionStyle->setMarginTop(400);

        $imagePath = public_path('assets/media/misc/kop.jpg'); 
        $imageData = base64_encode(file_get_contents($imagePath));

        $section->addImage(
            base64_decode($imageData),
            array(
                'width' => 500,
            )
        );

        $font = array('name' => 'Arial',
            'size' => '12');

        $styleCell = array('valign'=>'center');
        //table untuk nomor dan lembar ke
        $tabl = $section->addTable(array('align' => 'center'));

        $section->addText('SURAT PERINTAH', array(
            'bold' => true,
            'underline' => 'single',
            'name' => 'Arial',
            'size' => '13'
        ), array(
            'align' => 'center'
        ));

        $section->addText('Nomor  : '.$no_sp, array(
            'bold' => true,
            'name' => 'Arial',
            'size' => '12'
        ), array(
            'align' => 'center'
        ));

        $styleTable = array('cellMarginRight' => 80, 'cellMarginLeft' => 80, 'cellMarginTop' => 20);
        $phpWord->addTableStyle('myOwnTableStyle', $styleTable);

        $cellLeft = array('borderTopSize' => 8, 'borderLeftSize' => 8);
        $cellTop = array('borderTopSize' => 8, 'borderRightSize' => 8);
        $cellRight = array('borderTopSize' => 8, 'borderRightSize' => 8);
        $cellTitik2 = array('borderTopSize' => 8);

        $cellLeftBot = array('borderTopSize' => 8, 'borderLeftSize' => 8, 'borderBottomSize' => 8);
        $cellTopBot = array('borderTopSize' => 8, 'borderRightSize' => 8, 'borderBottomSize' => 8);
        $cellRightBot = array('borderTopSize' => 8, 'borderRightSize' => 8, 'borderBottomSize' => 8);
        $cellTitik2Bot = array('borderTopSize' => 8, 'borderBottomSize' => 8);

        $spaceAfter0 = array('spaceAfter' => 0);
        $table2 = $section->addTable('myOwnTableStyle');


        $table2->addRow();
        $table2->addCell(550)->addText('', $font, $spaceAfter0);
        $table2->addCell(2600)->addText('Nama', $font, $spaceAfter0);
        $table2->addCell(200)->addText(':', $font, $spaceAfter0);
        $signer = DB::select("SELECT * FROM signer JOIN pegawai ON signer.pegawai_id=pegawai.id_pegawai JOIN jabatan ON pegawai.jabatan_id=jabatan.id_jabatan WHERE pegawai.opd_id='$opd_id'");
        $table2->addCell(7300)->addText($signer[0]->nama, $font, $spaceAfter0);
		
        $table2->addRow();
		$table2->addCell(550)->addText('',$font, $spaceAfter0);
		$table2->addCell(2600)->addText('NIP', $font, $spaceAfter0);
		$table2->addCell(200)->addText(':', $font, $spaceAfter0);
		$table2->addCell(7300)->addText($signer[0]->nip, $font, $spaceAfter0);
		

		$table2->addRow();
		$table2->addCell(550)->addText('');
		$table2->addCell(2600)->addText('Jabatan', $font);
		$table2->addCell(200)->addText(':', $font);

		$table2->addCell(7300)->addText($signer[0]->jabatan.' Bappeda Litbang Kabupaten Pekalongan', $font);

        $section->addText('Memerintahkan Kepada : ', array(
            'bold' => true,
            'name' => 'Arial',
            'size' => '12'
        ), array(
            'align' => 'center'
        ));


	// ENTRY TABEL MEMERINTAHKAN

        $table3 = $section->addTable('myOwnTableStyle');
        if($count_pegawai == 1){
            $table3->addRow();
			$table3->addCell(550)->addText('',$font, $spaceAfter0);
			$cjudul = $table3->addCell(2600);
			$cjudul->addText('Nama', $font, $spaceAfter0);
			$cjudul->addText('NIP', $font, $spaceAfter0);
			$cjudul->addText('Jabatan', $font, $spaceAfter0);

			$ctitikdua = $table3->addCell(200);
			$ctitikdua->addText(':', $font, $spaceAfter0);
			$ctitikdua->addText(':', $font, $spaceAfter0);
			$ctitikdua->addText(':', $font, $spaceAfter0);

			$cdata = $table3->addCell(7300);
			$cdata->addText($pegawai[0]->nama, $font, $spaceAfter0);
			$cdata->addText($pegawai[0]->nip, $font, $spaceAfter0);
			$cdata->addText($pegawai[0]->jabatan.' Bappeda Litbang Kabupaten Pekalongan', $font, $spaceAfter0);
        } else {
            for ($i=0; $i < $count_pegawai; $i++) { 
                $x = $i+1;

                $table3->addRow();
                $table3->addCell(550)->addText('',$font, $spaceAfter0);
                $table3->addCell(350)->addText($x.'.',$font, $spaceAfter0);
    
                $cjudul = $table3->addCell(2250);
                $cjudul->addText('Nama', $font, $spaceAfter0);
                $cjudul->addText('NIP', $font, $spaceAfter0);
                $cjudul->addText('Jabatan', $font, $spaceAfter0);
    
                $ctitikdua = $table3->addCell(200);
                $ctitikdua->addText(':', $font, $spaceAfter0);
                $ctitikdua->addText(':', $font, $spaceAfter0);
                $ctitikdua->addText(':', $font, $spaceAfter0);
    
                $cdata = $table3->addCell(7300);
                $cdata->addText($pegawai2[$i]->nama, $font, $spaceAfter0);
                $cdata->addText($pegawai2[$i]->nip, $font, $spaceAfter0);
                $cdata->addText($pegawai2[$i]->jabatan.' Bappeda Litbang Kabupaten Pekalongan', $font, $spaceAfter0);
            }
        }

        $section->addText('');

		$keterangan = 'Untuk melaksanakan perjalanan dinas dalam rangka '.$dinas->kegiatan.', yang akan dilaksanakan pada';

	    $table4 = $section->addTable('myOwnTableStyle');
	    $table4->addRow();
		$table4->addCell(250)->addText('',$font, $spaceAfter0);
		$table4->addCell(10350)->addText('            '.$keterangan, $font, array('spaceAfter' => 0, 'align' => 'both'));

		$section->addText('');

		$table5 = $section->addTable('myOwnTableStyle');
	    $table5->addRow();
		$table5->addCell(550)->addText('', $font, $spaceAfter0);
		$table5->addCell(2600)->addText('Hari/ Tanggal', $font, $spaceAfter0);
		$table5->addCell(200)->addText(':', $font, $spaceAfter0);
		$table5->addCell(7300)->addText(hari($dinas->tanggal).', '.tanggal_indo($dinas->tanggal), $font, $spaceAfter0);
		// $table5->addCell(7300)->addText('Kamis - Sabtu, 8 - 10 Februari 2019', $font, $spaceAfter0);

		$table5->addRow();
		$table5->addCell(550)->addText('', $font, $spaceAfter0);
		$table5->addCell(2600)->addText('Jam', $font, $spaceAfter0);
		$table5->addCell(200)->addText(':', $font, $spaceAfter0);
		$table5->addCell(7300)->addText($dinas->jam, $font, $spaceAfter0);

		$table5->addRow();
		$table5->addCell(550)->addText('', $font, $spaceAfter0);
		$table5->addCell(2600)->addText('Tempat', $font, $spaceAfter0);
		$table5->addCell(200)->addText(':', $font, $spaceAfter0);
		$table5->addCell(7300)->addText($dinas->tujuan, $font, $spaceAfter0);

		$table5->addRow();
		$table5->addCell(550)->addText('', $font, $spaceAfter0);
		$table5->addCell(2600)->addText('Keterangan', $font, $spaceAfter0);
		$table5->addCell(200)->addText(':', $font, $spaceAfter0);
		$table5->addCell(7300)->addText('Biaya yang timbul akibat dikeluarkannya Surat Perintah ini dibebankan pada Anggaran Pendapatan dan Belanja Daerah Kabupaten Pekalongan Tahun Anggaran 2023', $font, array('spaceAfter' => 0, 'align' => 'both'));

        $section->addText('');

	    $table6 = $section->addTable('myOwnTableStyle');
	    $table6->addRow();
		$table6->addCell(250)->addText('',$font, $spaceAfter0);
		$table6->addCell(10350)->addText('            '.'Demikian untuk diketahui dan dilaksanakan dengan penuh tanggung jawab.', $font, array('spaceAfter' => 0));

		$section->addText('');

		$table6 = $section->addTable('myOwnTableStyle');

	    $table6->addRow();
		$table6->addCell(5900)->addText('',$font, $spaceAfter0);
		$table6->addCell(2000)->addText('Ditetapkan di', $font,$spaceAfter0);
		$table6->addCell(100)->addText(':', $font,$spaceAfter0);
		$table6->addCell(2150)->addText('Kajen', $font,$spaceAfter0);

		$table6->addRow();
		$table6->addCell(5900)->addText('',$font, $spaceAfter0);
		$table6->addCell(2000)->addText('Pada tanggal', $font,$spaceAfter0);
		$table6->addCell(100)->addText(':', $font,$spaceAfter0);
		$table6->addCell(2150)->addText('', $font,$spaceAfter0);

		$section->addText('',$font,$spaceAfter0);

        $tab3 = $section->addTable(array('align' => 'center'));
        $tab3->addRow();
        $tab3->addCell(5600, $styleCell)->addText('');
        $cel3 = $tab3->addCell(5000, $styleCell);

        $cel3->addText($signer[0]->jabatan.' Bappeda Litbang', 
            array('name' => 'Arial', 'size' => '12'),
            array('align' => 'center','spaceAfter' => 0)
        );
        
        $cel3->addText('Kabupaten Pekalongan', 
            array('name' => 'Arial', 'size' => '12'),
            array('align' => 'center','spaceAfter' => 0)
        );        

        $section->addText('');
        $section->addText('',array(), array('spaceAfter' => 0));
        $section->addText('',array(), array('spaceAfter' => 0));

        $tab3 = $section->addTable(array('align' => 'center'));
        $tab3->addRow();
        $tab3->addCell(5600, $styleCell)->addText('');
        $cel3 = $tab3->addCell(5000, $styleCell);

        $cel3->addText($signer[0]->nama, 
            array('name' => 'Arial', 'size' => '12', 'bold' => true),
            array('align' => 'center','spaceAfter' => 0)
        );

        $cel3->addText($signer[0]->pangkat, 
            array('name' => 'Arial', 'size' => '12'),
            array('align' => 'center','spaceAfter' => 0)
        );

        $cel3->addText($signer[0]->nip, 
            array('name' => 'Arial', 'size' => '12'),
            array('align' => 'center','spaceAfter' => 0)
        );
        


        // $filename = public_path('SP-'.$dinas->no_sp.'-'.$dinas->keterangan.'.docx'); 
        $filename = storage_path('app/SP-112.docx'); 
        $phpWord->save($filename);
        return response()->file($filename);
        return response()->download($filename);
    }

}
