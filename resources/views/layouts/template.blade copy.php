
<!doctype html>
<html lang="en" dir="ltr">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

@include('layouts.header')
	{{-- gradient : gradient-menu
	color : color-menu
	dark : dark-menu --}}
	<body class="app sidebar-mini dark-menu">
        <div class="page">
			<div class="page-main">
						<!--APP-SIDEBAR-->
				<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
				
                @include('layouts.sidebar')
				<!--/APP-SIDEBAR-->						<!-- App-Header -->
				<div class="app-header header">
					<div class="container-fluid">
						<div class="d-flex">
							<a class="header-brand d-md-none" href="index.html">
								<img src="{{ asset('assets/images/brand/logo-4.png') }}" class="header-brand-img mobile-icon" alt="logo">
								<img src="{{ asset('assets/images/brand/logo-4.png') }}" class="header-brand-img desktop-logo mobile-logo" alt="logo">
							</a>
							
							<div class="d-flex ml-auto header-right-icons header-search-icon">
								{{-- NOTIFICATION --}}
								<div class="dropdown d-none d-md-flex notifications">
									<a class="nav-link icon" data-toggle="dropdown">
									</a>
								</div>
								<!-- NOTIFICATIONS -->
																
								<div class="dropdown profile-1">
									<a href="#" data-toggle="dropdown" class="nav-link pl-2 pr-2  leading-none d-flex">
										<span>
											<img src="{{ asset('assets/images/users/10.jpg')}}" alt="profile-user" class="avatar  mr-xl-3 profile-user brround cover-image">
										</span>
										<div class="text-center mt-1 d-none d-xl-block">
											<h6 class="text-dark mb-0 fs-13 font-weight-semibold">{{  Auth::user()->name }}</h6>
										</div>
									</a>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
										<a class="dropdown-item" href="{{ route('logout') }}">
											<i class="dropdown-icon mdi  mdi-logout-variant"></i> Sign out
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@yield('konten')
			</div>
			<!-- SIDE-BAR CLOSED -->					<!-- FOOTER -->
			<footer class="footer">
				<div class="container">
					<div class="row align-items-center flex-row-reverse">
						<div class="col-md-12 col-sm-12 text-left">
							Copyright © {{ date('Y') }} Galank<a href="#" style="color: white">Yoha</a>.
							{{-- YANG PENTING JANGAN DIHAPUS SOALE FREELICENSE --}}
							<span style="color: white">Designed by</span>  <a href="#" style="color: white"> Spruko Technologies Pvt.Ltd </a> <span style="color: white">All rights reserved.</span> 
						</div>
					</div>
				</div>
			</footer>
			<!-- FOOTER END -->				
		</div>
				<!-- BACK-TO-TOP -->
		<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>
		@yield('modal')
		@include('layouts.script')
		@yield('js')

	</body>
</html>