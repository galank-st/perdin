@extends('layouts.template')
@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content col-12" id="kt_content">
		<div class="card card-flush pt-3 mb-5">
			<!--begin::Content-->
			<div class="card-body">
                <h2>Tambah Perjalanan Dinas</h2>
                <br><br>
				<!--begin::Form-->
                <form id="kt_docs_form validation_text" class="form" method="POST" action="{{route('dl.create')}}">
                    @csrf
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">No Surat Perintah (SP)</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="number" class="form-control" name="no_sp" placeholder="Nomor SP" value="{{$no_sp}}" id="no_sp" required/>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Tanggal</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input placeholder="Pilih Tanggal" class="form-control" id="tanggal" name="tanggal" required/>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <div class="mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-5">Lama Perjalan Dinas</label>
                        <!--end::Label-->
                
                        <!--begin::Input row-->
                        <div class="d-flex flex-column fv-row">
                            <!--begin::Radio-->
                            <div class="form-check form-check-custom form-check-solid mb-5">
                                <!--begin::Input-->
                                <input class="form-check-input me-3" name="radio_input" type="radio" value="1" id="hari1" checked/>
                                <!--end::Input-->
                
                                <!--begin::Label-->
                                <label class="form-check-label" for="hari1">
                                    <div class="fw-bolder text-gray-800">1 Hari</div>
                                </label>
                                <!--end::Label-->
                            </div>
                            <!--end::Radio-->
                
                            <!--begin::Radio-->
                            <div class="form-check form-check-custom form-check-solid mb-5">
                                <!--begin::Input-->
                                <input class="form-check-input me-3" name="radio_input" type="radio" value="2" id="hari2"/>
                                <!--end::Input-->
                
                                <!--begin::Label-->
                                <label class="form-check-label" for="hari2">
                                    <div class="fw-bolder text-gray-800">Lebih Dari 1 Hari</div>
                                </label>
                                <!--end::Label-->
                            </div>
                            <!--end::Radio-->
                            <!--end::Radio-->
                        </div>
                        <!--end::Input row-->
                    </div>
                    <div id="pulang"></div>
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Nama Pegawai</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <select class="form-select" data-control="select2" data-placeholder="Pilih Pegawai" data-allow-clear="true" multiple="multiple" name="pegawai_id[]" id="pegawai_id" selected="true" required>
                            @foreach ($pegawai as $p)
                            <option value="{{$p->id}}">{{$p->nama}}</option>                                
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
                        <input type="text" class="form-control" name="kegiatan" placeholder="Kegiatan ..." required/>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Tempat Tujuan</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control" name="tujuan" placeholder="Tujuan ..." required/>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Jam</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control" name="jam" placeholder="Jam mulai s/d selesai" required/>
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
                        <select class="form-select text-black" data-control="select2" data-placeholder="Pilih Transportasi" data-allow-clear="true" data-hide-search="true" name="transportasi" required>
                            <option></option>
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
		</div>
    </div>
    <!--end::Post-->
</div>
@endsection
@section('js')
<script>      
    let no_sp_old = '{{$no_sp}}'
    let array_pegawai = []  
    $("#pegawai_id").select2({
        tags: true
    });
    let url = '{{ url('/')}}';
    var changeInterval = null;
    $("#tanggal").flatpickr();
    $("#tanggal_pulang").flatpickr();

    $('#hari2').on('click', function () { 
        let form = `<div id="tanggal_pulang" class="fv-row mb-10">
                        <label class="required fw-bold fs-6 mb-2">Tanggal Pulang</label>
                        <input placeholder="Pilih Tanggal" class="form-control" id="tanggal_pulang" name="tanggal_pulang"/>
                    </div>`;
        $('#pulang').html(form)
    })
    $('#hari1').on('click', function () {
        $('#tanggal_pulang').remove()
    })

    $('#pegawai_id').on("select2:select", function(){ 
        let pegawai = $(this).val();        
        let tanggal = $('#tanggal').val();
        let tgl_pulang = $('#tanggal_pulang').val();
        let tanggal_pulang;

        if (tanggal === '') {
            Swal.fire({
                text: "Ups! Mohon tanggal diisi dulu.",
                icon: "warning",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
            $('#pegawai_id').val('').trigger('change')
        }  
        
        if (tgl_pulang){
            tanggal_pulang = tgl_pulang
        } else {
            tanggal_pulang = tanggal
        }
        // alert(url+"/ajax/cek-dinas/"+pegawai+"/"+tanggal+"/"+tanggal_pulang)
        if(pegawai != ''){
            $.ajax({
                type: "GET",
                url: url+"/ajax/cek-dinas/"+pegawai+"/"+tanggal+"/"+tanggal_pulang,
                success:function(result){
                    // console.log(result['pegawai'])
                    if(result['pegawai'] == 1){
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
    $('#no_sp').on("change", function(){ 
        let no_sp = $(this).val();
        $.ajax({
            type: "GET",
            url: url+"/ajax/cek-sp/"+no_sp,
            success:function(result){
                // console.log(result['pegawai'])
                if(result['no_sp'] == 1){
                    Swal.fire({
                        text: "Ups! Nomor SP telah digunakan.",
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    $('#no_sp').val(no_sp_old);
                } 
            },
            error:function(result)
            {
            }
        });

    });

    @if ($errors->any())
        Swal.fire({
            title: 'Kesalahan!',
            text: @json($errors->first()),
            icon: 'error',
            confirmButtonText: 'Oke'
        });
    @endif
</script>
@endsection