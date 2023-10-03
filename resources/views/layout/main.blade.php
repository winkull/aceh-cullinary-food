<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head><base href="">
		<meta charset="utf-8" />
		<title>{{ isset($data->title) ? strtoupper($data->title) : 'ACEH CULLINARY FOOD APP' }} | ACEH CULLINARY FOOD APP</title>
		<meta name="description" content="Updates and statistics" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<link rel="canonical" href="https://keenthemes.com/metronic" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Page Vendors Styles(used by this page)-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/fontawesome/css/all.css') }}"/>

        <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/cropper/css/cropper.css') }}"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.7/cropper.min.css" crossorigin="anonymous"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/crop-image.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/validation.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/layout.css') }}"/>
        <!--end::Page Vendors Styles-->
        <!--begin::Global Theme Styles(used by all pages)-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/global/plugins.bundle.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.bundle.css') }}"/>        
        <!--end::Global Theme Styles-->

        <link rel="shortcut icon" href="{{ asset('assets/media/logos/logo-app.png') }}" />

        <link href="{{asset('assets/css/select2.min.css') }}" rel="stylesheet" />

        <!--begin::Global Theme Bundle(used by all pages)-->
        <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
        <!--end::Global Theme Bundle-->
        <!--begin::Page Vendors(used by this page)-->
        <script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/validation/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/validation/additional-methods.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/validation/localization/messages_id.js') }}"></script>
        <!--end::Page Vendors-->
        <!--begin::Page Scripts(used by this page)-->
        <script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
        <script src="{{ asset('assets/js/pages/crud/forms/widgets/form-repeater.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/highcharts/highcharts.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/highcharts/modules/data.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/highcharts/modules/series-label.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/highcharts/modules/exporting.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/highcharts/modules/export-data.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/highcharts/modules/accessibility.js') }}"></script>

        <script src="{{ asset('assets/plugins/custom/cropper/cropper.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/jquery-number/jquery.number.js') }}"></script>
		<script src="{{ asset('assets/js/pages/features/charts/apexcharts.js') }}"></script>
        <!--end::Page Scripts-->

        <script src="{{ asset('assets/js/helper.js') }}"></script>
        <script src="{{ asset('assets/js/form-helper.js') }}"></script>
        <script src="{{ asset('assets/js/modal-helper.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.mask.min.js') }}"></script>
        <script src="{{ asset('assets/js/terbilang.js') }}"></script>

        <script>
            var ImageResult,
                notify,
                token = "{{ csrf_token() }}";

            $(document).on("mouseenter", ".popover-hover",function(){
                $(this).popover('show');
            }).on("mouseleave", ".popover-hover",function(){
                $(this).popover('hide');
            });
        </script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body>
		<!-- Start Sidebar -->
        <nav>
            <div class="top">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50 symbol-xxl-50 mr-5 align-self-start align-self-xxl-center">
                        <div class="symbol-label" style="background-image:url('{{ asset('assets/media/svg/avatars/007-boy-2.svg') }}'); border: 2px solid white; background-color: darkgray; border-radius: 50%;"></div>
                            <i class="symbol-badge bg-success"></i>
                        </div>
                        <div>
                            <a class="font-weight-bolder font-size-h3 text-white text-hover-primary">{{Session::get('user_name')}}</a>
                        </div>
                </div><br>
                <div class="row" style="margin-left: 0">
                    <a href="{{route('profile')}}" class="btn btn-sm btn-dark">
                        <i class="fas fa-lg fa-user-cog" style="color: white; "></i><span>Profile</span>
                    </a>
                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#logout" style="margin-left: 20px; margin-right: 20px">
                        <i class="fas fa-lg fa-sign-out-alt" style="color: white; "></i><span>LogOut</span>
                    </a>                    
                </div>
                <span class="line"></span>
            </div><br>
            <div class="bot">
                <div class="nav-links">
                    <div class="nav-link-wrapper"><a class="{{ isset($data) ? ($data->menu == 'dashboard' ? 'text-white' : '') : '' }}" href="{{route('dashboard')}}"><i class="fas fa-lg fa-home" style="color: white; "></i><span>Dashboard</span></a></div>
                    <div class="nav-link-wrapper"><a class="{{ isset($data) ? ($data->menu == 'tenant' ? 'text-white' : '') : '' }}" href="{{route('tenant')}}"><i class="fas fa-lg fa-store" style="color: white; "></i><span>Manajemen Tenant</span></a></div>
                    <div class="nav-link-wrapper"><a class="{{ isset($data) ? ($data->menu == 'pameran' ? 'text-white' : '') : '' }}" href="{{route('pameran')}}"><i class="fab fa-lg fa-youtube" style="color: white; "></i><span style="margin-left: 3
                    0px;">Daftar Link Youtube</span></a></div>
                    <div class="row">
                        <div class="dropdown show">
                            <a class="btn dropdown-toggle {{ isset($data) ? ($data->menu == 'promo' || $data->menu == 'promo-khusus' ? 'text-white' : '') : '' }}" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-lg fa-percent" style="color: white; "></i><span style="margin-left: 20px;">Data Promo</span></a>


                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class=" dropdown-item" href="{{route('promo')}}"><span>Promo Umum</span></a>
                                <a class="dropdown-item" href="{{route('promokhusus')}}"><span>Promo Khusus</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="nav-link-wrapper"><a class="{{ isset($data) ? ($data->menu == 'konten-banner' ? 'text-white' : '') : '' }}" href="{{route('konten')}}"><i class="fas fa-lg fa-ad" style="color: white; "></i><span style="margin-left: 30px;">Data Baliho</span></a></div>
                    <div class="nav-link-wrapper"><a class="{{ isset($data) ? ($data->menu == 'quiz' ? 'text-white' : '') : '' }}" href="{{route('quiz')}}"><i class="fas fa-lg fa-question" style="color: white; "></i><span style="margin-left: 35px;">Data Quiz</span></a></div>                    
                    <div class="nav-link-wrapper"><a class="{{ isset($data) ? ($data->menu == 'ongkir' ? 'text-white' : '') : '' }}" href="{{route('ongkir')}}"><i class="fab fa-lg fa-cc-amazon-pay" style="color: white; "></i><span style="margin-left: 29px;">Ongkos Kirim</span></a></div>
                    <div class="row">
                        <div class="dropdown show">
                            <a class="btn dropdown-toggle {{ isset($data) ? ($data->menu == 'laporan' ? 'text-white' : '') : '' }}" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fad fa-lg fa-file-export" style="color: white; "></i><span style="margin-left: 20px;">Laporan</span></a>


                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{route('laporan.pengguna')}}"><span>Daftar Pengguna</span></a>
                                <a class="dropdown-item" href="{{route('laporan.pesanan')}}"><span>Data Pesanan</span></a>
                                <a class="dropdown-item" href=""><span>Rekap Pesanan</span></a>
                            </div>
                        </div>
                    </div>                                     
                </div>
            </div>
        </nav>

        <div class="col-content">	
				
            @if(Session::has('success'))
                <script>
                    setTimeout(function () {
                        swal.fire({
                            html: "{!! Session::get('success') !!} <br> <b></b>",
                            icon: "success",
                            timer: 2000,
                            timerProgressBar: true,
                            willOpen: () => {
                                Swal.showLoading()
                            }
                        }).then(function() {
                            KTUtil.scrollTop();
                            });
                    }, 1000);
                </script>
            @endif

            @if(Session::has('fail'))
                <script>
                    setTimeout(function () {
                        swal.fire({
                            html: "{!! Session::get('fail') !!}",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok!",
                            customClass: {
                                confirmButton: "btn font-weight-bold btn-light-danger"
                            }
                        }).then(function() {
                            KTUtil.scrollTop();
                        });
                    }, 1000);
                </script>
            @endif

            @yield('content')		
        </div>		

		<div class="modal fade" id="logout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Peringatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>                
                <div class="modal-body">
					Anda yakin ingin keluar?					
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
					<a href="{{ route('logout')}}" class="btn btn-danger">
						<i class="fas fa-lg fa-sign-out-alt"  style="color: white; "></i>&nbsp; Ya! Sign Out
					</a>
                </div>
                
            </div>
        </div>
        <script src=”https://code.jquery.com/jquery-3.2.1.min.js”></script>
		<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "success": "#663259", "secondary": "#E5EAEE", "success": "#1BC5BD", "success": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "success": "#F4E1F0", "secondary": "#ECF0F3", "success": "#C9F7F5", "success": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "success": "#ffffff", "secondary": "#212121", "success": "#ffffff", "success": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
	</body>
</html>
