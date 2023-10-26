
<!--begin::Javascript-->
<!--begin::Global Javascript Bundle(used by all pages)-->
<script src="{{ asset('/') }}assets/plugins/global/plugins.bundle.js"></script>
<script src="{{ asset('/') }}assets/js/scripts.bundle.js"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Page Vendors Javascript(used by this page)-->
<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
<!--end::Page Vendors Javascript-->
<!--begin::Page Custom Javascript(used by this page)-->
{{-- <script src="{{ asset('/') }}assets/js/custom/apps/user-management/roles/view/view.js"></script> --}}
{{-- <script src="{{ asset('/') }}assets/js/custom/apps/user-management/roles/view/update-role.js"></script> --}}
<script src="{{ asset('/') }}assets/js/custom/widgets.js"></script>
<script src="{{ asset('/') }}assets/js/custom/apps/chat/chat.js"></script>
<script src="{{ asset('/') }}assets/js/custom/modals/create-app.js"></script>
<script src="{{ asset('/') }}assets/js/custom/modals/upgrade-plan.js"></script>

@if($x = Session::get('success'))
<script type="text/javascript">
	$(function() {
		var Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 2000
		});

		Toast.fire({
			icon: 'success',
			title: '{{ $x }}'
		})
	})
</script>
@elseif($x = Session::get('error'))
<script type="text/javascript">
	$(function() {
		var Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 2000
		});

		Toast.fire({
			icon: 'error',
			title: '{{ $x }}'
		})
	})      
</script>
@endif
<!--end::Page Custom Javascript-->
<!--end::Javascript-->