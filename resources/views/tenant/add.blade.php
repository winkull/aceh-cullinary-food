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
                <div class="col col-lg">
                    <!--begin::Card-->
                    <div class="card card-custom card-border">
                        <div class="card-header ribbon ribbon-top">
                            <div class="ribbon-target bg-dark" style="top: -2px; right: 20px;">Form Merchant</div>
                            <div class="card-title align-items-start flex-column" style="margin-top: 15px;">
                                <h3 class="card-label font-weight-bolder text-dark">Data Manajemen Merchant</h3>
                                <span class="text-muted font-weight-bold font-size-sm mt-1">Masukkan data sesuai dengan kolom yang tersedia</span>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form method="POST" action="{{ route('tenant.add') }}" class="form" id="tenant-form" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col col-lg-6">
                                        <div class="form-group">
                                            <label>Nomor</label>
                                            <input type="text" name="kode" class="form-control form-control-solid" value="{{ old('kode') }}" placeholder="Masukkan Kode" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Nama Merchant</label>
                                            <input type="text" name="nama" class="form-control form-control-solid" value="{{ old('nama') }}" placeholder="Masukkan Nama" required />
                                        </div>                                        
                                        <div class="form-group">
                                            <label>Pemilik</label>
                                            <input type="text" name="pemilik" class="form-control form-control-solid" value="{{ old('pemilik') }}" placeholder="Masukkan Pemilik" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <input type="text" name="alamat" class="form-control form-control-solid" value="{{ old('alamat') }}" placeholder="Masukkan Alamat" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Kota</label>
                                            <input type="text" name="kota" class="form-control form-control-solid" value="{{ old('kota') }}" placeholder="Masukkan Nama Kota" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Longitude</label><br>
                                            <label class="text-muted">exp : 5.5562446773940195</label>
                                            <input type="text" name="longitude" class="form-control form-control-solid" value="{{ old('longitude') }}" placeholder="Masukkan Longitude" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Latitude</label><br>
                                            <label class="text-muted">exp : 95.3248184531264</label>
                                            <input type="text" name="latitude" class="form-control form-control-solid" value="{{ old('latitude') }}" placeholder="Masukkan Latitude" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Telepon</label>
                                            <input type="text" name="telepon" class="form-control form-control-solid" value="{{ old('telepon') }}" placeholder="Masukkan Nomor Telepon" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Deskripsi</label>
                                            <textarea name="deskripsi" class="ckeditor" required></textarea>
                                        </div>                                        
                                    </div>
                                    <div class="col col-lg-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control form-control-solid" value="{{ old('email') }}" placeholder="Masukkan Alamat Email" required />
                                            <label class="text-muted">exp : example@gmail.com</label>

                                        </div>
                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <div class="radio-inline">
                                                <label class="radio radio-solid radio-primary">
                                                    <input type="radio" name="keterangan" checked="checked" value="Buka" />
                                                    <span></span>Buka
                                                </label>
                                                <label class="radio radio-solid radio-info">
                                                    <input type="radio" name="keterangan" value="Tutup" />
                                                    <span></span>Tutup
                                                </label>
                                                <label class="radio radio-solid radio-danger">
                                                    <input type="radio" name="keterangan" value="Destroyed" />
                                                    <span></span>Destroyed
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Catatan</label>
                                            <input type="text" name="catatan" class="form-control form-control-solid" value="{{ old('catatan') }}" placeholder="Masukkan Catatan"/>
                                        </div>
                                        <div hidden="" class="form-group">
                                            <label>Rating</label>
                                            <input type="number" name="rating" class="form-control form-control-solid" value="{{ old('rating') }}" placeholder="Masukkan Rating"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <div class="radio-inline">
                                                <label class="radio radio-solid radio-primary">
                                                    <input type="radio" checked="checked" name="kelompok" value="Food Market" />
                                                    <span></span>Food Market
                                                </label>
                                                <label class="radio radio-solid radio-primary">
                                                    <input type="radio" name="kelompok" value="Jejak Rasa" />
                                                    <span></span>Jejak Rasa
                                                </label>
                                                <label class="radio radio-solid radio-primary">
                                                    <input type="radio" name="kelompok" value="Nusantara" />
                                                    <span></span>Nusantara
                                                </label>
                                                <label class="radio radio-solid radio-primary">
                                                    <input type="radio" name="kelompok" value="Pop up Food" />
                                                    <span></span>Pop up Food
                                                </label>
                                                <label class="radio radio-solid radio-primary">
                                                    <input type="radio" name="kelompok" value="Pop up Kitchen" />
                                                    <span></span>Pop up Kitchen
                                                </label>
                                                <label class="radio radio-solid radio-primary">
                                                    <input type="radio" name="kelompok" value="Zona Segar" />
                                                    <span></span>Zona Segar
                                                </label>
                                            </div>
                                        </div>                                        
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" id="password" name="password" class="form-control form-control-solid" value="{{ old('password') }}" placeholder="Masukkan Password" required />
                                            <i class="bi bi-eye-slash" id="togglePassword" style="cursor: pointer;"> </i>Lihat Password                                            
                                        </div>
                                        <div class="form-group">
                                            <label>Upload Foto Toko</label>
                                            <div class="card card-custom card-border overlay" id="picture-overlay">
                                                <div class="card-body ribbon ribbon-top p-0">
                                                    <div class="ribbon-target bg-light-dark" style="top: -2px; right: 20px;">Foto Toko</div>
                                                    <div class="overlay-wrapper">                          
                                                        <img id="blah" src="{{ asset('assets/media/bg/camera.jpg') }}" alt="your image" width="100%" />                                  
                                                    </div>

                                                    <div class="overlay-layer align-items-end justify-content-center">
                                                        <div class="d-flex flex-grow-1 flex-center bg-white-o-5 py-5">
                                                            <label for="imgInp" class="btn btn-sm btn-success center-block">
                                                            <input type="file" name="foto" style="display: none;" class="form-control form-control-solid" id="imgInp"/>
                                                            Pilih File
                                                        </label>                                                        
                                                        <script type="text/javascript">
                                                            imgInp.onchange = evt => {
                                                              const [file] = imgInp.files
                                                              if (file) {
                                                                blah.src = URL.createObjectURL(file)
                                                              }
                                                            }
                                                        </script>
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                            </div>                                            
                                            <label class="text-muted">maxsimal upload hanya 2MB</label>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button id="cancel-btn" type="reset" class="btn btn-light-danger mr-2"><span class="fad fa-window-close"></span> Batal</button>
                                <button id="submit-btn" type="submit" class="btn btn-light-dark mr-2"><span class="fad fa-save"></span> Simpan</button>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card-->
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>

    <script>
        $(function () {
            var validation;
                submit_btn  = $('#submit-btn'),
                cancel_btn  = $('#cancel-btn'),
                
            validation = $('#tenant-form').validate({
                ignore: [],
                rules: {
                    name: "required",
                    email: "required|email"
                },
                invalidHandler: function() {
                    swal.fire({
                        text: "Maaf, Ada " + validation.numberOfInvalids() + " kolom yang tidak boleh dibiarkan kosong, silahkan coba lagi.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Siap!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        KTUtil.scrollTop();
                    });
                },
                errorPlacement: function (label, element) {
                    if (element.is("select")) {
                        label.insertAfter(element.next());
                    } else if (element.is("input:radio")) {
                        label.insertAfter($('#status_div'));
                    } else if (element.is("input#picture_val")) {
                        label.insertAfter($('#picture-overlay'));
                    } else {
                        label.insertAfter(element)
                    }
                },
            });

            $(document).on('click', '#cancel-btn', function () {
                window.location.href = '{{ route('tenant') }}';
            });
        });

        //lihat password

        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye / eye slash icon
            this.classList.toggle('bi-eye');
        });
    </script>
@endsection
