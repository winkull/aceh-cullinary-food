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
                <div class="row">                    
                    <div class="col">
                        <!--begin::Card-->
                        <div class="card card-custom card-border">
                            <div class="card-header ribbon ribbon-top">
                                <div class="card-title align-items-start flex-column" style="margin-top: 15px;">
                                    <h3 class="card-label font-weight-bolder text-dark"> Data Ongkos Kirim</h3>
                                    <span class="text-muted font-weight-bold font-size-sm mt-1">Menampilkan data Ongkos Kirim</span>
                                </div>
                            </div>
                            <!--begin::Body-->
                            <div class="row">
                                <div class="col col-lg-6">
                                    <div class="card-body">
                                    <!--begin: Datatable-->
                                        <table class="table table-head-custom nowrap" id="kt_datatable1" width="100%">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>No</th>
                                                    <th>Ongkos Kirim</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        <!--end: Datatable-->
                                    </div>                                
                                </div>
                                <div class="col col-lg-6" style="padding-top:2%;padding-right: 2%;padding-bottom: 2%;">
                                    <div class="card card-custom card-border">
                                        <div class="card-header ribbon ribbon-top">                            
                                            <div class="ribbon-target bg-dark" style="top: -2px; right: 20px;">Form Edit Ongkos Kirim</div>
                                            <div class="card-title align-items-start flex-column" style="margin-top: 15px;">
                                                <h3 class="card-label font-weight-bolder text-dark">Manajemen Ongkos Kirim</h3>
                                                <span class="text-muted font-weight-bold font-size-sm mt-1">Update Ongkos Kirim</span>
                                            </div>
                                        </div>
                                        <!--begin::Form-->
                                        <form method="POST" action="{{ route('ongkir.update',['id' => $data->ongkir->id]) }}" class="form" id="tenant-form">
                                            @csrf
                                            <div class="card-body">

                                                <div class="form-group">
                                                    <label>Petunjuk</label>
                                                    <input type="number" id="harga" name="ongkir" class="form-control form-control-solid mata-uang text-right" onkeyup="inputTerbilang();" value="{{ $data->ongkir->biaya_kirim }}" placeholder="RP" required /><br>
                                                    <textarea readonly="" type="text" id="terbilang" class="form-control form-control-solid text-right"></textarea>
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button id="submit-btn" type="submit" class="btn btn-light-dark mr-2"><span class="fad fa-save"></span> Simpan</button>
                                            </div>
                                        </form>
                                        <!--end::Form-->
                                    </div>                               
                                </div>                                
                            </div>
                            
                            <!--end::Body-->
                        </div>
                        <!--end::Card-->
                    </div>
                </div>
            </div>
            <!--end::Container-->
        </div>         
    </div>

    <script>
        $(function () {
            var validation,
                submit_btn  = $('#submit-btn'),
                cancel_btn  = $('#cancel-btn'),
                table       = $('#kt_datatable1');

            // begin first table
            table.DataTable({
                processing: true,
                serverSide: true,
                aLengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ],
                iDisplayLength: 10,
                scrollY: '60vh',
                scrollX: true,
                scrollCollapse: true,
                ajax:{
                    "url": "{{ route('ongkir.data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: token},                    
                },
                language: {
                    url: "{{ asset('assets/js/datatables/language/Indonesia.json') }}"
                },
                order: [[1, "asc"]],
                columns: [
                    { data: 'id[,]' },
                    { data: 'no' },
                    { data: 'ongkir' },
                ],
                columnDefs: [
                    {
                        targets: 0,
                        width: '10%',
                        orderable: false,
                        render: function(data, type, full, meta) {
                            var datas = data.split(',');
                            return '<div class="text-center"> \
                                        <a href="#" id="Edit" data-url="' + datas[2] + '" class="btn btn-xs btn-outline-success btn-icon popover-hover" data-container="body" data-toggle="popover" data-placement="right" data-content="Ubah">\
                                            <i class="fad fa-edit"></i>\
                                        </a>\
                                    </div>';
                        },
                    },                    
                    {
                        targets: 1,
                        width: '5%',
                        orderable: false,
                        render: function (data, type, full, meta){
                            return '<div class="text-center">' + data + '</div>';
                        }
                    },
                ],
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
