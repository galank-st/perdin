@extends('layouts.template')
@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content col-12" id="kt_content">
        <div class="card mb-8">
            <!--begin::Datatable-->
            <div class="card-body pt-0">
                <div class="fv-row mt-6 bb-4">
                    <!--begin::Label-->
                    <label class="required fw-bold fs-6">Pilih OPD</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <select class="form-select" data-control="select2" data-placeholder="Pilih OPD" id="opd" selected="true" required>
                        <option value="0">Pilih OPD</option>                                
                        @foreach ($opd as $o) 
                        <option value="{{$o->id_opd}}">{{$o->singkatan}}</option>                                
                        @endforeach
                    </select>
                </div>
            </div>
        </div>  
		<div class="card">
			<!--begin::Navbar-->
			<!--begin::Wrapper-->
			<div class="card-header border-0 pt-6">
				<div class="card-title">
					{{-- <div class="d-flex flex-stack mt-20"> --}}
						<!--begin::Search-->
						<div class="d-flex align-items-center position-relative my-1">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="grey"></rect>
								<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="grey"></path>
							</svg>
							<input type="text" data-kt-docs-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Pencarian"/>
						</div>
						<!--end::Search-->						
					{{-- </div> --}}
				</div>
				<div class="card-toolbar">
					<!--begin::Toolbar-->
					<div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
						<!--begin::Add customer-->
						<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah" 
                        title="Tambah Data" id="btn_tambah">
							<i class="fa fa-plus fs-4"></i>
							Tambah
                        </button>
						<!--end::Add customer-->
					</div>
					<!--end::Toolbar-->
				</div>
			</div>
			<!--end::Wrapper-->
			<!--begin::Datatable-->
			<div class="card-body pt-0">
				<table id="data_table" class="table align-middle table-row-dashed fs-6 gy-5">
					<thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th>No</th>
                            <th width="25%">NIP</th>
                            <th width="25%">Nama</th>
                            <th width="25%">Jabatan</th>
                            <th>Bidang</th>
                            <th>#</th>
                        </tr>
					</thead>
					<tbody class="text-gray-600 fw-semibold fs-6">
					</tbody>
				</table>
			</div>
			<br>
			<!--end::Datatable-->
		</div>
    </div>
    <!--end::Post-->
</div>
@endsection

@section('modal')
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h2 class="modal-title">Tambah Pegawai (PNS)</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('pegawai.create_byopd')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-10">
                        <label class="form-label">NIP</label>
                        <input type="text" class="form-control nip" name="nip" placeholder="Masukan NIP">
                    </div>
                    <div class="mb-10">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="nama" placeholder="Masukan Nama">
                        <input type="hidden" class="form-control" name="opd_id" value="{{$opd_id}}" id="opd_id">
                    </div>
                    <div class="mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Bidang</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <select class="form-select" data-control="select2" data-placeholder="Pilih Bidang" name="bidang_id" required id="bidang_id">
                        </select>
                        <!--end::Input-->
                    </div>
                    <div class="mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Jabatan</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <select class="form-select" data-control="select2" data-placeholder="Pilih Jabatan" name="jabatan_id" required>
                            <option></option>
                            @foreach ($jabatan as $p)
                            <option value="{{$p->id_jabatan}}">{{$p->jabatan}}</option> 
                            @endforeach
                        </select>
                        <!--end::Input-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-md btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn  btn-md btn-primary"> <i class="fa fa-save fs-4"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUbah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h2 class="modal-title">Ubah Jabatan</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('pegawai.update')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-10">
                        <label class="form-label">NIP</label>
                        <input type="text" class="form-control nip" name="nip" placeholder="Masukan NIP" id="nip">
                    </div>
                    <div class="mb-10">
                        <label class="form-label">Nama</label>
                        <input type="hidden" name="id_pegawai" id="id_pegawai">
                        <input type="text" class="form-control" name="nama" placeholder="Masukan Nama" id="nama">
                    </div>
                    <div class="mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Bidang</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <select class="form-select" data-control="select2" data-placeholder="Pilih Bidang" name="bidang_id" required id="bidang_id_u">
                        </select>
                        <!--end::Input-->
                    </div>
                    <div class="mb-10">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Jabatan</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <select class="form-select" data-control="select2" data-placeholder="Pilih Jabatan" name="jabatan_id" required id="jabatan_id">
                            @foreach ($jabatan as $p)
                            <option value="{{$p->id_jabatan}}">{{$p->jabatan}}</option> 
                            @endforeach
                        </select>
                        <!--end::Input-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-md btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn  btn-md btn-primary"> <i class="fa fa-save fs-4"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>   
    $('#btn_tambah').hide()   
    let opd_idx = "{{ $opd_id }}"

    dt = $("#data_table").DataTable({
        "processing": true,
        "serverSide": true,
        "saveState": true,
        "ajax": "{{ route('pegawai.data') }}",
        "columns": [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nip', name: 'nip'},
            {data: 'nama', name: 'nama'},
            {data: 'jabatan', name: 'jabatan'},
            {data: 'bidang', name: 'bidang'},
            {data: 'action', name: 'action'},
        ]
    });

    let urlx = url+"/ajax/pegawai/data/byopd/"+opd_idx
    dt.ajax.url(urlx).load();

    if (opd_id != '0'){
        $('#btn_tambah').show()
    } else {
        $('#btn_tambah').hide()
    }
    let bidang = '';
    $.ajax({
        type: "GET",
        url: url+"/bidang/byopd/"+opd_idx,
        success:function(result){
            $('#bidang_id').empty()
            $('#bidang_id_u').empty()

            for (let i = 0; i < result.length; i++) {
                bidang += '<option value="'+result[i].id_bidang+'">'+result[i].bidang+'</option>'
            }
            $('#bidang_id').append(bidang)
            $('#bidang_id_u').append(bidang)

        },
        error:function(result)
        {
            Swal.fire({
                title: 'Ups!',
                text: "Server bermasalah.",
                icon: 'error'
            })
        }
    });
    $('#opd').val(opd_idx).trigger('change')


    
    $('#opd').on("change", function(){  
        let opd_id = $(this).val();
        window.location.href = url+'/pegawai/'+opd_id
    });

    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            dt.search(e.target.value).draw();
        });
    }

    // Filter Datatable
    var handleFilterDatatable = () => {
        // Select filter options
        filterPayment = document.querySelectorAll('[data-kt-docs-table-filter="payment_type"] [name="payment_type"]');
        const filterButton = document.querySelector('[data-kt-docs-table-filter="filter"]');

        // Filter datatable on submit
        filterButton.addEventListener('click', function () {
            // Get filter values
            let paymentValue = '';

            // Get payment value
            filterPayment.forEach(r => {
                if (r.checked) {
                    paymentValue = r.value;
                }

                // Reset payment value if "All" is selected
                if (paymentValue === 'all') {
                    paymentValue = '';
                }
            });

            // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
            dt.search(paymentValue).draw();
        });
    }   

    handleSearchDatatable();

    Inputmask({
        "mask" : "999999999999999999"
    }).mask(".nip");

    $(document).on('click', '.ubah', function () {
        let id = $(this).data('id')
        let nip = $(this).data('nip')
        let nama = $(this).data('nama')
        let bidang_id = $(this).data('bidang_id')
        let jabatan_id = $(this).data('jabatan_id')
        $('#id_pegawai').val(id)
        $('#nip').val(nip)
        $('#nama').val(nama)
        $('#bidang_id').val(bidang_id).trigger('change')
        $('#jabatan_id').val(jabatan_id).trigger('change')
    })

    $(document).on('click', '.hapus', function () {
        let id = $(this).data('id')
        console.clear();
        Swal.fire({
            title: 'Apa Anda yakin untuk Hapus?',
            text: "Data yang terhapus tidak bisa dikembalikan. :(",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Tidak, batalkan!',
            reverseButtons: true,
            padding: '2em'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: url+"/ajax/pegawai/del/"+id,
                    success:function(result){
                        Swal.fire({
                            title: 'Terhapus!',
                            text: "Data berhasil dihapus!",
                            icon: 'success'
                        })
                        $('#data_table').DataTable().ajax.reload();
                    },
                    error:function(result)
                    {
                        Swal.fire({
                            title: 'Ups!',
                            text: "Server bermasalah.",
                            icon: 'error'
                        })
                    }
                });
            } 
        })
    })
</script>
@endsection