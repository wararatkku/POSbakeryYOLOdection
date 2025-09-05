@extends('layoutOwner')
@section('title')
    Dashboard
@endsection
@section('contents')
    <div class="db-header">
        <div class="btn-group db-btn" role="group">
            <a href="/ownerHome" type="button" class="btn btn-outline-primary">รายงานภาพรวม</a>
            <a href="/dbSale" type="button" class="btn btn-outline-primary">รายงานยอดขาย</a>
            <a href="/dbBuy" type="button" class="btn btn-outline-primary">รายงานรายจ่าย</a>
            <button type="button" class="btn btn-outline-primary active">รายงานสินค้า</button>
        </div>
    </div>
    <div class="db-pro">
        <div class="dashboard-main">
            <div class="card-group">
                <div class="db-card-main">
                    <h6>จำนวนสินค้าทั้งหมด</h6>
                    <div class="db-content">
                        <h2>{{ $TotalBakery }} ชิ้น</h2>
                    </div>
                </div>
                <div class="db-card-main">
                    <h6 id="monthN-stock">จำนวนการผลิตในเดือนนี้</h6>
                    <div class="db-content">
                        <h2>{{ $totalStockM }} ชิ้น</h2>
                    </div>
                </div>
                <div class="db-card-main">
                    <h6 id="yearN-stock">จำนวนการผลิตในปีนี้</h6>
                    <div class="db-content">
                        <h2>{{ $totalStockY }} ชิ้น</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-main">
            <div class="BestSell">
                <div class="bs-card" style="margin-top: 40px; width: 325px; height: 400px">
                    @foreach ($bakeryBestsell as $best)
                        <div class="db-img">
                            <img src="{{ asset('uploads/bakeries/' . $best->Bakery_image) }}" alt="" width="200px"
                                height="200px">
                        </div>
                        <div class="db-content">
                            <h6>สินค้าขายดี</h6>
                            <h2>{{ $best->Bakery_name }}</h2>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="db-large" style="width: 700px">
                <canvas id="BarChart" style="margin-top: 10px; padding-left: 10px; padding-right: 10px"></canvas>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.body.style.backgroundColor = "#ececec";
    </script>
    <script>
        const bc = document.getElementById('BarChart');
        const labels = @json($labels); // ดึงข้อมูล labels จาก Controller
        const data = @json($data); // ดึงข้อมูล data จาก Controller

        new Chart(bc, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'จำนวนสินค้าขายดี 5 อันดับแรก',
                    data: data,
                    borderWidth: 1,
                    backgroundColor: 'rgba(183, 155, 125)', // สีพื้นหลังแบบจาง
                    borderColor: '#7c6247', // สีขอบ
                }]
            },
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            color: '#7c6247',
                            font: {
                                weight: 'bold'
                            }
                        },
                        display: true,
                        title: {
                            display: true,
                            text: 'จำนวน (ชิ้น)', // Label ของแกน X
                            font: {
                                family: 'Arial',
                                size: 14,
                                weight: 'bold',
                                lineHeight: 1
                            }
                        }
                    },
                    y: {
                        ticks: {
                            color: '#7c6247',
                            font: {
                                weight: 'bold'
                            }
                        },
                        display: true,
                        title: {
                            display: true,
                            text: 'ชื่อสินค้า', // Label ของแกน X
                            font: {
                                family: 'Arial',
                                size: 14,
                                weight: 'bold',
                                lineHeight: 1
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#7c6247',
                            font: {
                                weight: 'bold'
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
    </script>

    <script>
        fetch('/MonthNow') // เปลี่ยน URL ตาม API ของคุณ
            .then(response => response.json())
            .then(data => {
                document.getElementById('monthN-stock').textContent = `จำนวนการผลิตในเดือน ${data.monthName}`;
                document.getElementById('yearN-stock').textContent = `จำนวนการผลิตในปี ${data.year}`;
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>
@endsection
