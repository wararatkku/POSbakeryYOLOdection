<!DOCTYPE html>
<html lang="en">
<head>
    {{-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> --}}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{$InvoiceOR->BakeryOrder_ID}}</title>
    

    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
        }
        html,
        body {
            margin: 10px;
            padding: 10px;
            font-family: "THSarabunNew", sans-serif;
        }
        h1,h2,h3,h4,h5,h6,p,span,label {
            font-family: "THSarabunNew", sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px !important;
        }
        table thead th {
            height: 28px;
            text-align: left;
            font-size: 16px;
            font-family: "THSarabunNew", sans-serif;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
        }

        .heading {
            font-size: 24px;
            margin-top: 12px;
            margin-bottom: 12px;
            font-family: "THSarabunNew", sans-serif;
        }
        .small-heading {
            font-size: 18px;
            font-family: "THSarabunNew", sans-serif;
        }
        .total-heading {
            font-size: 18px;
            font-weight: 700;
            font-family: "THSarabunNew", sans-serif;
        }
        .order-details tbody tr td:nth-child(1) {
            width: 20%;
        }
        .order-details tbody tr td:nth-child(3) {
            width: 20%;
        }

        .text-start {
            text-align: left;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .company-data span {
            margin-bottom: 4px;
            display: inline-block;
            font-family: "THSarabunNew", sans-serif;
            font-size: 14px;
            font-weight: 400;
        }
        .no-border {
            border: 1px solid #fff !important;
        }
        .bg-line {
            background-color: #BA9269;
            color: #fff;
        }
        .topline th{
            border: none;
        }
        .topline {
            background-color: #BA9269;
            color: #fff;
        }
    </style>
</head>
<body>
    <form id="download-form" action="{{ route('printInvoice', ['order_id' => $InvoiceOR->BakeryOrder_ID]) }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="cusName" value="{{ $cusName }}">
        <input type="hidden" name="cusAdd" value="{{ $cusAdd }}">
        <input type="hidden" name="cusUser" value="{{ $cusUser }}">
    </form>
    <table class="order-details">
        <thead>
            <tr class="topline">
                <th width="50%" colspan="2">
                    <img src="https://img.freepik.com/free-vector/illustration-bakery-house-stamp-banner_53876-6838.jpg?w=740&t=st=1703609652~exp=1703610252~hmac=2bfe80cbf9a43f5da83d1f93301cfba3e1241071235a621d2af76dfe6351d32e" alt="logo" style="height:100px; margin-left: 10px;">
                </th>
                <th width="50%" colspan="2" class="text-end company-data">
                    <span>หมายเลขใบเสร็จ: #{{ $InvoiceOR->BakeryOrder_ID }}</span> <br>
                    <span>วันที่: {{ date('d / m / Y') }}</span> <br>
                    <span>ชื่อพนักงาน: {{ $cusUser }}</span> <br>
                </th>
            </tr>
            <tr >
                <th width="50%" colspan="2">รายละเอียดคำสั่งซื้อ</th>
                <th width="50%" colspan="2">ข้อมูลลูกค้า</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>หมายเลขคำสั่งซื้อ:</td>
                <td>{{ 'OR-' . str_pad($InvoiceOR->BakeryOrder_ID, 4, '0', STR_PAD_LEFT) }}</td>

                <td>ชื่อ:</td>
                <td>{{ $cusName }}</td>
            </tr>
            <tr>
                <td>วันที่สั่งซื้อ:</td>
                <td>{{ $InvoiceOR->created_at->format('d/m/Y H:i:s') }}</td>
                
                <td style="border: 1px solid #ddd;">เลขประจำตัวผู้เสียภาษี:</td>
                <td style="border: 1px solid #ddd;">{{ $taxID }}</td>

                {{-- <td>หมายเลขโทรศัพท์:</td>
                <td>8889997775</td> --}}
            </tr>
            <tr>
                <td>รูปแบบการชำระเงิน:</td>
                <td style="border: 1px solid #ddd;">{{ $InvoiceOR->payment->Payment_Type }}</td>

                <td style="border: 1px solid #ddd;">ที่อยู่:</td>
                <td style="border: 1px solid #ddd;">{{ $cusAdd }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th class="no-border text-start heading" colspan="5">
                    รายการสินค้า
                </th>
            </tr>
            <tr class="bg-line">
                <th>ลำดับ</th>
                <th>สินค้า</th>
                <th>ราคา</th>
                <th>จำนวนสินค้า</th>
                <th>ราคารวม</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($InvoiceOR->orderItems as $item)
            <tr style="border: 1px solid #ddd;">
                <td width="10%">{{$loop->iteration}}</td>
                <td>{{$item->bakery->Bakery_name}}</td>
                <td width="10%">{{$item->bakery->Bakery_price}}</td>
                <td width="10%">{{$item->Sum_quantity}}</td>
                <td width="15%" class="fw-bold">{{$item->Sum_price}} บาท</td>
            </tr>
            @endforeach
            <tr class="bg-line">
                <td colspan="2" class="total-heading" style="visibility: hidden; border:none;"></td>
                <td colspan="2" class="total-heading">ราคารวมทั้งสิ้น :</td>
                <td colspan="1" class="total-heading">{{ $InvoiceOR->Total_price }} บาท</td>
            </tr>
        </tbody>
    </table>

    <br>
    <p class="text-center">
        ขอบคุณที่ใช้บริการ
    </p>
    <script>
        window.onload = function() {
            document.getElementById('download-form').submit();
        };
    </script>
</body>
</html>