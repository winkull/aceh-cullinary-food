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
                        <h2 class="text-dark font-weight-bold my-1 mr-5"><b>Laporan Pengguna</b></h2>
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
                                <h3 class="card-title">Form Laporan Pengguna</h3>   
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
                                                <label>Periode Daftar Awal</label>
                                                <input type="date" name="awal" id="awal" class="form-control form-control-solid" value="{{ date('Y-m-d', strtotime($data->tanggal)) }}" placeholder="Masukkan Periode Awal" required />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Periode Daftar Akhir</label>
                                                <input type="date" name="akhir" id="akhir" class="form-control form-control-solid" value="{{ date('Y-m-d', strtotime($data->tanggal)) }}" placeholder="Masukkan Periode Akhir" required />
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

            </div>
            <!--end::Container-->
        </div>         
    </div>

    <script>
        $(function () {
            var validation,
                
                btn_submit  = KTUtil.getById("view-btn"),
                table       = $('#report-table');

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

                    $.ajax({
                        type: "POST",
                        url: "{{ route('laporan.pengguna.tampilan') }}",
                        data: ({_token: token, periode_daftar_awal:startDate, periode_daftar_akhir:endDate}),
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
                                                extend: 'print',
                                                title: 'Laporan Daftar Pengguna ',
                                                text: "<span class='fad fa-print'></span> Print",
                                                messageTop: result.report.top,
                                            },
                                            {
                                                extend: 'excelHtml5',
                                                title: 'Laporan Daftar Pengguna',
                                                text: "<span class='fad fa-file-excel'></span> Excel",
                                                exportOptions: {
                                                    columns: [ 0, 1, 2, 3, 4, 5, 6]
                                                },
                                                messageTop: result.report.top_copy.periode,
                                            },
                                            {
                                                text: "<span class='fad fa-file-pdf'></span> PDF",
                                                action: function ( e, dt, node, config ) {
                                                    PopupCenter('{{ route('laporan.pengguna.pdf') }}?periode_daftar_awal=' + startDate + '&periode_daftar_akhir=' + endDate , 'Laporan Daftar Pengguna' ,'1200', '800');
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

                                $('#export_print').on('click', function(e) {
                                    e.preventDefault();
                                    table.button(0).trigger();
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
        });
    </script>
@endsection
