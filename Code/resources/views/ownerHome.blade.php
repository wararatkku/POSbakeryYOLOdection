@extends('layoutOwner')
@section('title')
    Home
@endsection
@section('contents')
    <div class="db-header">
        <div class="btn-group db-btn" role="group">
            <button type="button" class="btn btn-outline-primary active">รายงานภาพรวม</button>
            <a href="/dbSale" type="button" class="btn btn-outline-primary">รายงานยอดขาย</a>
            <a href="/dbBuy" type="button" class="btn btn-outline-primary">รายงานรายจ่าย</a>
            <a href="/dbProduct" type="button" class="btn btn-outline-primary">รายงานสินค้า</a>
        </div>
    </div>
    <div class="overall">
        <div class="dashboard-main">
            <div class="BestSell">
                <div class="bs-card">
                    @foreach ($bakeryBestsell as $best)
                        <div class="db-img">
                            <img src="{{ asset('uploads/bakeries/' . $best->Bakery_image) }}" alt="" width="150px"
                                height="150px">
                        </div>
                        <div class="db-content">
                            <h6>สินค้าขายดี</h6>
                            <h2>{{ $best->Bakery_name }}</h2>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-group">
                <div class="db-card-main">
                    <h6 id="monthN-sale">ยอดขายในเดือนนี้</h6>
                    <div class="db-content">
                        <h2 id="monthly-sale"> บาท</h2>
                    </div>
                </div>
                <div class="db-card-main">
                    <h6 id="monthN-buy">รายจ่ายในเดือนนี้</h6>
                    <div class="db-content">
                        <h2>{{ $totalMonthlyProductButyPrice }} บาท</h2>
                    </div>
                </div>
                <div class="db-card-main">
                    <h6 id="monthN-order">รายการคำสั่งซื้อในเดือนนี้</h6>
                    <div class="db-content">
                        <h2 id="monthly-order"> รายการ</h2>
                    </div>
                </div>
                <div class="db-card-main">
                    <h6 id="monthN-quan">จำนวนสินค้าที่ขายในเดือนนี้</h6>
                    <div class="db-content">
                        <h2 id="monthly-quan"> ชิ้น</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-main">
            <div class="db-large-main">
                <canvas id="LineSell" style="margin-top: 10px; padding-left: 10px; padding-right: 10px"></canvas>
            </div>
            <div class="db-large-main">
                <canvas id="LineChart2" style="margin-top: 10px; padding-left: 10px; padding-right: 10px"></canvas>
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
        const sellData = @json($sellData);
        const buyData = @json($saleData);
        const sellLabels = sellData.map(data => data.month); // ชื่อเดือน (ม.ค, ก.พ)
        const sellTotals = sellData.map(data => data.total);
        const buyLabels = buyData.map(data => data.monthpb); // ชื่อเดือน (ม.ค, ก.พ)
        const buyTotals = buyData.map(data => data.totalpb);

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
        new Chart(lc2, {
            type: 'line',
            data: {
                labels: buyLabels,
                datasets: [{
                    label: 'ยอดซื้อรวมในแต่ละเดือน (บาท)',
                    data: buyTotals,
                    borderWidth: 2,
                    backgroundColor: 'rgba(255, 51, 51, 0.6)',
                    borderColor: 'rgba(255, 51, 51, 1)',
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
                    document.getElementById('monthly-order').textContent = `${orders} รายการ`;
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
                document.getElementById('monthN-buy').textContent = `รายจ่ายในเดือน ${data.monthName}`;
                document.getElementById('monthN-order').textContent = `รายการคำสั่งซื้อในเดือน ${data.monthName}`;
                document.getElementById('monthN-quan').textContent = `จำนวนสินค้าที่ขายในเดือน ${data.monthName}`;
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>
    
@endsection