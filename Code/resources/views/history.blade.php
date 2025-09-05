@extends('layoutCus')
@section('title')
    History
@endsection
@section('contents')
    <style>
    </style>

    <div class="modal fade" id="Order-Detail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content purchase" style="background: url('{{ asset('images/purchase.png') }}');">
                <div class="modal-header">
                    <div class="his-detail">
                        <h1 class="modal-title fs-5" id="order-id"></h1>
                        <span id="order-idReq" hidden></span>
                        <span id="payment-type"></span>
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
                    <button id="cancel" type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                    <button id="Invoice" type="submit" class="btn btn-success">พิมพ์ใบเสร็จ</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Invoice-Data" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <input type="text" id="taxID" name="taxID" class="form-control tax" maxlength="13"
                                    minlength="13" pattern="[0-9]{13}" placeholder="เลขประจำตัวผู้เสียภาษี" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ที่อยู่</label>
                            <textarea class="form-control" id="cusAdd" name="cusAdd" rows="3"></textarea>
                        </div>
                        <input type="hidden" id="invoice-order-id" name="order_id">
                    </div>
                    <div class="modal-footer">
                        <button id="cancel" type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-success">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="text-heading">ประวัติการชำระเงิน</div>
    <form action="{{ route('history.filter') }}" method="GET">
        <div class="filter-date">
            <div style="position: relative;">
                <input type="text" id="date_range" name="date_range" value="{{ request('date_range') }}"
                    placeholder="เลือกช่วงวันที่" class="form-control"
                    style="width: 240px; max-width: 100%; padding: 10px; margin-right: 10px; background-color: #BA9269; color: white; padding-right: 30px;">
                <i class="fas fa-calendar-alt " id="calendar-icon"
                    style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); color: white; "></i>
            </div>

            <button type="submit" class="btn-filter">กรอง</button>
        </div>
    </form>

    <div class="h-detail">
        <table>
            <thead>
                <tr>
                    <th data-sort="id">ID</th>
                    <th data-sort="datetime">วันเวลา</th>
                    <th data-sort="payment">รูปแบบการชำระเงิน</th>
                    <th data-sort="price">ราคารวม</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="orderTableBody">
                @foreach ($bakeryOrders as $order)
                    <tr>
                        <td><strong>{{ 'OR-' . str_pad($order->BakeryOrder_ID, 4, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>{{ $order->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $order->payment->Payment_Type }}</td>
                        <td>{{ $order->Total_price }} ฿</td>
                        <td><button class="btn detail" data-id="{{ $order->BakeryOrder_ID }}"
                                data-payment="{{ $order->payment->Payment_Type }}"
                                data-datetime="{{ $order->created_at->format('d/m/Y H:i:s') }}"
                                data-items="{{ $order->orderItems }}"
                                data-price="{{ $order->Total_price }}">ดูรายละเอียด</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination-his">
        {{ $bakeryOrders->links('pagination::bootstrap-4') }}
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.detail').on('click', function() {
                // Extract data attributes from the clicked button
                var orderId = $(this).data('id');
                var paymentType = $(this).data('payment');
                var orderDateTime = $(this).data('datetime');
                var orderItems = $(this).data('items');
                var totalPrice = $(this).data('price');

                // Populate the modal with extracted data
                $('#order-id').text('Order ID : OR-' + orderId.toString().padStart(4, '0'));
                $('#order-idReq').text(orderId);
                $('#payment-type').text('รูปแบบการชำระเงิน : ' + paymentType);
                $('#order-datetime').text('วันเวลา : ' + orderDateTime);

                // Populate order items list
                var itemsList = $('#order-items');
                itemsList.empty();
                orderItems.forEach(function(orderItems) {
                    var itemContainer = $('<div class="item-list"></div>');

                    var bakeryName = $('<strong><div class="bakery-name"></div></strong>').text(
                        orderItems.bakery.Bakery_name);

                    var quanpriceModal = $('<div class="quanprice-modal"></div>');

                    var itemQuan = $('<span class="quan"></span>').text(orderItems.Sum_quantity);
                    var itemPrice = $('<span class="price"></span>').text(orderItems.Sum_price +
                        ' ฿');

                    quanpriceModal.append(itemQuan);
                    quanpriceModal.append(itemPrice);

                    itemContainer.append(bakeryName);
                    itemContainer.append(quanpriceModal);

                    itemsList.append(itemContainer);
                });

                $('#total-price').text('ราคารวม : ' + totalPrice + ' ฿');

                $('#Order-Detail').modal('show');
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
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#date_range", {
                mode: "range", 
                dateFormat: "Y-m-d", 
                onChange: function(selectedDates, dateStr, instance) {
                    document.querySelector('input[name="date_range"]').value = dateStr;
                }
            });
            document.getElementById("calendar-icon").addEventListener("click", function() {
                document.getElementById("date_range").focus();
            });
        });


        // document.addEventListener("DOMContentLoaded", () => {
        //     const tableBody = document.getElementById("orderTableBody");
        //     let sortDirection = 1; // 1 for ascending, -1 for descending

        //     // Add click event listeners to headers
        //     document.querySelectorAll("th[data-sort]").forEach(header => {
        //         header.addEventListener("click", () => {
        //             const sortKey = header.getAttribute("data-sort");
        //             const rows = Array.from(tableBody.querySelectorAll("tr"));

        //             rows.sort((a, b) => {
        //                 const aValue = getCellValue(a, sortKey);
        //                 const bValue = getCellValue(b, sortKey);

        //                 if (aValue < bValue) return -1 * sortDirection;
        //                 if (aValue > bValue) return 1 * sortDirection;
        //                 return 0;
        //             });

        //             // Update table with sorted rows
        //             rows.forEach(row => tableBody.appendChild(row));

        //             // Toggle sort direction
        //             sortDirection *= -1;
        //         });
        //     });

        //     function getCellValue(row, key) {
        //         switch (key) {
        //             case "id":
        //                 return row.cells[0].textContent.replace("OR-", "");
        //             case "datetime":
        //                 return new Date(row.cells[1].textContent);
        //             case "payment":
        //                 return row.cells[2].textContent;
        //             case "price":
        //                 return parseFloat(row.cells[3].textContent.replace(" ฿", ""));
        //             default:
        //                 return "";
        //         }
        //     }
        // });
    </script>
@endsection
