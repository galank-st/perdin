<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar">
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
        <!--begin::Toolbar container-->
        <div class="d-flex flex-column flex-row-fluid">
            <!--begin::Toolbar wrapper-->
            <div class="d-flex align-items-center pt-1">
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold pt-5">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-white fw-bold lh-1">
                        <a href="../../demo32/dist/index.html" class="text-white">
                            <i class="ki-outline ki-home text-white fs-6"></i>
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <i class="ki-outline ki-right fs-6 text-white mx-n1"></i>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-white fw-bold lh-1">Customers</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Toolbar wrapper=-->
            <!--begin::Page title-->
            <div class="page-title d-flex align-items-center me-3 mb-4 pt-9 pt-lg-17 mb-lg-15">
                <div class="btn btn-icon btn-custom h-65px w-65px me-6">
                    <img alt="Logo" src="{{ asset('/') }}assets/media/svg/misc/layer.svg" class="h-40px" />
                </div>
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-white fw-bolder fs-2 flex-column justify-content-center my-0">{{ $title }}
                <!--begin::Description-->
                <span class="page-desc fs-6 fw-bold pt-4">{{ $sub_title }}</span>
                <!--end::Description--></h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <div class="d-flex justify-content-between flex-wrap gap-4 gap-lg-10">
                <!--begin::Toolbar menu-->
                <div class="app-toolbar-menu menu menu-rounded menu-gray-800 menu-state-bg flex-wrap fs-5 fw-semibold">
                    <!--begin::Menu item-->
                    <div class="menu-item pb-xl-8 pb-4 mt-5 mt-lg-0">
                        <a class="menu-link {{ set_active('dashboard') }}" href="{{ route('dashboard') }}">
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item pb-xl-8 pb-4 mt-5 mt-lg-0">
                        <a class="menu-link {{ set_active('user.data') }}" href="{{ route('user.data') }}">
                            <span class="menu-title">User</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--begin::Toolbar menu-->
            </div>
        </div>
        <!--end::Toolbar container=-->
    </div>
    <!--end::Toolbar container-->
</div>
<!--end::Toolbar-->