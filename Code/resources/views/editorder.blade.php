@extends('layoutCus')
@section('title')
    Editorder
@endsection
@section('contentClass', 'fixed-content')
@section('contents')
    <style>
        body {
            background: linear-gradient(to right, white 60%, #d49a60 40%);
        }

        table {
            margin: auto;
            border-collapse: collapse;
            width: 100%;

        }

        thead,
        tbody {
            width: 100%;
            display: table;
            table-layout: fixed;
        }

        thead {
            margin-left: 10%;
        }

        tbody {
            display: block;
            max-height: 355px;
            overflow-y: auto;
            white-space: nowrap;
        }

        th {
            position: sticky;
            padding-left: 3%;
            top: 0;

        }
    </style>
    </head>

    <body>
        <div class="modal fade" id="PurchaseDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content purchase" style="background: url('{{ asset('images/purchase.png') }}');">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel" style="margin-left: 180px">รายการสินค้า</h1>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body purchase">
                        <ul>

                            @foreach ($productData as $product)
                                @if ($product['quantity'] > 0)
                                    <li>
                                        <div class="item-list">
                                            <strong>{{ $product['name'] }}</strong>
                                            <div class="quanprice-modal">
                                                <span class="quan">{{ $product['quantity'] }}</span>
                                                <span class="price">{{ $product['price'] * $product['quantity'] }} ฿
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach

                        </ul>
                    </div>
                    <div class="total-price-modal">
                        <h5>ราคารวม : <strong>{{ number_format($totalPrice, 2) }} ฿</strong></h5>
                        <input id="sumPrice" type="text" value="{{ number_format($totalPrice, 2) }}" hidden>
                    </div>

                    <div class="modal-footer">
                        <button id="PP" type="button" class="btn btn-primary">PromptPay</button>
                        <button id="CalM" type="submit" class="btn btn-success">ชำระผ่านเงินสด</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="CalCash" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content purchase" style="background: url('{{ asset('images/purchase.png') }}');">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel" style="margin-left: 180px">รายการสินค้า</h1>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body purchase">
                        <ul>

                            @foreach ($productData as $product)
                                @if ($product['quantity'] > 0)
                                    <li>
                                        <div class="item-list">
                                            <strong>{{ $product['name'] }}</strong>
                                            <div class="quanprice-modal">
                                                <span class="quan">{{ $product['quantity'] }}</span>
                                                <span class="price">{{ $product['price'] * $product['quantity'] }} ฿</span>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach

                        </ul>
                        @if (session('orders'))
                            <div class="total-price-modal">
                                <h5>ราคารวม : <strong>{{ session('sumPrice') }} ฿</strong></h5>
                                <input id="sumPrice" type="text" value="{{ session('sumPrice') }}" hidden>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer Cal">
                        <div class="mb-3 row MoneyR">
                            <label class="col-sm-5 col-form-label">จำนวนเงินสด : </label>
                            <div class="col-sm-7 ib">
                                <input id="CashR" type="number" class="form-control">
                                <button id="CalBtn" class='bx bxs-calculator icon'></button>
                            </div>
                        </div>
                        <div class="Calcash">
                            <label class="col-sm-3 col-form-label">เงินทอน : </label>
                            <div class="col-sm-5 ib">
                                <input id="Cal" readonly type="text" class="form-control-plaintext">
                            </div>
                        </div>
                        <div class="modal-buttonCal">
                            <button id="CancelCal" type="button" class="btn btn-primary">ยกเลิก</button>
                            <button id="Cash" type="submit" class="btn btn-success">ยืนยัน</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="QR-show" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content QR" style="background: url('{{ asset('images/purchase.png') }}');">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body QR">
                        <img class="qrCode" id="qrI" src="" alt="qr-code">
                        <div class="total-price-modalQR">
                            <h5><strong>{{ number_format($totalPrice, 2) }} ฿</strong></h5>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="cancelQR" type="submit" class="btn btn-danger">ยกเลิก</button>
                        <button id="confirmQR" type="submit" class="btn btn-success">ยืนยัน</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="Order-Detail" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content purchase" style="background: url('{{ asset('images/purchase.png') }}');">
                    <div class="modal-header">
                        <div class="his-detail">
                            <h1 class="modal-title fs-5" id="order-id"></h1>
                            <span id="payment-type"></span>
                            <span id="order-idReq" hidden></span>
                            <span id="order-datetime"></span>
                        </div>
                    </div>
                    <div class="modal-body purchase">
                        <ul>
                            <li>
                                <div id="order-items"></div>
                            </li>
                        </ul>


                    </div>
                    <span id="total-price" class="total-price-modal H"></span>
                    <div class="modal-footer">
                        <button id="cancelQR" type="submit" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                        <button id="Invoice" type="submit" class="btn btn-success">พิมพ์ใบเสร็จ</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="Invoice-Data" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content purchase" style="background: url('{{ asset('images/purchase.png') }}');">
                    <form id="invoiceForm" method="POST" target="_blank">
                        @csrf
                        <div class="modal-header">
                            <h3>ข้อมูลลูกค้า</h3>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 row">
                                <label class="col-sm-1 col-form-label">ชื่อ</label>
                                <div class="col-sm-11">
                                    <input type="text" class="form-control" id="cusName" name="cusName"
                                        placeholder="ชื่อลูกค้า">
                                    <input type="text" class="form-control" id="cusUser" name="cusUser"
                                        value="{{ auth()->user()->name }}" hidden>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label">เลขประจำตัวผู้เสียภาษี (ตัวเลข 13 หลัก)</label>
                                    <input type="checkbox" id="noTaxID" class="me-3" onchange="toggleTaxID()">
                                    <label class="form-label mb-0">ไม่ระบุ</label>
                                </div>
                                <div class="col-sm-13">
                                    <input type="text" id="taxID" name="taxID" class="form-control tax"
                                        maxlength="13" minlength="13" pattern="[0-9]{13}"
                                        placeholder="เลขประจำตัวผู้เสียภาษี" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ที่อยู่</label>
                                <textarea class="form-control" id="cusAdd" name="cusAdd" rows="3"></textarea>
                            </div>
                            <input type="hidden" id="invoice-order-id" name="order_id">
                        </div>
                        <div class="modal-footer">
                            <button id="cancelQR" type="submit" class="btn btn-danger"
                                data-dismiss="modal">ปิด</button>
                            <button type="submit" class="btn btn-success">ยืนยัน</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="editorder">
            <div class="half-l bakery-list">
                <div class="text-headingF">เบเกอรี่</div>
                <div class="searchB" style="width: 25%">
                    <input type="text" name="findEditB" class="form-control" id="findEditB"
                        placeholder="ค้นหาเบเกอรี่...">
                </div>
                @foreach ($productData as $product)
                    <div class="bakery-card-con-edit">
                        <div class="bakery-card-edit" style="background: url('{{ asset('images/bakery_card.png') }}');">
                            <div class="card-detail-edit">
                                <img src="{{ asset('uploads/bakeries/' . $product['image']) }}"
                                    alt="{{ $product['name'] }}" width="200px">
                                <h3>{{ $product['name'] }}</h3>
                                <p>{{ 'B-' . str_pad($product['id'], 4, '0', STR_PAD_LEFT) }}</p>
                                <p>จำนวนสินค้า : {{ $product['quantity'] }}</p>
                                <h5>{{ $product['price'] }} ฿</h5>
                            </div>
                            <div class="addBakery">
                                <form action="{{ route('increaseQuantity') }}" method="POST" class="quantity-form">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $product['id'] }}">
                                    <input type="hidden" name="mask" value="+">
                                    <button type="submit" class="btndesign choose">เลือกสินค้านี้</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="half-r detect-detail">
                <div class="order-show">
                    <div class="order-list-edit">
                        <h5>รายการสินค้า</h5>
                        <table>
                            <thead>
                                <tr>
                                    <th>ชื่อสินค้า</th>
                                    <th>จำนวน</th>
                                    <th>ราคา</th>
                                </tr>
                            </thead>
                            <tbody>
                                <div class="item-list">
                                    @foreach ($productData as $product)
                                        @if ($product['quantity'] > 0)
                                            <tr id="order_row_{{ $product['id'] }}">
                                                <td class="product-name" style="padding-left: 10%">{{ $product['name'] }}
                                                </td>
                                                <td class="center-content" >
                                                    <div class="quanprice">
                                                        <div class="quantity-container">
                                                            <form action="{{ route('increaseQuantity') }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $product['id'] }}">
                                                                <input type="hidden" name="mask" value="-">
                                                                <button type="submit" class="button">-</button>
                                                            </form>
                                                            <span
                                                                id="quantity_display_{{ $product['id'] }}">{{ $product['quantity'] }}</span>
                                                            <form action="{{ route('increaseQuantity') }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $product['id'] }}">
                                                                <input type="hidden" name="mask" value="+">
                                                                <button type="submit" class="button">+</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td id="price_display_{{ $product['id'] }}" style="padding-left: 12%">
                                                    {{ $product['price'] * $product['quantity'] }} ฿
                                                </td>

                                            </tr>
                                        @endif
                                    @endforeach
                                </div>
                            </tbody>
                        </table>
                        <div class="total-order-price-edit">
                            <h5>ราคารวม: {{ number_format($totalPrice, 2) }} ฿</h5>
                        </div>

                    </div>
                    <div class="order-button-edit">
                        <form action="{{ route('detectP') }}" method="GET">
                            <button type="submit" class="btn btn-danger">ย้อนกลับไปถ่ายใหม่</button>
                        </form>
                        <button type="submit" class="btn btn-success toPurchase">ยืนยัน</button>

                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('.toPurchase').click(function(e) {
                    e.preventDefault();
                    $('#PurchaseDetail').modal('show');
                });

                $('#CalM').click(function(e) {
                    e.preventDefault();
                    $('#PurchaseDetail').modal('hide');
                    $('#CalCash').modal('show');

                    $('#CancelCal').click(function(e) {
                        e.preventDefault();
                        $('#CalCash').modal('hide');
                        $('#PurchaseDetail').modal('show');
                    });
                })

                $('#CalBtn').click(function(e) {
                    e.preventDefault();
                    var cashR = parseFloat($('#CashR').val());
                    var totalPrice = parseFloat($('#sumPrice').val());
                    var change = cashR - totalPrice;

                    $('#Cal').val(change.toFixed(2) + ' ฿');

                })

                $('#PP').click(function(e) {
                    e.preventDefault();
                    var sumPrice = $('#sumPrice').val();
                     _token: "{{ csrf_token() }}"
                    console.log(sumPrice);
                    
                    $.ajax({
                        type: "POST",
                        url: "{{ route('genQR') }}",
                        data: {
                            sumPrice: sumPrice
                        },
                        success: function(response) {
                            if (response.qrSrc) {
                                $('#PurchaseDetail').modal('hide');
                                $('#qrI').attr('src', response.qrSrc);

                                $('#QR-show').modal('show');
                                const qrUrl =
                                    `/qrcode-view?qrSrc=${encodeURIComponent(response.qrSrc)}&amount=${parseFloat(sumPrice)}`;
                                window.open(qrUrl, '_blank');
                            } else {
                                alert('Failed to generate QR Code. Please try again.');
                            }
                        },

                        error: function(error) {
                            console.error('AJAX Error:', error);
                        }

                    });
                    $('#cancelQR').click(function(e) {
                        e.preventDefault();
                        $('#QR-show').modal('hide');
                        $('#PurchaseDetail').modal('show');
                    });
                });

                $('#Invoice').click(function(e) {
                    e.preventDefault();

                    var orderId = $('#order-idReq').text();

                    $('#Order-Detail').modal('hide');

                    $('#Invoice-Data').find('#invoice-order-id').val(orderId);

                    $('#invoiceForm').attr('action', '/invoice/' + orderId);

                    $('#Invoice-Data').modal('show');
                });
            });

            $('#Cash').click(function(e) {
                var productData = @json($productData);
                e.preventDefault();

                let orderItems = [];
                productData.forEach(function(product) {
                    let item = {
                        id: product.id,
                        name: product.name,
                        quantity: product.quantity,
                        price: product.price,
                    };

                    if (item.quantity > 0) { // ตรวจสอบว่า quantity มากกว่า 0
                        orderItems.push(item);
                    }
                });

                $.ajax({
                    url: '/cashPay', 
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        items: orderItems
                    },
                    success: function(response) {
                        console.log(response);
                        $('#CalCash').modal('hide');

                        var savedOrder = response.savedOrder;

                        $('#order-id').text('Order ID: ' + padOrderID(savedOrder.BakeryOrder_ID));
                        $('#order-idReq').text(savedOrder.BakeryOrder_ID);

                        if (savedOrder.payment) {
                            $('#payment-type').text('รูปแบบการชำระเงิน: ' + savedOrder.payment
                                .Payment_Type);
                        } else {
                            $('#payment-type').text('รูปแบบการชำระเงิน: ไม่ระบุ');
                        }

                        $('#order-datetime').text('วันเวลา: ' + new Date(savedOrder.created_at)
                            .toLocaleString('th-TH', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            }) + ' น.');

                        var itemsList = $('#order-items');
                        itemsList.empty();
                        if (Array.isArray(savedOrder.order_items)) {
                            savedOrder.order_items.forEach(function(orderItem) {
                                // $('#order-items').append('<li>' + item.bakery.Bakery_name +
                                //     ', จำนวน: ' + item.Sum_quantity + ', ราคา: ' + item
                                //     .Sum_price + '</li>');
                                var itemContainer = $('<div class="item-list"></div>');

                                var bakeryName = $(
                                    '<strong><div class="bakery-name"></div></strong>').text(
                                    orderItem.bakery.Bakery_name);

                                var quanpriceModal = $('<div class="quanprice-modal"></div>');

                                var itemQuan = $('<span class="quan"></span>').text(orderItem
                                    .Sum_quantity);
                                var itemPrice = $('<span class="price"></span>').text(orderItem
                                    .Sum_price + ' ฿');

                                quanpriceModal.append(itemQuan);
                                quanpriceModal.append(itemPrice);

                                itemContainer.append(bakeryName);
                                itemContainer.append(quanpriceModal);

                                itemsList.append(itemContainer);
                            });
                        } else {
                            $('#order-items').append('<li>ไม่มีข้อมูลสินค้า</li>');
                        }
                        $('#total-price').text('ราคารวม: ' + savedOrder.Total_price.toFixed(2) + ' ฿');

                        $('#Order-Detail').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });

            });
            $('#confirmQR').click(function(e) {
                var productData = @json($productData);
                e.preventDefault();

                let orderItems = [];
                productData.forEach(function(product) {
                    let item = {
                        id: product.id,
                        name: product.name,
                        quantity: product.quantity,
                        price: product.price,
                    };

                    if (item.quantity > 0) { 
                        orderItems.push(item);
                    }
                });


                $.ajax({
                    url: '/PromptPay', 
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        items: orderItems 
                    },
                    success: function(response) {
                        console.log(response);
                        $('#QR-show').modal('hide');

                        var savedOrder = response.savedOrder;

                        $('#order-id').text('Order ID: ' + padOrderID(savedOrder.BakeryOrder_ID));
                        $('#order-idReq').text(savedOrder.BakeryOrder_ID);

                        if (savedOrder.payment) {
                            $('#payment-type').text('รูปแบบการชำระเงิน: ' + savedOrder.payment
                                .Payment_Type);
                        } else {
                            $('#payment-type').text('รูปแบบการชำระเงิน: ไม่ระบุ');
                        }

                        $('#order-datetime').text('วันเวลา: ' + new Date(savedOrder.created_at)
                            .toLocaleString('th-TH', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            }) + ' น.');

                        var itemsList = $('#order-items');
                        itemsList.empty();
                        if (Array.isArray(savedOrder.order_items)) {
                            // savedOrder.order_items.forEach(function(item) {
                            //     $('#order-items').append('<li>' + item.bakery.Bakery_name +
                            //         ', จำนวน: ' + item.Sum_quantity + ', ราคา: ' + item
                            //         .Sum_price + '</li>');
                            savedOrder.order_items.forEach(function(orderItem) {
                                // $('#order-items').append('<li>Bakery Name: ' + item.bakery.Bakery_name + ', Quantity: ' + item.Sum_quantity + ', Price: ' + item.Sum_price + '</li>');
                                var itemContainer = $('<div class="item-list"></div>');

                                var bakeryName = $(
                                    '<strong><div class="bakery-name"></div></strong>').text(
                                    orderItem.bakery.Bakery_name);

                                var quanpriceModal = $('<div class="quanprice-modal"></div>');

                                var itemQuan = $('<span class="quan"></span>').text(orderItem
                                    .Sum_quantity);
                                var itemPrice = $('<span class="price"></span>').text(orderItem
                                    .Sum_price + ' ฿');

                                quanpriceModal.append(itemQuan);
                                quanpriceModal.append(itemPrice);

                                itemContainer.append(bakeryName);
                                itemContainer.append(quanpriceModal);

                                itemsList.append(itemContainer);
                            });
                        } else {
                            $('#order-items').append('<li>ไม่มีข้อมูลสินค้า</li>');
                        }
                        $('#total-price').text('ราคารวม: ' + savedOrder.Total_price.toFixed(2));

                        $('#Order-Detail').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            });

            function padOrderID(orderID) {
                let orderIDStr = orderID.toString();

                while (orderIDStr.length < 4) {
                    orderIDStr = '0' + orderIDStr;
                }

                return 'OR-' + orderIDStr;
            }

            function toggleTaxID() {
                var isChecked = $('#noTaxID').is(':checked'); 
                $('#taxID').prop('disabled', isChecked); 
                if (isChecked) {
                    $('#taxID').val(''); 
                }
            }

            $('#noTaxID').change(function() {
                toggleTaxID();
            });
            $(document).ready(function() {
                $('#findEditB').on('input', function() {
                    var searchTerm = $(this).val().toLowerCase(); 

                    $('.bakery-card-con-edit').each(function() {
                        var productName = $(this).find('h3').text()
                            .toLowerCase(); 

                        if (productName.includes(searchTerm)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });
            });
        </script>

    </body>
@endsection
