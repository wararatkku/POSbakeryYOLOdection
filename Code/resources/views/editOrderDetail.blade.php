@extends('layoutOwner')
@section('title')
    SellManagement
@endsection

@section('contents')
    <div class="modal fade" id="selectBakeryM" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header">
                        <h4>เลือกรายการสินค้า</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table-listB">
                            <thead>
                                <tr>
                                    <th style="width: 80px;"><input type="checkbox" id="selectAll"></th>
                                    <th>รหัสสินค้า</th>
                                    <th style="width: 80px;"></th>
                                    <th>ชื่อสินค้า</th>
                                    <th>ราคา(บาท)</th>
                                    <th>คงเหลือ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Bakery as $bakery)
                                    <tr>
                                        <td style="width: 80px;"><input type="checkbox" class="selectItem" data-bakery-id="{{ $bakery->Bakery_ID }}"></td>
                                        <td>{{ 'B-' . str_pad($bakery->Bakery_ID, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td><img src="{{ asset('uploads/bakeries/' . $bakery->Bakery_image) }}" alt=""
                                            width="50px" height="50px"></td>
                                        <td>{{ $bakery->Bakery_name }}</td>
                                        <td>{{ $bakery->Bakery_price }}</td>
                                        <td>{{ optional($bakery->stock->first())->totalS_quantity ?? 0 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button id="cancel" type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                        <button id="chooseBakery" type="button" class="btn btn-primary">ยืนยัน</button>
                    </div>
            </div>
        </div>
    </div>
    <div class="order-detail">
        <form id="createORForm" action="{{ route('updateOD', $bakeryOrders->BakeryOrder_ID) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="order-headSell">
                <h5><strong>รายละเอียดรายการ</strong></h5>
            </div>
            <input type="hidden" id="selectedBakeryIds" name="selectedBakeryIds">
            <input type="hidden" id="finalTotalPriceInput" name="finalTotalPrice" value="{{ $bakeryOrders->Total_price }}">
            <div class="orderData">
                <h5><strong>ข้อมูล</strong></h5>
                <div class="Orderinfo">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>การชำระเงิน :</label>
                            <select class="form-select PayType" name="payment_type" style="width: 200px" required>
                                <option value="" disabled hidden>-เลือกวิธีการชำระเงิน-</option>
                                <option value="เงินสด" {{ $bakeryOrders->payment->Payment_Type == 'เงินสด' ? 'selected' : '' }}>เงินสด</option>
                                <option value="PromptPay" {{ $bakeryOrders->payment->Payment_Type == 'PromptPay' ? 'selected' : '' }}>PromptPay</option>
                                </select>
                        </div>
                        <div class="info-item date">
                            <label>วันที่ขาย :</label>
                            <input type="datetime-local" name="sale_date" value="{{ \Carbon\Carbon::parse($bakeryOrders->created_at)->format('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="orderItem">
                <div class="orderItemHead">
                    <h5><strong>สินค้า</strong></h5>
                    <button type="button" class="btn btn-primary" id="selectBakery">เลือกสินค้า</button>
                </div>
                <div class="orderT">
                    <table class="table-listO">
                        <thead>
                            <tr>
                                <th scope="col">รหัสสินค้า</th>
                                <th scope="col">ชื่อสินค้า</th>
                                <th scope="col">จำนวน(ชิ้น)</th>
                                <th scope="col">ราคาต่อชิ้น(บาท)</th>
                                <th scope="col">ราคารวม(บาท)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bakeryOrders->orderItems as $item)
                                <tr>
                                    <td>{{ 'B-' . str_pad($item->bakery->Bakery_ID, 4, '0', STR_PAD_LEFT) }}
                                        <input type="hidden" class="BIDInput" name="b-id" value="{{ $item->bakery->Bakery_ID }}">
                                    </td>
                                    <td>{{ $item->bakery->Bakery_name }}</td>
                                    <td><input type="number" name="b-quan" class="form-control quantityInput" value="{{ $item->Sum_quantity }}" min="1" max="{{ $item->Sum_quantity + $item->bakery->stock->first()->totalS_quantity }}" style="margin: 0 auto; width: 80px; text-align: center">
                                        <input type="hidden" id="quanInput" name="b-quantity[]" value="{{ $item->Sum_quantity }}"></td>
                                    <td>{{ $item->bakery->Bakery_price }}</td>
                                    <td>
                                        <span class="totalPrice">{{ $item->Sum_price }}</span>
                                        <input type="hidden" id="totalPInput" name="b-ttp[]" value="{{ $item->Sum_price }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="OrderP">
                    <div>
                        <label>ราคารวม</label>
                        <p id="totalPrice">{{ $bakeryOrders->Total_price }}</p>
                    </div>
                    <div>
                        <label><strong>ราคารวมสุทธิ</strong></label>
                        <p id="finalTotalPrice"><strong>{{ $bakeryOrders->Total_price }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="con-canBtn">
                <a href="#" id="cancelButton" class="btn btn-secondary">ยกเลิก</a>
                <button type="submit" class="btn btn-primary">ยืนยัน</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')

    <script>
        $(document).ready(function() {
            $('#selectBakery').click(function(e) {
                e.preventDefault();

                $('#selectBakeryM').modal('show');
            });
        });

        document.getElementById('cancelButton').addEventListener('click', function(event) {
            event.preventDefault(); 
            window.history.back(); 
        });

        const form = document.getElementById('createORForm');
        const selectAllCheckbox = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.selectItem');
        const tableListOBody = document.querySelector('.table-listO tbody');
        const totalPrice = document.getElementById('totalPrice');
        const finalTotalPrice  = document.getElementById('finalTotalPrice');
        const selectedBakeryIdsInput = document.getElementById('selectedBakeryIds');
        let existingBakeryIds = @json($bakeryOrders->orderItems->pluck('bakery.Bakery_ID')->toArray());

        document.addEventListener('DOMContentLoaded', function() {
            const ODbakeryIds = [];
            const tableBID = tableListOBody.querySelectorAll('tr');
            tableBID.forEach(row => {
                // Find the hidden input with the bakery ID
                const bidInput = row.querySelector('.BIDInput');
                if (bidInput) {
                    ODbakeryIds.push(bidInput.value);
                }
            });
            // Convert array to a comma-separated string
            let bakeryIdsString = ODbakeryIds.join(',');
            console.log(bakeryIdsString);
            // Set the value of the hidden input
            document.getElementById('selectedBakeryIds').value = bakeryIdsString;
        });

        function updateSelectAllCheckbox() {
            // Check if all checkboxes are checked
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            selectAllCheckbox.checked = allChecked;
        }

        function updateCheckboxes() {
            $('.selectItem').each(function() {
                const bakeryId = $(this).data('bakery-id');
                if (existingBakeryIds.includes(bakeryId)) {
                    $(this).prop('checked', true);
                }
            });
        }

        form.addEventListener('submit', function (event) {
            if (selectedBakeryIdsInput.value.trim() === '' && tableListOBody.children.length === 0) {
                event.preventDefault(); // Prevent form submission
                alert('กรุณาเลือกสินค้าอย่างน้อยหนึ่งรายการ'); // "Please select at least one item"
                return false;
            }
        });

        $('#selectBakeryM').on('shown.bs.modal', function() {
            updateCheckboxes();
        });

        $('.selectItem').on('change', function() {
            const BakeryId = $(this).data('bakery-id');
            if ($(this).is(':checked')) {
                // Add to existingBakeryIds if checked
                if (!existingBakeryIds.includes(BakeryId)) {
                    existingBakeryIds.push(BakeryId);
                }
            } else {
                // Remove from existingBakeryIds if unchecked
                existingBakeryIds = existingBakeryIds.filter(id => id !== BakeryId);
            }
        });

    
        selectAllCheckbox.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectAllCheckbox);
        });

        $('#chooseBakery').click(function(e) {
            let totalSum = 0;
            let bakeryIds = [];

            // Track currently selected items from the modal
            let selectedItems = new Set();
            let checkboxes = document.querySelectorAll('#selectBakeryM .selectItem'); // Ensure correct selection
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const row = checkbox.closest('tr');
                    const cells = row.querySelectorAll('td');
                    const bakeryId = cells[1].textContent.trim(); // Format includes 'B-'
                    const rawBakeryID = checkbox.getAttribute('data-bakery-id'); 
                    selectedItems.add(bakeryId);
                    bakeryIds.push(rawBakeryID);
                }
            });

            // Get existing rows from the table
            const tableRows = tableListOBody.querySelectorAll('tr');
            let rowsToRemove = [];
            
            tableRows.forEach(row => {
                const bakeryId = row.querySelector('td').textContent.trim(); 
                if (!selectedItems.has(bakeryId)) {
                    rowsToRemove.push(row);
                } else {
                    // Update total sum for existing rows
                    const quantityInput = row.querySelector('.quantityInput');
                    const quantity = parseInt(quantityInput.value, 10);
                    const price = parseFloat(row.querySelector('td:nth-child(4)').textContent.trim());
                    const totalPrice = quantity * price;
                    row.querySelector('.totalPrice').textContent = totalPrice;
                    row.querySelector('input[name="b-ttp[]"]').value = totalPrice;

                    totalSum += totalPrice;
                }
            });

            // Remove deselected rows
            rowsToRemove.forEach(row => row.remove());

            // Add new rows for selected items
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const row = checkbox.closest('tr');
                    const cells = row.querySelectorAll('td');
                    const bakeryId = cells[1].textContent.trim(); // Format includes 'B-'
                    const bakeryName = cells[3].textContent.trim();
                    const quantity = 1; 
                    const price = parseFloat(cells[4].textContent.trim());
                    const bakeryQuan = parseInt(cells[5].textContent.trim());
                    const totalPrice = price * quantity;

                    // Check if the row already exists
                    let rowExists = false;
                    tableRows.forEach(existingRow => {
                        if (existingRow.querySelector('td').textContent.trim() === bakeryId) {
                            rowExists = true;
                        }
                    });

                    if (!rowExists) {
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td>${bakeryId}</td>
                            <td>${bakeryName}</td>
                            <td><input type="number" name="b-quan" class="form-control quantityInput" value="${quantity}" min="1" max="${bakeryQuan}" style="margin: 0 auto; width: 80px; text-align: center">
                                <input type="hidden" name="b-quantity[]" value="${quantity}"></td>
                            <td>${price}</td>
                            <td><span class="totalPrice">${totalPrice}</span>
                                <input type="hidden" name="b-ttp[]" value="${totalPrice}"></td>
                        `;
                        tableListOBody.appendChild(newRow);
                        totalSum += totalPrice;
                    }
                }
            });

            // Update selected bakery IDs input
            selectedBakeryIdsInput.value = bakeryIds.join(',');
            console.log(bakeryIds)

            // Update total price display
            totalPrice.textContent = totalSum.toFixed(2);
            finalTotalPrice.innerHTML = `<strong>${totalSum.toFixed(2)}</strong>`;
            document.getElementById('finalTotalPriceInput').value = totalSum;

            $('#selectBakeryM').modal('hide');
        });
        tableListOBody.addEventListener('input', function (event) {
            if (event.target.classList.contains('quantityInput')) {
                const input = event.target;
                const row = input.closest('tr');
                const price = parseFloat(row.cells[3].textContent.trim());
                const quantity = parseInt(input.value) || 1;
                const TotalPrice = price * quantity;

                row.querySelector('.totalPrice').textContent = TotalPrice;
                row.querySelector('input[name="b-ttp[]"]').value = TotalPrice;
                row.querySelector('input[name="b-quantity[]"]').value = quantity;

                // Recalculate and update the total sum
                let newTotalSum = 0;
                tableListOBody.querySelectorAll('.totalPrice').forEach(cell => {
                    newTotalSum += parseFloat(cell.textContent);
                });
                console.log(newTotalSum);

                totalPrice.textContent = newTotalSum.toFixed(2);
                finalTotalPrice.innerHTML = `<strong>${newTotalSum.toFixed(2)}</strong>`;
                document.getElementById('finalTotalPriceInput').value = newTotalSum;
                
            }
        });
        
    </script>
@endsection
