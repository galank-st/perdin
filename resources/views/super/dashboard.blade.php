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
                        HIYA HIYA HIYA
                    </div>
                </div>
                <br>
                <!--end::Datatable-->
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