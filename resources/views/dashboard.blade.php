@extends('layouts.template')
@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <div class="row g-5 g-xl-12 mb-8">
            <div class="col-lg-6">
                <div class="card me-4 ms-4">
                    <!--begin::Navbar-->
                    <!--begin::Wrapper-->
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <span class="card-label fw-bolder fs-3 mb-1">Dinas Luar Berdasarkan Bidang</span>
                        </div>
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Datatable-->
                    <div class="card-body pt-0">
                        <table id="kt_datatable_1" class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-muted">
                                    <th>No</th>
                                    <th>Nama Bidang</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=1;
                                @endphp
                                @foreach ($dl_bidang as $dlb)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$dlb->bidang}}</td>
                                    <td>{{$dlb->jml}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <!--end::Datatable-->
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card me-4 ms-4">
                    <!--begin::Navbar-->
                    <!--begin::Wrapper-->
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <span class="card-label fw-bolder fs-3 mb-1">Dinas Dalam Berdasarkan Bidang</span>
                        </div>
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Datatable-->
                    <div class="card-body pt-0">
                        <table id="kt_datatable_2" class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-muted">
                                    <th>No</th>
                                    <th>Nama Bidang</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=1;
                                @endphp
                                @foreach ($dd_bidang as $ddb)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$ddb->bidang}}</td>
                                    <td>{{$ddb->jml}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <!--end::Datatable-->
                </div>
            </div>
        </div>
        <div class="row g-5 g-xl-12">
            <div class="col-lg-6">
                <div class="card me-4 ms-4">
                    <!--begin::Navbar-->
                    <!--begin::Wrapper-->
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <span class="card-label fw-bolder fs-3 mb-1">Dinas Luar Berdasarkan Pegawai</span>
                        </div>
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Datatable-->
                    <div class="card-body pt-0">
                        <table id="kt_datatable_1" class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-muted">
                                    <th>No</th>
                                    <th>Nama Pegawai</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=1;
                                @endphp
                                @foreach ($dl_pegawai as $dlp)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$dlp->nama}}</td>
                                    <td>{{$dlp->jml}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <!--end::Datatable-->
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card me-4 ms-4">
                    <!--begin::Navbar-->
                    <!--begin::Wrapper-->
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <span class="card-label fw-bolder fs-3 mb-1">Dinas Dalam Berdasarkan Pegawai</span>
                        </div>
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Datatable-->
                    <div class="card-body pt-0">
                        <table id="kt_datatable_2" class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-muted">
                                    <th>No</th>
                                    <th>Nama Pegawai</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=1;
                                @endphp
                                @foreach ($dd_pegawai as $ddp)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$ddp->nama}}</td>
                                    <td>{{$ddp->jml}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <!--end::Datatable-->
                </div>
            </div>
        </div>
    </div>    
    <!--end::Post-->
</div>
@endsection
@section('js')
<script>    
$('#kt_datatable_1').dataTable( {
    "searching": true
} );
$('#kt_datatable_2').dataTable( {
    "searching": true
} );
</script>
@endsection