<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pesanan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <style>
        @page {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            margin-top: 1cm; margin-left: 1.5cm; margin-right: 1.5cm; margin-bottom: 1.5cm;
        }

        body {
            border: 1px solid #eee;
            color: #555;
        }

        header{
            top: -2.5cm;
            height: 60px;
            position: fixed;
            text-align: left;
        }

        header table {
            width: 100%;
            text-align: left;
            border: 1px solid #eee;
        }

        header table {
            width: 100%;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        header table td {
            padding: 5px;
            vertical-align: top;
        }

        header table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box {
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 10px;
            line-height: 16px;
        }

        .invoice-box table {
            width: 100%;
            padding: 10px;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: left;
        }

        .invoice-box table tr.top table td {
            font-size: 14px;
            line-height: 7px;
            text-align: right;
        }

        .invoice-box table tr.under-title th {
            color: #6E8192;
            font-size: 10px;
            text-align: left;
            vertical-align: middle;
        }

        .invoice-box table tr.under-item th {
            font-size: 12px;
            font-weight: bold;
            text-align: left;
            vertical-align: middle;
            padding-bottom: 5px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }

        .invoice-box table tr.heading td {
            background: rgb(85, 80, 80);
            border: 0.5px solid #000000;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td{
            border: 0.5px solid #000000;
        }

        .invoice-box table tr.total td {
            text-align: right;
            border: 0.5px solid #000000;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
<main class="invoice-box">
    <table class="table table-hover" cellpadding="0" cellspacing="0" style="align: center">
        <tr class="information">
            <td colspan="5">
                <table>
                    <tr>
                        <td>
                            <h1>Laporan Pesanan</h1> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>        
        <tr>
            <td colspan="2">Periode</td>
            <td colspan="2">: {{ App\Helpers\MyHelper::date_id($periode_awal, 'j F Y', '') }} s/d {{ App\Helpers\MyHelper::date_id($periode_akhir, 'j F Y', '') }}</td>
        </tr>
        <tr>
            <td colspan="2">Nama Merchant</td>
            <td colspan="2">: {{ $tenant_name }}</td>
        </tr>
        <tr>
            <td colspan="2">Status Pesanan</td>
            <td colspan="2">: {{ $status_pesan_name }}</td>
        </tr>

        <tr>
            <td colspan="16"></td>
        </tr>
        
        
        <tr class="heading">
            <th>No</th>
            <th>Nama Menu</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Sub Total</th>
        </tr>                                  
        @forelse ($reports as $report)                                            
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $report->namamenu }}</td>
                <td class="text-right">Rp. {{ number_format($report->harga,0,',','.') }}</td>
                <td class="text-right">{{ $report->qty }}</td>
                <td class="text-right">Rp. {{ number_format($report->subtotal,0,',','.') }}</td>
            </tr>                                            
        @empty
            <tr>
                <td colspan="5"><center>Data Belum Tersedia</center></td>
            </tr>
        @endforelse
    </table>
</main>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
