@extends('layouts.template')
@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content col-12" id="kt_content">
        @if(!$signer && $pegawai)            
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
        @elseif($signer)
        <div class="card">
            <!--begin::Datatable-->
            <div class="card-body pt-0">
                <form id="kt_docs_form validation_text mt-6" class="form" method="POST" action="{{route('signer.create')}}">
                    @csrf                    
                    <!--begin::Input group-->
                    <div class="fv-row mt-10 mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Nama Pegawai</label>
                        <input type="hidden" name="id_signer" value="{{$signer[0]->id_signer}}">
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
        @else 
            <!--begin::Alert-->
            <div class="alert alert-dismissible bg-primary d-flex flex-column flex-sm-row p-5 mb-10">
                <!--begin::Icon-->
                <span class="svg-icon svg-icon-2hx svg-icon-light me-4 mb-5 mb-sm-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3" d="M14 3V20H2V3C2 2.4 2.4 2 3 2H13C13.6 2 14 2.4 14 3ZM11 13V11C11 9.7 10.2 8.59995 9 8.19995V7C9 6.4 8.6 6 8 6C7.4 6 7 6.4 7 7V8.19995C5.8 8.59995 5 9.7 5 11V13C5 13.6 4.6 14 4 14V15C4 15.6 4.4 16 5 16H11C11.6 16 12 15.6 12 15V14C11.4 14 11 13.6 11 13Z" fill="black"/>
                    <path d="M2 20H14V21C14 21.6 13.6 22 13 22H3C2.4 22 2 21.6 2 21V20ZM9 3V2H7V3C7 3.6 7.4 4 8 4C8.6 4 9 3.6 9 3ZM6.5 16C6.5 16.8 7.2 17.5 8 17.5C8.8 17.5 9.5 16.8 9.5 16H6.5ZM21.7 12C21.7 11.4 21.3 11 20.7 11H17.6C17 11 16.6 11.4 16.6 12C16.6 12.6 17 13 17.6 13H20.7C21.2 13 21.7 12.6 21.7 12ZM17 8C16.6 8 16.2 7.80002 16.1 7.40002C15.9 6.90002 16.1 6.29998 16.6 6.09998L19.1 5C19.6 4.8 20.2 5 20.4 5.5C20.6 6 20.4 6.60005 19.9 6.80005L17.4 7.90002C17.3 8.00002 17.1 8 17 8ZM19.5 19.1C19.4 19.1 19.2 19.1 19.1 19L16.6 17.9C16.1 17.7 15.9 17.1 16.1 16.6C16.3 16.1 16.9 15.9 17.4 16.1L19.9 17.2C20.4 17.4 20.6 18 20.4 18.5C20.2 18.9 19.9 19.1 19.5 19.1Z" fill="black"/>
                    </svg></span>
                <!--end::Icon-->

                <!--begin::Wrapper-->
                <div class="d-flex flex-column text-light pe-0 pe-sm-10">
                    <!--begin::Title-->
                    <h4 class="mb-2 text-light">Ups! Terjadi Kesalahan.</h4>
                    <!--end::Title-->

                    <!--begin::Content-->
                    <span>Anda harus mengisi data <strong>semua pegawai</strong> terlebih dulu untuk mengakses halaman ini.</span>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
            </div>
        @endif
    </div>
    <!--end::Post-->
</div>
@endsection
@section('js')

@endsection