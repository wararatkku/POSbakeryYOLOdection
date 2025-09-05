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
                                        <td>{{ $bakery->stock->first()->totalS_quantity }}</td>
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
        <form id="createORForm" action="{{ route('insertOrder') }}" method="POST">
            @csrf
            <div class="order-headSell">
                <h5><strong>สร้างรายการ</strong></h5>
            </div>
            <input type="hidden" id="selectedBakeryIds" name="selectedBakeryIds">
            <input type="hidden" id="finalTotalPriceInput" name="finalTotalPrice">
            <div class="orderData">
                <h5><strong>ข้อมูล</strong></h5>
                <div class="Orderinfo">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>การชำระเงิน :</label>
                            <select class="form-select PayType" name="payment_type" style="width: 200px" required>
                                <option value="" disabled selected hidden>-เลือกวิธีการชำระเงิน-</option>
                                <option value="เงินสด">เงินสด</option>
                                <option value="PromptPay">PromptPay</option>
                            </select>
                        </div>
                        <div class="info-item date">
                            <label>วันที่ขาย :</label>
                            <input type="datetime-local" name="sale_date" required>
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
                        </tbody>
                    </table>
                </div>
                <div class="OrderP">
                    <div>
                        <label>ราคารวม</label>
                        <p id="totalPrice">0</p>
                    </div>
                    <div>
                        <label><strong>ราคารวมสุทธิ</strong></label>
                        <p id="finalTotalPrice"><strong>0</strong></p>
                    </div>
                </div>
            </div>
            <div class="con-canBtn">
                <a href="/SellManage" class="btn btn-secondary">ยกเลิก</a>
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

        const form = document.getElementById('createORForm');
        const selectAllCheckbox = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.selectItem');
        const tableListOBody = document.querySelector('.table-listO tbody');
        const totalPrice = document.getElementById('totalPrice');
        const finalTotalPrice  = document.getElementById('finalTotalPrice');
        const selectedBakeryIdsInput = document.getElementById('selectedBakeryIds');

        function updateSelectAllCheckbox() {
            // Check if all checkboxes are checked
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            selectAllCheckbox.checked = allChecked;
        }

        form.addEventListener('submit', function (event) {
            if (selectedBakeryIdsInput.value.trim() === '') {
                event.preventDefault(); // Prevent form submission
                alert('กรุณาเลือกสินค้าอย่างน้อยหนึ่งรายการ'); // "Please select at least one item"
                return false;
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
            tableListOBody.innerHTML = ''; // Clear previous rows
            let totalSum = 0;
            let bakeryIds = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {        
                    const row = checkbox.closest('tr');
                    const cells = row.querySelectorAll('td');
                    const rawBakeryID = checkbox.getAttribute('data-bakery-id'); 
                    bakeryIds.push(rawBakeryID); 
                    const bakeryId = cells[1].textContent.trim();
                    const bakeryName = cells[3].textContent.trim();
                    const quantity = 1; 
                    const price = parseFloat(cells[4].textContent.trim());
                    const bakeryQuan = parseInt(cells[5].textContent.trim());
                    const totalPrice = price * quantity;

                    totalSum += totalPrice;

                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${bakeryId}</td>
                        <td>${bakeryName}</td>
                        <td><input type="number" name="b-quan" class="form-control quantityInput" value="${quantity}" min="1" max="${bakeryQuan}" style="margin: 0 auto; width: 80px; text-align: center">
                            <input type="hidden" id="quanInput" name="b-quantity[]" value="${quantity}"></td>
                        <td>${price}</td>
                        <td><span class="totalPrice">${totalPrice}</span>
                            <input type="hidden" id="totalPInput" name="b-ttp[]" value="${totalPrice}"></td>
                    `;
                    
                    tableListOBody.appendChild(newRow);
                }
            });
            selectedBakeryIdsInput.value = bakeryIds.join(',');

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