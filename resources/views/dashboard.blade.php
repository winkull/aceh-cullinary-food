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
                        <h2>Dashboard</h2>
                        <!--end::Page Title-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <!--end::Info-->
            </div>
        </div>
        <!--end::Subheader-->
        <!--begin::Subheader-->
        <div class="d-flex align-items-center">
                    <div class="container-fluid">
                        <div class="row">                    
                            <div class="col">
                                <!--begin::Card-->
                                <div class="card card-custom card-border">
                                    <div class="card-header ribbon ribbon-top">
                                        <div class="card-title align-items-start flex-column" style="margin-top: 15px;">
                                            <h3 class="card-label font-weight-bolder text-dark">Dashboard</h3>
                                            <span class="text-muted font-weight-bold font-size-sm mt-1">Menampilkan jumlah data Aceh Food Cullinary</span>                                            
                                        </div>
                                        <!--begin::Toolbar-->
                                        <div class="card-toolbar" id="myDIV">
                                            <!--begin::Actions-->
                                            <a class="btn btn-sm btn-secondary active" id="hari_ini"><span class="font-weight-bold font-size-base">Hari Ini</span></a>&nbsp
                                            <a class="btn btn-sm btn-secondary" id="minggu_ini"><span class="font-weight-bold font-size-base">Minggu Ini</span></a>&nbsp
                                            <a class="btn btn-sm btn-secondary" id="bulan_ini"><span class="font-weight-bold font-size-base">Bulan Ini</span></a>
                                            <!--end::Actions-->                                                
                                        </div>
                                        <!--end::Toolbar-->
                                    </div>
                                    <!--begin::Body-->
                                    <div class="card-body">
                                        <!--begin: Datatable-->
                                        <div class="col">
                                            <div class="row justify-content-center align-items-center">
                                                <div class="card border-dark mb-3" style="width: 15%;">
                                                    <div class="text-center"  style="padding-top: 5%;"><img src="{{asset('assets/media/logos/toko.png')}}" width="50%"></div>
                                                    <div class="title wide text-center">Jumlah Merchant</div>
                                                    <div class="text-center"><p class="font-weight-bold" style="font-size: 1.5vw">{{$toko}}</p></div>
                                                </div>
                                                <div class="card border-dark mb-3" style="width: 15%; margin-left: 1%;">
                                                    <div class="text-center"  style="padding-top: 5%;"><img src="{{asset('assets/media/logos/menu.png')}}" width="50%"></div>
                                                    <div class="title wide text-center">Jumlah Menu</div>
                                                    <div class="text-center"><p class="font-weight-bold" style="font-size: 1.5vw">{{$menu}}</p></div>
                                                </div>
                                                <div class="card border-dark mb-3" style="width: 15%; margin-left: 1%;">
                                                    <div class="text-center"  style="padding-top: 5%;"><img src="{{asset('assets/media/logos/pengguna.png')}}" width="50%"></div>
                                                    <div class="title wide text-center">Jumlah Pengguna</div>
                                                    <div class="text-center"><p class="font-weight-bold" style="font-size: 1.5vw">{{$pengguna}}</p></div>
                                                </div>                                                
                                                <div class="card border-dark mb-3" style="width: 15%; margin-left: 1%;">
                                                    <div class="text-center"  style="padding-top: 5%;"><img src="{{asset('assets/media/logos/driver.png')}}" width="50%"></div>
                                                    <div class="title wide text-center">Jumlah Driver</div>
                                                    <div class="text-center"><p class="font-weight-bold" style="font-size: 1.5vw">{{$driver}}</p></div>
                                                </div>
                                            </div><br><hr><br>
                                            <div class="row justify-content-center align-items-center">
                                                <h3 class="card-label font-weight-bolder text-dark text-center">Jumlah dan Total Transaksi</h3>
                                            </div>
                                            <div class="row justify-content-center align-items-center">
                                                <div class="card card-custom card-border" style="width: 32%;">
                                                    <div class="card-body">
                                                        <h5 class="card-label font-weight-bolder text-dark text-center">Hari ini</h5>
                                                        <div class="row justify-content-center align-items-center">
                                                            <div class="card border-dark mb-3" style="width: 100%;">
                                                                <div class="row" style="padding-top: 5%; padding-right: 3%; padding-left: 3%; padding-bottom: 3%;">
                                                                    <div class="col" style="margin-left: -8%">
                                                                        <img src="{{asset('assets/media/logos/total.png')}}" width="100%">
                                                                    </div>
                                                                    <div class="col" style="margin-left: -18%">
                                                                        <div class="font-weight-bolder">Transaksi</div>
                                                                        <span class="text-left">Jumlah</span><br>
                                                                        <div class="text-left font-weight-bold" style="font-size: 1.5vw;">{{$jumlah_hari}} X</div>
                                                                        <span class="text-left">Total</span>
                                                                        <div class="text-right font-weight-bold" style="font-size: 1.5vw; margin-left: -40%">{{$total_transaksi_hari}}</div>
                                                                    </div>        
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card card-custom card-border" style="width: 32%; margin-left: 1%;">
                                                    <div class="card-body">
                                                        <h5 class="card-label font-weight-bolder text-dark text-center">Minggu ini</h5>
                                                        <div class="row justify-content-center align-items-center">
                                                            <div class="card border-dark mb-3" style="width: 100%;">
                                                                <div class="row" style="padding-top: 5%; padding-right: 3%; padding-left: 3%; padding-bottom: 3%;">
                                                                    <div class="col" style="margin-left: -8%">
                                                                        <img src="{{asset('assets/media/logos/total.png')}}" width="100%">
                                                                    </div>
                                                                    <div class="col" style="margin-left: -18%">
                                                                        <div class="font-weight-bolder">Transaksi</div>
                                                                        <span class="text-left">Jumlah</span><br>
                                                                        <div class="text-left font-weight-bold" style="font-size: 1.5vw;">{{$jumlah_minggu}} X</div>
                                                                        <span class="text-left">Total</span>
                                                                        <div class="text-right font-weight-bold" style="font-size: 1.5vw; margin-left: -40%">{{$total_transaksi_minggu}}</div>
                                                                    </div>        
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card card-custom card-border" style="width: 32%; margin-left: 1%;">
                                                    <div class="card-body">
                                                        <h5 class="card-label font-weight-bolder text-dark text-center">Bulan ini</h5>
                                                        <div class="row justify-content-center align-items-center">
                                                            <div class="card border-dark mb-3" style="width: 100%;">
                                                                <div class="row" style="padding-top: 5%; padding-right: 3%; padding-left: 3%; padding-bottom: 3%;">
                                                                    <div class="col" style="margin-left: -8%">
                                                                        <img src="{{asset('assets/media/logos/total.png')}}" width="100%">
                                                                    </div>
                                                                    <div class="col" style="margin-left: -18%">
                                                                        <div class="font-weight-bolder">Transaksi</div>
                                                                        <span class="text-left">Jumlah</span><br>
                                                                        <div class="text-left font-weight-bold" style="font-size: 1.5vw;">{{$jumlah_bulan}} X</div>
                                                                        <span class="text-left">Total</span>
                                                                        <div class="text-right font-weight-bold" style="font-size: 1.5vw; margin-left: -40%">{{$total_transaksi_bulan}}</div>
                                                                    </div>        
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br><hr><br>

                                            <div class="row">
                                                <div class="card card-custom card-border" style="width: 49%;">
                                                    <div class="card-header ribbon ribbon-top">
                                                        <div class="card-title align-items-start flex-column" style="margin-top: 15px;">
                                                            <h3 class="card-label font-weight-bolder text-dark">Top 10 Merchant dengan Penjualan Tebanyak</h3>
                                                            <span class="text-muted font-weight-bold font-size-sm mt-1">Menampilkan jumlah data Merchant</span>                                            
                                                        </div>
                                                    </div>
                                                    <!--begin::Body-->
                                                    <div class="card-body">
                                                        <!--begin: Datatable-->
                                                        <table class="table table-head-custom nowrap" id="merchant-default" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>No Urut</th>
                                                                    <th>Nama Merchant</th>
                                                                    <th>Jumlah Pemesan</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>       
                                                        <!--end: Datatable-->
                                                    </div>                                    
                                                    <!--end::Body-->
                                                </div>
                                                <div class="card card-custom card-border" style="width: 49%; margin-left: 2%;">
                                                    <div class="card-header ribbon ribbon-top">
                                                        <div class="card-title align-items-start flex-column" style="margin-top: 15px;">
                                                            <h3 class="card-label font-weight-bolder text-dark">Top 10 Merchant Total Penjualan Terbanyak</h3>
                                                            <span class="text-muted font-weight-bold font-size-sm mt-1">Menampilkan jumlah data Merchant</span>                                            
                                                        </div>
                                                    </div>
                                                    <!--begin::Body-->
                                                    <div class="card-body">
                                                        <!--begin: Datatable-->
                                                        <table class="table table-head-custom nowrap" id="merchants-default" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>No Urut</th>
                                                                    <th>Nama Merchant</th>
                                                                    <th>Total Pemesan</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>        
                                                        <!--end: Datatable-->
                                                    </div>                                    
                                                    <!--end::Body-->
                                                </div>
                                            </div><br><hr><br>

                                            <div class="row">
                                                <div class="card card-custom card-border" style="width: 49%;">
                                                    <div class="card-header ribbon ribbon-top">
                                                        <div class="card-title align-items-start flex-column" style="margin-top: 15px;">
                                                            <h3 class="card-label font-weight-bolder text-dark">Top 10 Menu Paling Laku</h3>
                                                            <span class="text-muted font-weight-bold font-size-sm mt-1">Menampilkan jumlah top menu paling laku</span>                                            
                                                        </div>
                                                    </div>
                                                    <!--begin::Body-->
                                                    <div class="card-body">
                                                        <!--begin: Datatable-->
                                                        <table class="table table-head-custom nowrap" id="menu-default" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>No Urut</th>
                                                                    <th>Nama Menu</th>
                                                                    <th>Merchant</th>
                                                                    <th>Jumlah</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                        <!--end: Datatable-->
                                                    </div>                                    
                                                    <!--end::Body-->
                                                </div>
                                                <div class="card card-custom card-border" style="width: 49%;margin-left: 2%;">
                                                    <div class="card-header ribbon ribbon-top">
                                                        <div class="card-title align-items-start flex-column" style="margin-top: 15px;">
                                                            <h3 class="card-label font-weight-bolder text-dark">Top 10 Kontributor Driver</h3>
                                                            <span class="text-muted font-weight-bold font-size-sm mt-1">Menampilkan top 10 Kontributor Driver</span>                                            
                                                        </div>
                                                    </div>
                                                    <!--begin::Body-->
                                                    <div class="card-body">
                                                        <!--begin: Datatable-->
                                                        <table class="table table-head-custom nowrap" id="driver-default" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>No Urut</th>
                                                                    <th>Nama Driver</th>
                                                                    <th>Merchant</th>
                                                                    <th>Jumlah</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                        <!--end: Datatable-->
                                                    </div>                                    
                                                    <!--end::Body-->
                                                </div>
                                            </div>

                                        </div>                                        
                                        <!--end: Datatable-->
                                    </div>                                    
                                    <!--end::Body-->
                                </div>
                                <!--end::Card-->
                            </div>
                        </div>
                    </div>
                </div>
        <!--end::Subheader-->

        <script type="text/javascript">
            var header = document.getElementById("myDIV");
            var btns = header.getElementsByClassName("btn");
            for (var i = 0; i < btns.length; i++) {
              btns[i].addEventListener("click", function() {
              var current = document.getElementsByClassName("active");
              current[0].className = current[0].className.replace(" active", "");
              this.className += " active";
              });
            }
        </script>
    </div>

    <script>
        $(document).ready(function () {
            var validation,
                tableMerchantDefault    = $('#merchant-default'),
                tableMerchantsDefault   = $('#merchants-default'),
                tableMenuDefault        = $('#menu-default'),
                tableDriverDefault      = $('#driver-default');

            function loadTableMerchantDefault(url)
            {
                tableMerchantDefault.DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    aLengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ],
                    iDisplayLength: 10,
                    scrollY: '60vh',
                    scrollX: true,
                    scrollCollapse: true,
                    ajax:{
                        "url": url,
                        "dataType": "json",
                        "type": "POST",
                        "data":{ _token: token},                    
                    },
                    language: {
                        url: "{{ asset('assets/js/datatables/language/Indonesia.json') }}"
                    },
                    order: [[2, "desc"]],
                    columns: [
                        { data: 'no' },
                        { data: 'nama' },
                        { data: 'jumlah' },
                    ],
                });
            }
            
            loadTableMerchantDefault("{{ route('dashboard.merchant.jumlah') }}");

            function loadTableMerchantsDefault(url)
            {
                tableMerchantsDefault.DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    aLengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ],
                    iDisplayLength: 10,
                    scrollY: '60vh',
                    scrollX: true,
                    scrollCollapse: true,
                    ajax:{
                        "url": url,
                        "dataType": "json",
                        "type": "POST",
                        "data":{ _token: token},                    
                     },
                    language: {
                        url: "{{ asset('assets/js/datatables/language/Indonesia.json') }}"
                    },
                    order: [[2, "desc"]],
                    columns: [
                        { data: 'no' },
                        { data: 'nama' },
                        { data: 'total' },
                    ],
                });
            }
            
            loadTableMerchantsDefault("{{ route('dashboard.merchant.total') }}");
            
            function loadTableMenuDefault(url)
            {
                tableMenuDefault.DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    aLengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ],
                    iDisplayLength: 10,
                    scrollY: '60vh',
                    scrollX: true,
                    scrollCollapse: true,
                    ajax:{
                        "url": url,
                        "dataType": "json",
                        "type": "POST",
                        "data":{ _token: token},                    
                    },
                    language: {
                        url: "{{ asset('assets/js/datatables/language/Indonesia.json') }}"
                    },
                    order: [[3, "desc"]],
                    columns: [
                        { data: 'no' },
                        { data: 'nama-menu' },
                        { data: 'nama' },
                        { data: 'jumlah' },
                    ],
                });
            }
            
            loadTableMenuDefault("{{ route('dashboard.menu.jumlah') }}");

            function loadTableDriverDefault(url)
            {
                tableDriverDefault.DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    aLengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ],
                    iDisplayLength: 10,
                    scrollY: '60vh',
                    scrollX: true,
                    scrollCollapse: true,
                    ajax:{
                        "url": url,
                        "dataType": "json",
                        "type": "POST",
                        "data":{ _token: token},                    
                    },
                    language: {
                        url: "{{ asset('assets/js/datatables/language/Indonesia.json') }}"
                    },
                    order: [[3, "desc"]],
                    columns: [
                        { data: 'no' },
                        { data: 'nama-driver' },
                        { data: 'nama' },
                        { data: 'jumlah' },
                    ],
                });
            }
            
            loadTableDriverDefault("{{ route('dashboard.driver.jumlah') }}");

            $("#hari_ini").click(function(){
                loadTableMerchantDefault("{{ route('dashboard.merchant.jumlah') }}");
                
                loadTableMerchantsDefault("{{ route('dashboard.merchant.total') }}");
                
                loadTableMenuDefault("{{ route('dashboard.menu.jumlah') }}");
                
                loadTableDriverDefault("{{ route('dashboard.driver.jumlah') }}");
            });

            $("#minggu_ini").click(function(){
                loadTableMerchantDefault("{{ route('dashboard.merchant.jumlah.minggu') }}");
                
                loadTableMerchantsDefault("{{ route('dashboard.merchant.total.minggu') }}");
                
                loadTableMenuDefault("{{ route('dashboard.menu.jumlah.minggu') }}");
                
                loadTableDriverDefault("{{ route('dashboard.driver.jumlah.minggu') }}");
            });

            $("#bulan_ini").click(function(){
                loadTableMerchantDefault("{{ route('dashboard.merchant.jumlah.bulan') }}");
                
                loadTableMerchantsDefault("{{ route('dashboard.merchant.total.bulan') }}");
                
                loadTableMenuDefault("{{ route('dashboard.menu.jumlah.bulan') }}");
                
                loadTableDriverDefault("{{ route('dashboard.driver.jumlah.bulan') }}");
            });
        });
    </script>

    
@endsection
