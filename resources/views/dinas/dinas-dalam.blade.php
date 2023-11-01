@extends('layouts.template')
@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content col-12" id="kt_content">
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
						<a href="{{route('dd.add')}}" type="button" class="btn btn-danger btn-sm">
							<i class="fa fa-plus fs-4"></i>
							Tambah
                        </a>
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
                            <th width="25%">Nama</th>
                            <th>Kegiatan</th>
                            <th>Tujuan</th>
                            <th>No SP</th>
                            <th>Tanggal</th>
                            <th>Tanggal Pulang</th>
                            <th>Pembuat</th>                        
                            <th width="10%">#</th>
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
<div class="modal fade" id="modalSppd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h2 class="modal-title">Pilih Pegawai</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sppd') }}" method="POST" id="cetak">
                @csrf
                <div class="modal-body">
                    <div id="pegawai"></div>
                    <input type="hidden" name="id_dinas" id="id_dinas">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-info"> <i class="las la-print fs-3"></i> Cetak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h2 class="modal-title">Pilih Pegawai</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sp') }}" method="POST" id="cetak2">
                @csrf
                <div class="modal-body">
                    <div id="pegawai2"></div>
                    <input type="hidden" name="id_dinas" id="id_dinas2">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-info"> <i class="las la-print fs-3"></i> Cetak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>    
    dt = $("#data_table").DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "{{ route('dd.data') }}",
        "columns": [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nama', name: 'nama'},
            {data: 'kegiatan', name: 'kegiatan'},
            {data: 'tujuan', name: 'tujuan'},
            {data: 'no_sp', name: 'no_sp'},
            {data: 'tanggal', name: 'tanggal'},
            {data: 'tanggal_pulang', name: 'tanggal_pulang'},
            {data: 'user', name: 'user'},
            {
                data: 'action', 
                name: 'action', 
                orderable: true, 
                searchable: true
            },
        ]
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

    $(document).on('click', '.hapus', function () {
        let no_sp = $(this).data('no_sp')
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
                    url: url+"/ajax/dinas-dalam/del/"+no_sp,
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

    $(document).on('click', '.sppd', function () {
        let no_sp = $(this).data('no_sp')
        let ket = $(this).data('keterangan')
        let id = $(this).data('id')

        $('#id_dinas').val(id);

        let checkbox = ''
        // console.clear();
        $.ajax({
            type: "GET",
            url: url+"/cek-pegawai/"+no_sp+'/'+ket,
            success:function(result){
                for (let i = 0; i < result.length; i++) {
                    checkbox += `<div class=" mb-2 form-check form-check-custom"><input class="form-check-input" name="pegawai_id[]" type="checkbox" value="`+result[i]['id_pegawai']+`" id="`+result[i]['id_dinas']+`"/><label class="form-check-label" for="`+result[i]['id_dinas']+`"> `+' '+result[i]['nama']+`</label></div>`;
                }
                $('#pegawai').html(checkbox)
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
    })

    $("#cetak").submit(function(event) {
        let pegawai = $("input[type='checkbox']:checked");
        
        if (pegawai.length < 1) {
            event.preventDefault(); 
            Swal.fire({
                    title: 'Ups!',
                    text: "Mohon pilih pegawai.",
                    icon: 'error'
                })
        }
    });

    $(document).on('click', '.sp', function () {
        let no_sp = $(this).data('no_sp')
        let ket = $(this).data('keterangan')
        let id = $(this).data('id')

        $('#id_dinas2').val(id);

        let checkbox = ''
        // console.clear();
        $.ajax({
            type: "GET",
            url: url+"/cek-pegawai/"+no_sp+'/'+ket,
            success:function(result){
                for (let i = 0; i < result.length; i++) {
                    checkbox += `<div class=" mb-2 form-check form-check-custom"><input class="form-check-input" name="pegawai_id[]" type="checkbox" value="`+result[i]['id_pegawai']+`" id="`+result[i]['id_dinas']+`"/><label class="form-check-label" for="`+result[i]['id_dinas']+`"> `+' '+result[i]['nama']+`</label></div>`;
                }
                $('#pegawai2').html(checkbox)
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
    })

    $("#cetak2").submit(function(event) {
        let pegawai = $("input[type='checkbox']:checked");
        
        if (pegawai.length < 1) {
            event.preventDefault(); 
            Swal.fire({
                    title: 'Ups!',
                    text: "Mohon pilih pegawai.",
                    icon: 'error'
                })
        }
    });

</script>
@endsection