@extends('layoutCus')
@section('title')
    DetectPage
@endsection
@section('contentClass', 'fixed-content')
@section('contents')
    <style>
        body {
            background: linear-gradient(to right, white 60%, #d49a60 40%);
        }

        /* .table-wrapper {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100%;
                } */

        table {
            width: 100%;
        }

        th,
        td {
            padding: 16px;
        }

        tbody {
            text-align: left;
        }

        tr {
            text-align: center;
        }

        /* .table-wrapperor {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                } */

        td.product,
        td.quantity,
        td.price {
            text-align: left;
        }

        #data-table td.price-column::after {
            content: ' ฿';
        }

        #data-tableor td.price-column::after {
            content: ' ฿';
        }

        .items-list {
            margin: 10px 0;
        }

        .items-list .item {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 5px;
            font-weight: bold;
        }

        .item-name {
            flex: 3;
            /* ชื่อสินค้าชิดซ้าย */
        }

        .item-quantity {
            text-align: center;
            /* ชิดกลาง */
            flex: 1;
            /* ให้มีขนาดเท่ากัน */
        }

        .item-price {
            text-align: right;
            /* ชิดขวา */
            flex: 1;
            /* ให้มีขนาดเท่ากัน */
            padding-right: 40px;

        }
    </style>

    </head>

    <body>

        <div class="detectpage">
            <div class="half-l cam">
                <div class="webcam-scan">
                    <video id="video" width="640" height="480" autoplay></video>
                    <canvas id="canvas" width="640" height="480" style="display:none;"></canvas>
                    <img id="result" width="640" height="480" />
                </div>

            </div>
            <div class="half-r detect-detail">
                <div class="order">
                    <div class="order-list">
                        <h5>รายการสินค้า</h5>
                        <form id="product-form" action="{{ url('editorder') }}" method="POST">
                            @csrf
                            <div class="table-wrapper">
                                <table class="table-listDt">
                                    <thead>
                                        <tr>
                                            <th>ชื่อสินค้า</th>
                                            <th>จำนวน</th>
                                            <th>ราคา</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-table"></tbody>
                                </table>

                            </div>

                            <div class="total-order-price" id="total-price" style="text-align: center; margin-top: 10px;">
                                <h5>ราคารวม: 0 ฿</h5>
                            </div>
                    </div>
                </div>
                <div class="cambutton" style="margin-top: 30px;">
                    <div class="circleshape">
                        <button class="btn-capture" id="capture" type="button"
                            style="background-color: transparent; border: none;">
                            <i class="bx bxs-camera icon"></i>
                        </button>
                    </div>
                    <div class="circleshape">
                        <button class="btn-refresh" onClick="window.location.reload()" type="button"
                            style="background-color: transparent; border: none;">
                            <i class="bx bx-refresh icon"></i>
                        </button>
                    </div>
                    <div class="circleshape">
                        <button class="btn-editorder" type="submit" style="background-color: transparent; border: none;">
                            <i class='bx bxs-edit icon'></i>
                        </button>
                    </div>
                </div>
                </form>

                <div class="order-button">
                    <a href="/">
                        <button class="btn btn-danger">ยกเลิก</button>
                    </a>
                    <button type="submit" class="btn btn-success toPurchase">ชำระเงิน</button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="PurchaseDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content purchase" style="background: url('{{ asset('images/purchase.png') }}');">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel" style="margin-left: 180px">รายการสินค้า</h1>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body purchase">
                        <div class="table-wrapperor">
                            <table class="table-listOr">
                                <tbody id="data-tableor">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="total-price-modal">
                        <h5><strong></strong></h5>
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
                            <li>
                                <div id="calCashItems" class="items-list"></div>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer Cal">
                        <div class="mb-3 row MoneyR">
                            <label class="col-sm-5 col-form-label">จำนวนเงิน : </label>
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
                            <h5><strong></strong></h5>
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

                        {{-- <span id="total-price" class="total-price-modal H"></span> --}}
                    </div>
                    <div id="total-price" class ="total-price-modal H">
                        <h5><strong></strong></h5>
                    </div>
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

        <script>
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const resultImage = document.getElementById('result');
            const context = canvas.getContext('2d');
            const detectionResults = document.getElementById('detection-results');
            const datatable = document.getElementById('data-table');
            const datatableorder = document.getElementById('data-tableor');
            let isWebSocketOpen = false;

            // เข้าถึงกล้อง
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(stream => {
                    video.srcObject = stream;
                    video.play();
                })
                .catch(err => {
                    console.error("ไม่สามารถเข้าถึงกล้องได้: ", err);
                });

            const socket = new WebSocket('ws://localhost:8765');

            socket.onopen = () => {
                console.log('WebSocket connected');
                isWebSocketOpen = true;
                setInterval(() => {
                    if (isWebSocketOpen) {
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);
                        canvas.toBlob(blob => { //แปลงภาพเป็นPNG
                            const reader = new FileReader();
                            reader.onloadend = () => {
                                const base64data = reader.result.split(',')[1];
                                if (isWebSocketOpen) {
                                    socket.send(base64data);
                                } else {
                                    console.log('WebSocket is not open');
                                }
                            };
                            reader.readAsDataURL(blob);
                        }, 'image/png');
                    }
                }, 500 / 1); // ส่งภาพทุก 1/0.5 วินาที (ประมาณ 2 fps)
            };

            socket.onmessage = event => {
                const response = JSON.parse(event.data);
                if (typeof response === 'object' && response !== null) {
                    const url = 'data:image/png;base64,' + response.image;
                    resultImage.src = url;
                    resultImage.style.display = 'block';
                    video.style.display = 'none';

                } else {
                    console.error('ไม่สามารถแปลงเป็น Object ได้:', response);
                }
            };

            socket.onerror = error => {
                console.error('WebSocket Error: ', error);
            };

            socket.onclose = () => {
                console.log('WebSocket connection closed');
                isWebSocketOpen = false;
            };

            document.getElementById('capture').addEventListener('click', () => {
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                if (socket) {
                    socket.close();
                    console.log('WebSocket connection closed');
                    isWebSocketOpen = false;
                }

                canvas.toBlob(blob => {
                    const formData = new FormData();
                    formData.append('image', blob, 'capture.png');

                    fetch('/api/send-image', {
                            method: 'POST',
                            body: formData,

                        })
                        .then(response => response.json())
                        .then(data => {
                            // แสดงภาพที่ประมวลผลแล้ว
                            console.log(data)
                            const imageUrl = `data:image/png;base64,${data.image}`;
                            resultImage.src = imageUrl;
                            resultImage.style.display = 'block';
                            video.style.display = 'none';

                            // แสดงผลการตรวจจับ

                            datatable.innerHTML = '';
                            datatableorder.innerHTML = '';
                            let totalPrice = 0;

                            // แก้ไขส่วนการแสดงผลใน <script>
                            for (const [name, result] of Object.entries(data.detections)) {
                                const row = document.createElement('tr');
                                const nameColumn = document.createElement('td');
                                const valueColumn = document.createElement('td');
                                const priceColumn = document.createElement('td');
                                const idColumn = document.createElement('input');
                                const imageColumn = document.createElement('input');

                                nameColumn.textContent = result.name;
                                valueColumn.textContent = result.count;
                                priceColumn.textContent = result.price;
                                priceColumn.classList.add('price-column');
                                idColumn.type = 'hidden';
                                idColumn.value = result.id;
                                imageColumn.type = 'hidden';
                                imageColumn.value = result.image;

                                row.appendChild(nameColumn);
                                row.appendChild(valueColumn);
                                row.appendChild(priceColumn);
                                row.appendChild(idColumn);
                                row.appendChild(imageColumn);

                                // เพิ่มเงื่อนไขเพื่อซ่อน row ที่ count น้อยกว่าหรือเท่ากับ 0
                                if (result.count > 0) {
                                    totalPrice += result.price * result.count;

                                } else {
                                    row.style.display = 'none';
                                }

                                // เพิ่มข้อมูลไปยังทั้งสองตาราง (หากต้องการ)
                                datatableorder.appendChild(row);
                                datatable.appendChild(row.cloneNode(true));
                            }

                            // Display the total price
                            document.getElementById('total-price').innerHTML =
                                `<h5>ราคารวม: ${totalPrice.toFixed(2)} ฿</h5>`;


                        })
                        .catch(error => {
                            console.error('เกิดข้อผิดพลาด:', error);
                        });
                }, 'image/png');
            });

            function refreshPage() {
                if (socket) {
                    socket.close();
                    console.log('WebSocket connection closed');
                    isWebSocketOpen = false;
                }
                window.location.reload();
            }

            document.getElementById('product-form').addEventListener('submit', function(event) {
                event.preventDefault(); // ยกเลิกการส่งฟอร์มเพื่อใช้ JavaScript จัดการ
                let tableRows = document.querySelectorAll('#data-table tr');
                let formData = [];
                tableRows.forEach(function(row) {
                    let rowData = {
                        id: row.querySelectorAll('input[type="hidden"]')[0].value,
                        name: row.cells[0].innerText,
                        quantity: row.cells[1].innerText,
                        price: row.cells[2].innerText,
                        image: row.querySelectorAll('input[type="hidden"]')[1].value
                    };
                    formData.push(rowData);
                });

                let hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'product_data');
                hiddenInput.setAttribute('value', JSON.stringify(formData));
                document.getElementById('product-form').appendChild(hiddenInput);

                this.submit();
            });

            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('.toPurchase').click(function(e) {
                    e.preventDefault();

                    let totalPrice = 0;
                    $('#data-tableor tr').each(function() {
                        let row = $(this);
                        let quantity = parseInt(row.find('td').eq(1).text());
                        let price = parseFloat(row.find('td').eq(2).text());
                        if (quantity > 0 && !isNaN(price)) {
                            totalPrice += quantity * price;
                        }
                    });
                    $('#PurchaseDetail').modal('show');
                    $('#PurchaseDetail .total-price-modal h5 strong').text(
                        `ราคารวม: ${totalPrice.toFixed(2)} ฿`);
                });

                $('#CalM').click(function(e) {
                    e.preventDefault();
                    $('#PurchaseDetail').modal('hide');
                    $('#CalCash').modal('show');

                    $('#CancelCal').click(function(e) {
                        e.preventDefault();

                        // Close QR Code Modal
                        $('#CalCash').modal('hide');

                        // Open Purchase Detail Modal
                        $('#PurchaseDetail').modal('show');
                    });

                    let orderItems = [];
                    let totalPrice = 0;

                    $('#data-tableor tr').each(function() {
                        let row = $(this);
                        let item = {
                            id: row.find('input[type="hidden"]').eq(0).val(),
                            name: row.find('td').eq(0).text(),
                            quantity: row.find('td').eq(1).text(),
                            price: row.find('td').eq(2).text(),
                        };
                        if (item.quantity > 0) {
                            orderItems.push(item);
                            totalPrice += parseFloat(item.price) * parseInt(item.quantity);
                        }
                    });

                    let itemsList = $('#calCashItems');
                    itemsList.empty();
                    orderItems.forEach(function(item) {
                        itemsList.append(`
        <div class="item">
            <span class="item-name">${item.name}</span>
            <span class="item-quantity"> ${item.quantity}</span>
            <span class="item-price"> ${item.price} ฿</span>
        </div>
    `);
                    });
                });


                $('#CalBtn').click(function(e) {
                    e.preventDefault();

                    // ดึงจำนวนเงินที่ได้รับ
                    var cashR = parseFloat($('#CashR').val());

                    // คำนวณราคารวมจากรายการสินค้าใน Modal
                    let totalPrice = 0;
                    $('#data-tableor tr').each(function() {
                        let row = $(this);
                        let quantity = parseInt(row.find('td').eq(1).text());
                        let price = parseFloat(row.find('td').eq(2).text());
                        if (quantity > 0 && !isNaN(price)) {
                            totalPrice += quantity * price;
                        }
                    });

                    // คำนวณเงินทอน
                    var change = cashR - totalPrice;

                    // แสดงผลลัพธ์ใน label
                    $('#Cal').val(change.toFixed(2) + ' ฿');
                });


                $('#PP').click(function(e) {
                    e.preventDefault();

                    let totalPrice = 0;
                    $('#data-tableor tr').each(function() {
                        let row = $(this);
                        let quantity = parseInt(row.find('td').eq(1).text(), 10);
                        let price = parseFloat(row.find('td').eq(2).text(), 10);
                        if (quantity > 0 && !isNaN(price)) {
                            totalPrice += quantity * price;
                        }
                    });

                    $('#QR-show .total-price-modalQR h5 strong').text(`${totalPrice.toFixed(2)} ฿`);

                    $('#PurchaseDetail').modal('hide');
                    $('#QR-show').modal('show');


                    $.ajax({
                        type: "POST",
                        url: "{{ route('genQR') }}",
                        data: {
                            sumPrice: totalPrice
                        },
                        success: function(response) {
                            $('#QR-show').modal('show');
                            $('#qrI').attr('src', response.qrSrc);
                            const qrUrl = `/qrcode-view?qrSrc=${encodeURIComponent(response.qrSrc)}&amount=${parseFloat(totalPrice)}`;
                                window.open(qrUrl, '_blank'); // Set the QR code image source
                        },
                        error: function(error) {
                            console.error('AJAX Error:', error);
                        }
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

                $('#cancelQR').click(function(e) {
                    e.preventDefault();

                    // Close QR Code Modal
                    $('#QR-show').modal('hide');

                    // Open Purchase Detail Modal
                    $('#PurchaseDetail').modal('show');
                });
            });

            $('#Cash').click(function(e) {
                e.preventDefault();

                // เตรียมข้อมูลจาก #data-tableor
                let orderItems = [];
                let totalPrice = 0;

                $('#data-tableor tr').each(function() {
                    let row = $(this);
                    let item = {
                        id: row.find('input[type="hidden"]').eq(0).val(),
                        name: row.find('td').eq(0).text(),
                        quantity: row.find('td').eq(1).text(),
                        price: row.find('td').eq(2).text(),
                    };
                    if (item.quantity > 0) { // ตรวจสอบว่า quantity มากกว่า 0
                        orderItems.push(item);
                        totalPrice += parseFloat(item.price) * parseInt(item.quantity);
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

                        // Copy content from #data-tableor to #order-items
                        // $('#order-items').html($('#data-tableor').html());
                        var itemsList = $('#order-items');
                        itemsList.empty();
                        if (Array.isArray(savedOrder.order_items)) {
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

                        $('#Order-Detail .total-price-modal h5 strong').text(
                            `ราคารวม: ${savedOrder.Total_price.toFixed(2)} ฿`);

                        $('#Order-Detail .total-price').text('ราคารวม : ' + savedOrder.Total_price);


                        $('#Order-Detail').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            });
            $('#confirmQR').click(function(e) {
                e.preventDefault();

                // เตรียมข้อมูลจาก #data-tableor
                let orderItems = [];
                $('#data-tableor tr').each(function() {
                    let row = $(this);
                    let item = {
                        id: row.find('input[type="hidden"]').eq(0).val(),
                        name: row.find('td').eq(0).text(),
                        quantity: row.find('td').eq(1).text(),
                        price: row.find('td').eq(2).text(),
                    };
                    if (item.quantity > 0) { // ตรวจสอบว่า quantity มากกว่า 0
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
                        // $('#total-price').text('ราคารวม: ' + savedOrder.Total_price);
                        $('#Order-Detail .total-price-modal h5 strong').text(
                            `ราคารวม: ${savedOrder.Total_price.toFixed(2)} ฿`);


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
                var isChecked = $('#noTaxID').is(':checked'); // Check if checkbox is checked
                $('#taxID').prop('disabled', isChecked); // Enable/disable the input based on checkbox state
                if (isChecked) {
                    $('#taxID').val(''); // Clear the value of #taxID when checkbox is checked
                }
            }

            $('#noTaxID').change(function() {
                toggleTaxID();
            });
        </script>
    </body>
@endsection
