@extends('layouts.template')
@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content col-12" id="kt_content">
        <div class="card">
			<!--begin::Datatable-->
			<div class="card-body pt-0">
                <div class="fv-row mt-6 mb-0">
                    <!--begin::Label-->
                    <label class="required fw-bold fs-6 mb-2">Pilih Bulan</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="hidden" id="bulan_now" value="{{$bulan_now}}">
                    <select class="form-select" data-control="select2" data-placeholder="Pilih Bulan" id="bulan" selected="true" required>
                        @foreach ($bulan as $angka => $bulan) 
                        <option value="{{$angka}}">{{$bulan}}</option>                                
                        @endforeach
                    </select>
                    <!--end::Input-->
                </div>
			</div>
			<br>
			<!--end::Datatable-->
		</div>

		<div class="card mt-6">
			<!--begin::Datatable-->
			<div class="card-body pt-0">
                <div class="fv-row mt-10 mb-10">
                    <table class="table table-row-bordered gy-5" id="data_table" width="100%">
                        <thead>
                            <tr class="fw-bold fs-6 text-muted">
                                <th width="3%">No</th>
                                <th width="30%">Nama Pegawai</th>
                                @for ($i = 1; $i < $jml_hari; $i++)
                                <th>{{$i}}</th>
                                @endfor
                                <th>DD</th>
                                <th>DL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($dinas as $p)
                            @php
                                $dd = 0;
                                $dl = 0;
                            @endphp
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{$p->nama}}</td>
                                @for ($i = 1; $i < $jml_hari; $i++)
                                @php
                                    $x = 'x'.$i;
                                @endphp
                                @if ($p->dinas[0]->$x == 'Dinas Luar')
                                @php
                                    $dl = $dl+1
                                @endphp
                                <td><span class="badge badge-danger">DL</span></td>                            
                                @elseif ($p->dinas[0]->$x == 'Dinas Dalam')
                                @php
                                    $dd = $dd+1
                                @endphp
                                <td><span class="badge badge-info">DD</span></td>                            
                                @else
                                <td>-</td>                            
                                @endif
                                @endfor
                                <td>{{$dd}}</td>
                                <td>{{$dl}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
			</div>
			<br>
			<!--end::Datatable-->
		</div>
    </div>
    <!--end::Post-->
</div>
@endsection
@section('js')
<script>
    $('#data_table').dataTable( {
        "searching": true,
        "scrollX": true
    } );
    let bulan = $('#bulan_now').val();
    if (bulan != 0){
        $('#bulan').val(bulan).trigger('change');
    }

    $('#bulan').on("change", function(){ 
        let bulan = $(this).val();
        var newURL = url+'/rekap/'+bulan;
        window.location.href = newURL;

    });
</script>
@endsection