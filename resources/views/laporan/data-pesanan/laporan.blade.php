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
                        <h2 class="text-dark font-weight-bold my-1 mr-5"><b>Laporan Pesanan</b></h2>
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
                            <div class="card-header">
                                <h3 class="card-title">Form Laporan Pesanan</h3>   
                            </div>
                            <!--begin::Body-->
                            <div class="card-body">
                                <!--begin: Form Filter-->
                                <h3 class="text-dark font-weight-bold mb-5">Tentukan Pencarian Laporan</h3>
                                <form class="form" id="report-filter-form" method="POST" action="#" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Periode Awal</label>
                                                <input type="date" name="awal" id="awal" class="form-control form-control-solid" value="{{ date('Y-m-d', strtotime($data->tanggal)) }}" placeholder="Masukkan Periode Awal" required />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Periode Akhir</label>
                                                <input type="date" name="akhir" id="akhir" class="form-control form-control-solid" value="{{ date('Y-m-d', strtotime($data->tanggal)) }}" placeholder="Masukkan Periode Akhir" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Nama Tenant/Toko</label><br>
                                                <select class="form-control form-control-solid" id="tenant" name="tenant" required>
                                                    <option name="tenant" value="all">Semua Merchant</option>
                                                    @foreach ($data->tenant as $item)
                                                            <option name="tenant" value="{{$item->kodetoko}}">{{$item->nama}}</option>
                                                        @endforeach
                                                </select>
                                                <script type="text/javascript">                                                
                                                    $(document).ready(function() {
                                                        $('#tenant').select2({                                   
                                                            placeholder: '-- Pilih Pengguna --',
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Status Kupon</label><br>
                                                <select class="form-control form-control-solid" id="status-kupon" name="status-kupon" required>
                                                    <option name="status-kupon" value="all">Semua Status Kupon</option>
                                                    <option name="status-kupon" value="1">Pake Kupon</option>
                                                    <option name="status-kupon" value="0">Tidak Pake Kupon</option>
                                                    
                                                </select>
                                                <script type="text/javascript">                                                
                                                    $(document).ready(function() {
                                                        $('#status-kupon').select2({                                   
                                                            placeholder: '-- Pilih Status Kupon --',
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Status Pesanan</label><br>
                                                <select class="form-control form-control-solid form-control" id="status-pesan" name="status-pesan" required>
                                                    <option name="status-pesan" value="all">Semua Status Pesanan</option>
                                                    <option name="status-pesan" value="0">Pesanan Gagal</option>
                                                    <option name="status-pesan" value="1">Pesanan Sukses</option>
                                                </select>
                                                <script type="text/javascript">                                                
                                                    $(document).ready(function() {
                                                        $('#status-pesan').select2({                                   
                                                            placeholder: '-- Pilih Status Pesanan --',
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>                                       
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <button id="view-btn" type="submit" class="btn btn-light-dark mt-8"><span class="fad fa-file-export"></span> Tampilkan</button>
                                        </div>
                                    </div>
                                </form>
                                <!--end: Form Filter-->
                                <hr>
                                <!--begin: Datatable-->
                                <table class="table table-hover table-hover nowrap" id="report-table" width="100%"></table>
                                <!--end: Datatable-->
                                
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                </div>


                <!-- begin: Detail  Modal-->
                <div class="modal fade bd-example-modal-lg" id="detail-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detail-modal-title">Modal Title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="report-top"></div>
                                <div id="report-detail-table-title" class="text-dark-50 font-size-lg font-weight-bold mb-1"></div>
                                <table id="report-detail-table" class="table table-striped" width="100%" style="width:100%"></table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end: Detail  Modal-->

            </div>
            <!--end::Container-->
        </div>         
    </div>

    <script>
        $(function () {
            var validation,
                
                btn_submit  = KTUtil.getById("view-btn"),
                table       = $('#report-table');
                table_detail= $("#report-detail-table"),

            validation = $('#report-filter-form').validate({
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

                submitHandler: function (form) {
                    var startDate  = $('#awal').val();
                    var endDate    = $('#akhir').val();
                    var tenant     = $('#tenant').val();
                    var kupon      = $('#status-kupon').val();
                    var pesan      = $('#status-pesan').val();

                    $.ajax({
                        type: "POST",
                        url: "{{ route('laporan.pesanan.tampilan') }}",
                        data: ({_token: token, periode_awal:startDate, periode_akhir:endDate, tenant:tenant, status_kupon:kupon, status_pesan:pesan}),
                        beforeSend: function ()
                        {
                            KTUtil.btnWait(btn_submit, "spinner spinner-white spinner-right pr-15", "<span class='fad fa-file-export'></span> Loading");
                        },
                        error: function (e) {
                            KTUtil.btnRelease(btn_submit);
                            swal.fire({
                                html: "Response error " + e,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "OK!",
                                customClass: {
                                    confirmButton: "btn font-weight-bold btn-light-primary"
                                }
                            });
                        },
                        complete: function () {
                            KTUtil.btnRelease(btn_submit);
                        },
                        success: function (result) {
                            if(result.status == 'success')
                            {
                                if ( $.fn.DataTable.isDataTable('#report-table') ) {
                                    $('#report-table').DataTable().destroy();
                                    $('#report-table tbody').empty();

                                    table.html(result.report.data);
                                }
                                else
                                {
                                    table.html(result.report.data);
                                }

                                table.DataTable({
                                    iDisplayLength: "All",
                                    lengthChange: false,
                                    scrollCollapse: true,
                                    scrollX: true,
                                    info: false,
                                    paginate: false,
                                    dom: `<'row'<'col-sm-8 text-left'B<"clear">><'col-sm-4 text-right'f>>
                                    <'row'<'col-sm-12'tr>>`,
                                    buttons: {
                                        buttons: [
                                            {
                                                extend: 'excelHtml5',
                                                title: 'Laporan Pesanan',
                                                text: "<span class='fad fa-file-excel'></span> Excel",
                                                exportOptions: {
                                                    columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                                                },
                                                messageTop: result.report.top_copy.periode + ' ' + result.report.top_copy.tenant + ' ' + result.report.top_copy.kupon + ' ' + result.report.top_copy.pesan
                                            },
                                            {
                                                text: "<span class='fad fa-file-pdf'></span> PDF",
                                                action: function ( e, dt, node, config ) {
                                                    PopupCenter('{{ route('laporan.pesanan.pdf') }}?periode_awal=' + startDate + '&periode_akhir=' + endDate + '&tenant=' + tenant + '&status_kupon=' + kupon + '&status_pesan=' + pesan, 'Laporan Pesanan' ,'1200', '800');
                                                }
                                            },
                                            {
                                                extend: 'colvis',
                                                text: "<span class='fad fa-columns'></span> Show/Hide Column"
                                            },
                                        ],
                                    },
                                    language: {
                                        url: "{{ asset('assets/js/datatables/language/Indonesia.json') }}"
                                    },
                                });

                                $('#export_excel').on('click', function(e) {
                                    e.preventDefault();
                                    table.button(2).trigger();
                                });
                            }
                            else
                            {
                                swal.fire({
                                    html: result.message,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "OK!",
                                    customClass: {
                                        confirmButton: "btn font-weight-bold btn-light-primary"
                                    }
                                });
                            }
                        }
                    });
                }
            });

            $(document).on('click', '#report-detail', function () {
                $("#detail-modal-title").text("Laporan Detail Pesanan");

                var id           = $(this).data("id");
                var tenant       = $(this).data("tenant");
                var kupon        = $(this).data("kupon");
                var pesan        = $(this).data("pesan");
                var startDate    = $(this).data("startdate");
                var endDate      = $(this).data("enddate");

                $.ajax({
                    type: "POST",
                    url: "{{ route('laporan.pesanan.detail.tampilan') }}",
                    data: ({_token: token, id: id, tenant: tenant, periode_awal: startDate, periode_akhir: endDate, status_kupon: kupon, status_pesan: pesan}),
                    beforeSend: function ()
                    {
                        KTUtil.btnWait(btn_submit, "spinner spinner-white spinner-right pr-15", "<span class='fad fa-file-export'></span> Loading");
                    },
                    error: function (e) {
                        KTUtil.btnRelease(btn_submit);
                        swal.fire({
                            html: "Response error " + e,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "OK!",
                            customClass: {
                                confirmButton: "btn font-weight-bold btn-light-primary"
                            }
                        });
                    },
                    complete: function () {
                        KTUtil.btnRelease(btn_submit);
                    },
                    success: function (result) {
                        if(result.status == 'success')
                        {
                            $('#report-top').html(result.report.top);
                            $('#report-detail-table-title').html("Laporan Detail Pesanan");

                            if ( $.fn.DataTable.isDataTable('#report-detail-table') ) {
                                $('#report-detail-table').DataTable().destroy();
                                $('#report-detail-table tbody').empty();

                                table_detail.html(result.report.data);
                            }
                            else
                            {
                                table_detail.html(result.report.data);
                            }


                            table_detail.DataTable({
                                iDisplayLength: "All",
                                lengthChange: false,
                                scrollCollapse: true,
                                responsive: true,
                                filter: false,
                                info: false,
                                paginate: false,
                                dom: 'B<"clear">lfrtip',
                                buttons: {
                                    buttons: [                                    
                                        {
                                            extend: 'excelHtml5',
                                            title: 'Laporan Detail Pesanan ',
                                            text: "<span class='fad fa-file-excel'></span> Excel",
                                            messageTop: result.report.top_copy.periode + ' - | - ' + result.report.top_copy.tenant + ' - | - ' + result.report.top_copy.kupon + ' - | - ' + result.report.top_copy.pesan
                                        },
                                        {
                                            text: "<span class='fad fa-file-pdf'></span> PDF",
                                            action: function ( e, dt, node, config ) {
                                                PopupCenter('{{ route('laporan.pesanan.detail.pdf') }}?id=' + id + '&periode_awal=' + startDate + '&periode_akhir=' + endDate + '&tenant=' + tenant + '&status_kupon=' + kupon + '&status_pesan=' + pesan, 'Laporan Detail Pesanan' ,'1200', '800');
                                            }
                                        }
                                    ],
                                },
                                language: {
                                    url: "{{ asset('assets/js/datatables/language/Indonesia.json') }}"
                                },
                            });

                            $('#detail-modal').on('shown.bs.modal', function () {
                                var table = $('#report-detail-table').DataTable();
                                table.columns.adjust().draw();
                            });

                            $('#export_excel').on('click', function(e) {
                                e.preventDefault();
                                table.button(2).trigger();
                            });

                            $("#detail-modal").modal("show");
                        }
                        else
                        {
                            swal.fire({
                                html: result.message,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "OK!",
                                customClass: {
                                    confirmButton: "btn font-weight-bold btn-light-primary"
                                }
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
