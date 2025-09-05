@extends('layoutOwner')
@section('title')
    Dashboard
@endsection
@section('contents')
    <div class="db-header">
        <div class="btn-group db-btn" role="group">
            <a href="/ownerHome" type="button" class="btn btn-outline-primary">รายงานภาพรวม</a>
            <button type="button" class="btn btn-outline-primary active">รายงานยอดขาย</button>
            <a href="/dbBuy" type="button" class="btn btn-outline-primary">รายงานรายจ่าย</a>
            <a href="/dbProduct" type="button" class="btn btn-outline-primary">รายงานสินค้า</a>
        </div>
    </div>
    <div class="db-sale">
        <div class="dashboard-main">
            <div class="db-large-sale">
                <canvas id="LineSell" style="margin-top: 50px; padding-left: 10px; padding-right: 10px"></canvas>
            </div>
            <div class="card-group">
                <div class="db-card-sale">
                    <h6 id="monthN-sale">ยอดขายในเดือนนี้</h6>
                    <div class="db-content">
                        <h2 id="monthly-sale"> บาท</h2>
                    </div>
                </div>
                <div class="db-card-sale">
                    <h6 id="monthN-quan">จำนวนสินค้าที่ขายในเดือนนี้</h6>
                    <div class="db-content">
                        <h2 id="monthly-quan"> ชิ้น</h2>
                    </div>
                </div>
                <div class="db-card-sale">
                    <h6 id="monthN-order">รายการคำสั่งซื้อในเดือนนี้</h6>
                    <div class="db-content">
                        <h2 id="monthly-order"></h2>
                    </div>
                </div>
                <div class="db-card-sale">
                    <h6 id="monthN-quan-order">จำนวนสินค้าต่อคำสั่งซื้อเฉลี่ยในเดือนนี้</h6>
                    <div class="db-content">
                        <h2>{{ $avgItemsPerOrder }} ชิ้น</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-main">
            {{-- <div class="db-large">
                <canvas id="LineSell" style="margin-top: 50px; padding-left: 10px; padding-right: 10px"></canvas>
            </div> --}}
            <div class="db-large-side-sale">
                <div class="dbT">
                    <table class="table-listP-db-sale">
                        <thead>
                            <tr>
                                <th scope="col">รายการ</th>
                                <th scope="col">การชำระเงิน</th>
                                <th scope="col">จำนวนสินค้าทั้งหมด</th>
                                <th scope="col">ราคารวม(บาท)</th>
                                <th scope="col">วันที่ขาย</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bakeryOrders as $key => $order)
                                <tr>
                                    <td>{{ 'OR-' . str_pad($order->BakeryOrder_ID, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $order->payment->Payment_Type }}</td>
                                    <td>{{ $order->orderItems->sum('Sum_quantity') }}</td>
                                    <td>{{ $order->Total_price }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.body.style.backgroundColor = "#ececec";
    </script>
    <script>
        const lc = document.getElementById('LineSell');
        const lc2 = document.getElementById('LineChart2');
        const sellData = @json($saleData);
        const sellLabels = sellData.map(data => data.month); // ชื่อเดือน (ม.ค, ก.พ)
        const sellTotals = sellData.map(data => data.total);

        new Chart(lc, {
            type: 'line',
            data: {
                labels: sellLabels,
                datasets: [{
                    label: 'ยอดขายรวมในแต่ละเดือน (บาท)',
                    data: sellTotals,
                    borderWidth: 2,
                    backgroundColor: 'rgba(11, 156, 49, 0.6)',
                    borderColor: 'rgba(11, 156, 49, 1)',
                    fill: true
                }]
            },
            options: {
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'เดือน', // Label ของแกน X
                            font: {
                                family: 'Arial',
                                size: 14,
                                weight: 'bold',
                                lineHeight: 1
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        display: true,
                        title: {
                            display: true,
                            text: 'จำนวนเงิน (บาท)', // Label ของแกน X
                            font: {
                                family: 'Arial',
                                size: 14,
                                weight: 'bold',
                                lineHeight: 1
                            }
                        }
                    }
                }
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('/api/getMonthlySales') // URL ชี้ไปยัง route ของ controller
                .then(response => response.json())
                .then(data => {
                    const sales = Number(data.sales).toLocaleString();
                    document.getElementById('monthly-sale').textContent = `${sales} บาท`;
                })
                .catch(error => {
                    console.error('Error fetching monthly sales:', error);
                    document.getElementById('monthly-sale').textContent = 'ไม่สามารถโหลดข้อมูลได้';
                });
        });
        document.addEventListener("DOMContentLoaded", function() {
            fetch('/api/getMonthlyOrders') // URL ชี้ไปยัง route ของ controller
                .then(response => response.json())
                .then(data => {
                    const orders = Number(data.orders).toLocaleString();
                    document.getElementById('monthly-order').textContent = `${orders} คำสั่งซื้อ`;
                })
                .catch(error => {
                    console.error('Error fetching monthly orders:', error);
                    document.getElementById('monthly-order').textContent = 'ไม่สามารถโหลดข้อมูลได้';
                });
        });
        document.addEventListener("DOMContentLoaded", function() {
            fetch('/api/getMonthlySaleQuan') // URL ชี้ไปยัง route ของ controller
                .then(response => response.json())
                .then(data => {
                    const saleQuan = Number(data.saleQuan).toLocaleString();
                    document.getElementById('monthly-quan').textContent = `${saleQuan} ชิ้น`;
                })
                .catch(error => {
                    console.error('Error fetching monthly quantities:', error);
                    document.getElementById('monthly-quan').textContent = 'ไม่สามารถโหลดข้อมูลได้';
                });
        });
    </script>

    <script>
        fetch('/MonthNow') // เปลี่ยน URL ตาม API ของคุณ
            .then(response => response.json())
            .then(data => {
                document.getElementById('monthN-sale').textContent = `ยอดขายในเดือน ${data.monthName}`;
                document.getElementById('monthN-quan-order').textContent =
                    `จำนวนสินค้าต่อคำสั่งซื้อเฉลี่ยในเดือน ${data.monthName}`;
                document.getElementById('monthN-order').textContent = `รายการคำสั่งซื้อในเดือน ${data.monthName}`;
                document.getElementById('monthN-quan').textContent = `จำนวนสินค้าที่ขายในเดือน ${data.monthName}`;
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>
@endsection
