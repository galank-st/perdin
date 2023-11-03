@extends('layouts.template')
@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <div class="content col-12 mt-n6" id="kt_content">
            <div class="card me-4 ms-4">
                <!--begin::Datatable-->
                <div class="card-body pt-0">
                    <div class="fv-row mt-6 mb-8">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Pilih Grafik</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <select class="form-select" data-control="select2" data-placeholder="Pilih Bulan" id="bulan" selected="true" required>
                            <option value="0">Semua</option>                                
                            @foreach ($bulan as $angka => $bulan) 
                            <option value="{{$angka}}">{{$bulan}}</option>                                
                            @endforeach
                        </select>
                        <!--end::Input-->
                    </div>
                    <div class="mt-8" id="chart"></div>
                </div>
                <br>
                <!--end::Datatable-->
            </div>
        </div>
        
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
var chart;
$(document).ready(function() {
    $.ajax({
        type: "GET",
        url: url+"/grafik-bulanan",
        success:function(result){
            // console.log(result)
            var chartData = result
            var options = {
                series: [{
                    name: "Jumlah Perjalanan Dinas",
                    data: chartData
                }],
                chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
                },
                dataLabels: {
                enabled: false
                },
                stroke: {
                curve: 'straight'
                },
                title: {
                text: 'Grafik Laporan Bulanan Perjalanan Dinas',
                align: 'left'
                },
                grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
                },
                xaxis: {
                categories: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            }
            };

            chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

        },
        error:function(result)
        {
        }
    });
})

function refreshChart(newData, newCategories) {
    chart.destroy(); // Destroy the existing chart
    createChart(newData, newCategories); // Recreate the chart with new data and categories
}


$('#bulan').on("change", function(){  
    let bulan = $(this).val();
    if (bulan != 0) {
        $.ajax({
            type: "GET",
            url: url+"/grafik-harian/"+bulan,
            success:function(result){
                let newCategories = result.hari
                let newData = result.dinas

                chart.updateOptions({
                    xaxis: {
                        categories: newCategories,
                    },
                    series: [{
                        data: newData,
                    }],
                });
            },
            error:function(result)
            {
            }
        });
    } else {
        $.ajax({
            type: "GET",
            url: url+"/grafik-bulanan",
            success:function(result){
                let newCategories = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agus', 'Sep', 'Okt', 'Nov', 'Des']
                let newData = result

                chart.updateOptions({
                    xaxis: {
                        categories: newCategories,
                    },
                    series: [{
                        data: newData,
                    }],
                });
            },
            error:function(result)
            {
            }
        });
    }

});

</script>
@endsection