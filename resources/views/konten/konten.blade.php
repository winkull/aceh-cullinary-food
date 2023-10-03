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
                                    <h3 class="card-label font-weight-bolder text-dark"> Data Konten Baliho</h3>
                                    <span class="text-muted font-weight-bold font-size-sm mt-1">Menampilkan data baliho yang tersusun dalam area pameran.</span>
                                </div>
                                <div class="card-toolbar">
                                    <a href="{{route('konten.create')}}" class="btn btn-light-dark" role="button" aria-pressed="true">Tambah Konten</a>                                   
                                </div>
                            </div>
                            <!--begin::Body-->
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <table class="table table-head-custom nowrap" id="kt_datatable1" width="100%">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>No</th>
                                        <th>Uraian</th>
                                        <th>Tautan</th>
                                        <th>Status</th>
                                        <th>Gambar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <!--end: Datatable-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card-->
                    </div>
                </div>
            </div>
            <!--end::Container-->
            <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    ...
                    </div>
              </div>
            </div>
        </div>         
    </div>

    <script>
        $(function () {
            var validation,
                submit_btn  = $('#submit-btn'),
                cancel_btn  = $('#cancel-btn'),
                table       = $('#kt_datatable1');

            validation = $('#proyek-form').validate({
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
                        label.insertAfter($('.file-input'));
                    } else {
                        label.insertAfter(element)
                    }
                },
            });

            cancel_btn.on('click', function () {
                window.location.href = '{{ route('tenant') }}';
            });

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
                    "url": "{{ route('konten.data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: token},                    
                },
                language: {
                    url: "{{ asset('assets/js/datatables/language/Indonesia.json') }}"
                },
                order: [[2, "asc"]],
                columns: [
                    { data: 'id[,]' },
                    { data: 'no' },
                    { data: 'uraian' },
                    { data: 'tautan' },
                    { data: 'status' },
                    { data: 'gambar' },
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
                                        <a href="#" id="Delete" data-name="' + datas[1] + '" data-url="' + datas[3] + '" class="btn btn-xs btn-outline-danger btn-icon popover-hover" data-container="body" data-toggle="popover" data-placement="right" data-content="Hapus">\
                                            <i class="fad fa-trash"></i>\
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

            $(document).on('click', '#Edit', function () {
                window.location.href = $(this).data('url');
            });

            $(document).on('click', '#Menu', function () {
                window.location.href = $(this).data('url');
            });

            $(document).on('click', '#Delete', function () {
                var url = $(this).data('url');

                Swal.fire({
                    title: "Konfirmasi",
                    html: "Anda akan menghapus data promo <span class='text-danger'><strong>" + $(this).data('name') + "</strong></span>, lanjutkan ?",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: "Batal",
                    confirmButtonText: "Ya, hapus!"
                }).then(function(result) {
                    if (result.value) {
                        window.location.href = url;
                    }
                });
            });
        });
    </script>
@endsection
