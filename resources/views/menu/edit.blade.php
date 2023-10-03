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
                        <h2 class="text-dark font-weight-bold my-1 mr-5"><b>Tambah Menu</b></h2>
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
                            <div class="ribbon-target bg-dark" style="top: -2px; right: 20px;">TAMBAH MENU TOKO</div>
                            <h3 class="card-title">Form Menu Toko</h3>
                        </div>
                        <!--begin::Form-->
                        <form method="POST" action="{{ route('menu.update',['id' => $datas->kodemenu]) }}" class="form" id="tenant-form" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col col-lg-6">
                                        <div class="form-group">
                                            <label>Nama Menu</label>
                                            <input type="text" name="namamenu" class="form-control form-control-solid" value="{{ $datas->namamenu }}" placeholder="Masukkan Nama Menu" required />
                                            <input type="hidden" name="kode" value="{{ $datas->kodetoko}}">
                                        </div>                                        
                                        <div class="form-group">
                                            <label>Stock Tersedia</label>
                                            <input type="number" name="stock" class="form-control form-control-solid" value="{{ $datas->sisastock }}" placeholder="Masukkan Stock Tersedia" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Harga</label>
                                            <input type="text" id="harga" name="harga" class="form-control form-control-solid mata-uang text-right" onkeyup="inputTerbilang();" value="{{ $datas->harga }}" placeholder="Masukkan Harga Menu" required /><br>
                                            <textarea readonly="" type="text" id="terbilang" class="form-control form-control-solid text-right"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Deskripsi</label>
                                            <textarea name="deskripsi" class="ckeditor" required>{{ $datas->deskripsi }}</textarea>
                                        </div>                                        
                                    </div>
                                    <div class="col col-lg-6">
                                        <div class="form-group">
                                            <label>Jenis Menu</label>
                                            <div class="radio-inline">
                                                <label class="radio radio-solid radio-primary">
                                                    <input type="radio" name="jenis" {{ $datas->jenis == 'Makanan' ? 'checked' : '' }} value="Makanan" />
                                                    <span></span>Makanan
                                                </label>
                                                <label class="radio radio-solid radio-info">
                                                    <input type="radio" name="jenis" {{ $datas->jenis == 'Minuman' ? 'checked' : '' }} value="Minuman" />
                                                    <span></span>Minuman
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Diskon</label>
                                            <input type="number" name="diskon" class="form-control form-control-solid" value="{{ $datas->diskon }}" placeholder="Masukkan Diskon Menu" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Persen</label>
                                            <input type="text" name="persen" class="form-control form-control-solid" value="{{ $datas->persen }}" placeholder="Masukkan Diskon Persen" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <div class="radio-inline">
                                                <label class="radio radio-solid radio-primary">
                                                    <input type="radio" name="status" {{ $datas->status == 1 ? 'checked' : '' }} value="1" />
                                                    <span></span>Aktif
                                                </label>
                                                <label class="radio radio-solid radio-info">
                                                    <input type="radio" name="status" {{ $datas->status == 0 ? 'checked' : '' }} value="0" />
                                                    <span></span>Tidak Aktif
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Upload Foto Menu</label>
                                            <div class="card card-custom card-border overlay" id="picture-overlay">
                                                <div class="card-body ribbon ribbon-top p-0">
                                                    <div class="ribbon-target bg-light-dark" style="top: -2px; right: 20px;">Foto Menu</div>
                                                    <div class="overlay-wrapper">                          
                                                        <img id="blah" src="{{ asset('uploads/menu/'.$datas->gambar) }}" alt="image store" width="100%" />                                  
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
                window.location.href = '{{ route('menu',['id' => $datas->kodetoko]) }}';
            });
        });

        function inputTerbilang() {
          //membuat inputan otomatis jadi mata uang
        $('.mata-uang').mask('0000000000', {reverse: true});

          //mengambil data uang yang akan dirubah jadi terbilang
           var input = document.getElementById("harga").value.replace(/\./g, "");

           //menampilkan hasil dari terbilang
           document.getElementById("terbilang").value = terbilang(input).replace(/  +/g, ' ');
        }
    </script>
@endsection
