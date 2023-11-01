@extends('layouts.template')
@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content col-12" id="kt_content">
		<div class="card card-flush pt-3 mb-5">
            @foreach ($dinas as $d)
			<!--begin::Content-->
			<div class="card-body">
                <h2>Ubah Perjalanan Dinas</h2>
                <br><br>
				<!--begin::Form-->
                <form id="kt_docs_form validation_text" class="form" method="POST" action="{{route('dl.update')}}">
                    @csrf
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">No Surat Perintah (SP)</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="number" class="form-control" name="no_sp" placeholder="Nomor SP" value="{{$d->no_sp}}" id="no_sp"/>
                        <input type="hidden" value="{{$d->dinas_id}}" name="id_dinas">
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Tanggal</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input placeholder="Pilih Tanggal" class="form-control" id="tanggal" name="tanggal" value="{{$d->tanggal}}"/>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Tanggal Pulang</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input placeholder="Pilih Tanggal" class="form-control" id="tanggal_pulang" name="tanggal_pulang" value="{{$d->tanggal_pulang}}"/>
                        <!--end::Input-->
                    </div>
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Nama Pegawai</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <select class="form-select" data-control="select2" data-placeholder="Pilih Pegawai" data-allow-clear="true" multiple="multiple" name="pegawai_id[]" id="pegawai_id" selected="true" value="{{$d->pegawai_id}}">
                            <option></option>
                            @foreach ($pegawai as $p)
                            <option value="{{$p->id_pegawai}}">{{$p->nama}}</option>                                
                            @endforeach
                        </select>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Kegiatan</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control" name="kegiatan" value="{{$d->kegiatan}}" placeholder="Kegiatan ..."/>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Tempat Tujuan</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control" name="tujuan" value="{{$d->tujuan}}" placeholder="Tujuan ..."/>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Jam</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control" name="jam" value="{{$d->jam}}" placeholder="Jam mulai s/d selesai"/>
                        <span class="badge badge-primary mt-1">Contoh : 09.00 s/d Selesai</span>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Transportasi</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <select class="form-select text-black" data-control="select2" data-allow-clear="true" data-hide-search="true" name="transportasi">
                            <option value="{{$d->transportasi}}">{{$d->transportasi}}</option>
                            <option value="KENDARAAN DINAS">KENDARAAN DINAS</option>
                            <option value="KENDARAAN UMUM">KENDARAAN UMUM</option>   
                            <option value="BUS">BUS</option>   

                        </select>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <button type="submit" class="btn btn-danger">
                        <span class="indicator-label">
                            <i class="fa fa-save fs-4"></i> Simpan
                        </span>
                    </button>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
			</div>
			<br>
			<!--end::Content-->
            @endforeach
		</div>
    </div>
    <!--end::Post-->
</div>
@endsection
@section('js')
<script>        
    let array_pegawai = '{{$d->pegawai_id}}' 

    let pegawai_id = '{{$d->pegawai_id}}'
    var arr = pegawai_id.split(',').map(function(item) {
        return parseInt(item, 10); 
    });
    $('#pegawai_id').val(arr).trigger("change")

    $("#pegawai_id").select2({
        tags: true
    });
    var changeInterval = null;
    $("#tanggal").flatpickr();
    $('#hari2').on('click', function () { 
        let form = `<div id="tanggal_pulang" class="fv-row mb-10">
                        <label class="required fw-bold fs-6 mb-2">Tanggal Pulang</label>
                        <input placeholder="Pilih Tanggal" class="form-control" id="tanggal_pulang" name="tanggal_pulang"/>
                    </div>`;
        $("#tanggal_pulang").flatpickr();
        $('#pulang').html(form)
    })
    $('#hari1').on('click', function () {
        $('#tanggal_pulang').remove()
    })

    $('#pegawai_id').on("select2:select", function(){  
        let pegawai = $(this).val();
        let tanggal = $('#tanggal').val();
        let tgl_pulang = $('#tanggal_pulang').val();
        let no_sp = $('#no_sp').val();
        let tanggal_pulang;
        if (tgl_pulang){
            tanggal_pulang = tgl_pulang
        } else {
            tanggal_pulang = tanggal
        }
        // alert(url+"/ajax/cek-dinas/"+pegawai+"/"+tanggal+"/"+tanggal_pulang+"/"+no_sp)
        if(pegawai != ''){
            $.ajax({
                type: "GET",
                url: url+"/ajax/cek-dinas/"+pegawai+"/"+tanggal+"/"+tanggal_pulang+"/"+no_sp,
                success:function(result){
                    // console.log(result)
                    if(result['pesan']){
                        Swal.fire({
                            text: result['pesan'],
                            icon: "warning",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                        $('#pegawai_id').val(array_pegawai).trigger("change")
                    } else {
                        array_pegawai = pegawai
                    }
                },
                error:function(result)
                {
                }
            });
        }

    });
</script>
@endsection