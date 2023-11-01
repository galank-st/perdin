@extends('layouts.template')
@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content col-12" id="kt_content">
		<div class="card">
			<!--begin::Datatable-->
			<div class="card-body pt-0">
				<form id="kt_docs_form validation_text mt-6" class="form" method="POST" action="{{route('signer.create')}}">
                    @csrf                    
                    <!--begin::Input group-->
                    <div class="fv-row mt-10 mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Nama Pegawai</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <select class="form-select" data-control="select2" data-placeholder="Pilih Pegawai" name="pegawai_id" id="pegawai_id" required>
                            <option value="{{$signer[0]->pegawai_id}}">{{$signer[0]->nama}}</option>
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
                        <label class="required fw-bold fs-6 mb-2">Pangkat/Golongan</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control" name="pangkat" placeholder="Nomor SP" value="{{$pangkat}}" id="pangkat" required/>
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
			</div>
			<br>
			<!--end::Datatable-->
		</div>
    </div>
    <!--end::Post-->
</div>
@endsection
@section('js')

@endsection