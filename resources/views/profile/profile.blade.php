@extends('layout.main')
@section('content')

    <center><h1><b>Aceh Cullinary Food App</b></h1></center>

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-6 subheader-transparent" id="kt_subheader">
            <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h2 class="text-dark font-weight-bold my-1 mr-5"><b>Data Profil</b></h2>
                        <!--end::Page Title-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <!--end::Info-->
            </div>
        </div>
        <!--end::Subheader-->
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container-fluid">
                <div class="row" align="center">                    
                    <div class="col col-lg-6" align="center">
                        <!--begin::Card-->
                        <div class="card card-custom card-border">
                            <!--begin::Body-->
                            <div class="card-body">
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                                        <div class="symbol-label" style="background-image:url('{{ asset('assets/media/svg/avatars/007-boy-2.svg') }}');"></div>
                                        <i class="symbol-badge bg-success"></i>
                                    </div>
                                    <div>
                                        <a href="#" class="font-weight-bolder font-size-h3 text-dark-75 text-hover-primary">{{$data->select->user->namaadmin}}</a>
                                    </div>
                                </div>
                                <!--end::User-->
                                <!--begin::Contact-->
                                <div class="py-9">                                            
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="font-weight-bold font-size-h5 mr-2">ID Admin :</span>
                                        <span class="text-muted font-size-h4">{{$data->select->user->idadmin}}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="font-weight-bold font-size-h5 mr-2">Alamat :</span>
                                        <a href="#" class="text-muted font-size-h5 text-hover-primary">{{$data->select->user->alamatadmin}}</a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="font-weight-bold font-size-h5 mr-2">Telepon :</span>
                                        <a href="#" class="text-muted font-size-h5 text-hover-primary">{{$data->select->user->telpadmin}}</a>
                                    </div>                                    
                                </div>
                                <div class="align-items-center">
                                        <a class="btn btn-lg btn-secondary"><i class="fas fa-unlock-alt" style="margin-right: 10px;"></i><span class="font-weight-bold mb-2" data-toggle="modal" data-target="#exampleModalCenter">>Reset Password</span></a>
                                    </div>
                                <!--end::Contact-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <div class="col-lg-6">
                        <!--begin::Card-->
                        <div class="card card-custom card-border">
                        <div class="card-header ribbon ribbon-top">
                            <div class="ribbon-target bg-dark" style="top: -2px; right: 20px;">Update Profile</div>
                            <div class="card-title align-items-start flex-column" style="margin-top: 15px;">
                                <h3 class="card-label font-weight-bolder text-dark">Personal Information</h3>
                                <span class="text-muted font-weight-bold font-size-sm mt-1">Update your personal informaiton</span>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form method="POST" action="{{route('profile.edit',['id' => $data->select->user->id])}}" class="form" id="tenant-form">
                            @csrf
                            <div class="card-body">                                
                                <div class="form-group row">
                                    <label align="left" class="col-form-label col-sm-4">ID Admin</label>
                                    <div class="col-sm">
                                        <input type="text" name="idadmin" class="form-control form-control-solid" value="{{$data->select->user->idadmin}}" placeholder="Masukkan ID Admin" required />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label align="left" class="col-form-label col-sm-4">Nama Admin</label>
                                    <div class="col-sm">
                                        <input type="text" name="nama" class="form-control form-control-solid" value="{{$data->select->user->namaadmin}}" placeholder="Masukkan Nama Admin" required />
                                    </div>
                                </div>                                        
                                <div class="form-group row">
                                    <label align="left" class="col-form-label col-sm-4">Alamat</label>
                                    <div class="col-sm">
                                        <input type="text" name="alamat" class="form-control form-control-solid" value="{{$data->select->user->alamatadmin}}" placeholder="Masukkan Alamat" required />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label align="left" class="col-form-label col-sm-4">Telepon</label>
                                    <div class="col-sm">
                                        <input type="text" name="telepon" class="form-control form-control-solid" value="{{$data->select->user->telpadmin}}" placeholder="Masukkan Nomor Telepon" required />
                                        <input hidden type="text" name="pass" class="form-control form-control-solid" value="{{$data->select->user->pass}}"/>
                                        <input hidden type="text" name="id" class="form-control form-control-solid" value="{{$data->select->user->id}}"/>
                                    </div>
                                </div>                                 
                            </div>
                            <div class="card-footer text-right">
                                <button id="submit-btn" type="submit" class="btn btn-light-dark mr-2"><span class="fad fa-save"></span> Simpan</button>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                        <!--end::Card-->
                    </div>
                </div>
            </div>
            <!--end::Container-->
            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form method="POST" action="{{route('profile.reset.password',['id' => $data->select->user->id])}}" class="form" id="tenant-form">
                        @csrf
                        <div class="card-body">                                
                            <div class="form-group row">
                                <label align="left" class="col-form-label col-sm-4">Password Baru</label>
                                <div class="col-sm">
                                    <input type="password" id="password" name="pass1" class="form-control form-control-solid" value="" placeholder="Masukkan password baru" required />
                                    <i class="bi bi-eye-slash" id="togglePassword" style="cursor: pointer;"> </i>Lihat Password
                                </div>
                            </div>
                            <div class="form-group row">
                                <label align="left" class="col-form-label col-sm-4">Konfirmasi Password</label>
                                <div class="col-sm">
                                    <input type="password" id="password1" name="pass2" class="form-control form-control-solid" value="" placeholder="Konfirmasi Password" required />
                                    <i class="bi bi-eye-slash" id="togglePassword1" style="cursor: pointer;"> </i>Lihat Password
                                </div>
                            </div>                                                               
                        </div>
                        <div class="card-footer text-right">
                            <button id="submit-btn" type="button" class="btn btn-light-danger mr-2"  data-dismiss="modal"><i class="fas fa-window-close"></i></span> Close</button>
                            <button id="submit-btn" type="submit" class="btn btn-light-dark mr-2"><span class="fad fa-save"></span> Save Changes</button>
                        </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
        </div>         
    </div>

    <script type="text/javascript">

        //lihat password baru

        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye / eye slash icon
            this.classList.toggle('bi-eye');
        });

        //lihat konfirmasi password

        const togglePassword1 = document.querySelector('#togglePassword1');
        const password1 = document.querySelector('#password1');

        togglePassword1.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password1.getAttribute('type') === 'password' ? 'text' : 'password';
            password1.setAttribute('type', type);
            // toggle the eye / eye slash icon
            this.classList.toggle('bi-eye');
        });
    </script>
@endsection
