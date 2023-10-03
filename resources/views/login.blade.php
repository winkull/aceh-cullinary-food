<!DOCTYPE html>
<html lang="en">    
    <head>
        <base href="../../../">
        <meta charset="utf-8" />
        <title>LOGIN | ACEH CULLINARY FOOD APP</title>
        <meta name="description" content="Login page example" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<link rel="canonical" href="https://keenthemes.com/metronic" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Page Custom Styles(used by this page)-->
		<link rel="stylesheet" href="{{ asset('assets/css/pages/login/login-1.css') }}" type="text/css" />
		<!--end::Page Custom Styles-->
		<!--begin::Global Theme Styles(used by all pages)-->
		<link rel="stylesheet" href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" type="text/css" />
		<link rel="stylesheet" href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.css') }}" type="text/css" />
		<link rel="stylesheet" href="{{ asset('assets/css/style.bundle.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}" type="text/css" />
		<!--end::Global Theme Styles-->
        <link rel="shortcut icon" href="{{ asset('assets/media/logos/logo-app.png') }}" />
		<!--begin::Layout Themes(used by all pages)-->
		<!--end::Layout Themes-->       
    </head>
    <body>
        <section class="container-fluid bg">
            <section class="row justify-content-center">                
                <section class="col-sm-3">                    
                    <form method="post" action="{{route('proses.login')}}" class="form-container" novalidate="novalidate" id="kt_login_signin_form">
                        @csrf
                        <!--begin::Title-->
                        <center>
                            <div class="column">
                                <img src="{{ asset('assets/media/logos/logo-3.png') }}" alt="">
                            </div>
                        </center>                    
                        <div class="pb-13 pt-lg-0 pt-5">                            
                            <h3 class="font-weight-bolder text-white text-center font-size-h4 font-size-h1-sm">LOGIN</h3>
                        </div>
                        <!--begin::Title-->
                        <!--begin::Form group-->
                        <div class="form-group">
                            <label class="font-size-h6 text-white">ID Admin</label>
                            <input class="form-control form-control-solid h-auto" type="text" name="username" placeholder="Masukkan ID admin" autocomplete="off" />
                        </div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group">
                            <div class="d-flex justify-content-between mt-n5">
                                <label class="font-size-h6 text-white pt-5">Kata Sandi</label>                                
                            </div>
                            <input class="form-control form-control-solid h-auto" type="password" name="password" placeholder="Masukkan kata sandi" autocomplete="off" />
                        </div>
                        <!--end::Form group-->
                        <!--begin::Action-->
                        <div class="pb-lg-0 pb-5">
                            <button type="submit" id="kt_login_signin_submit" class="btn btn-sm btn-block btn-primary font-weight-bolder">Masuk</button>
                        </div>
                        <!--end::Action-->
                    </form>
                </section>
            </section>
        </section>
        <script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
		<!--begin::Global Config(global config for global JS scripts)-->
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#663259", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#F4E1F0", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
		<!--end::Global Config-->
		<!--begin::Global Theme Bundle(used by all pages)-->
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
		<!--end::Global Theme Bundle-->
		<!--begin::Page Scripts(used by this page)-->
		<script src="{{ asset('assets/js/pages/custom/login/login-general.js') }}"></script>
		<!--end::Page Scripts-->

        @if(\Illuminate\Support\Facades\Session::has('login_fail'))
            <script>
                swal.fire({
                    text: "{{ \Illuminate\Support\Facades\Session::get('login_fail') }}",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Siap!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    }
                }).then(function() {
                    KTUtil.scrollTop();
                });
            </script>
        @endif
    </body>
</html>