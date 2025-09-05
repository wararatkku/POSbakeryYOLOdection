@extends('layoutOwner')
@section('title')
    Dashboard
@endsection
@section('contents')
    <div class="db-header">
        <div class="btn-group db-btn" role="group">
            <a href="/ownerHome" type="button" class="btn btn-outline-primary" >รายงานภาพรวม</a>
            <a href="/dbSale" type="button" class="btn btn-outline-primary">รายงานยอดขาย</a>
            <button type="button" class="btn btn-outline-primary active">รายงานรายจ่าย</button>
            <a href="/dbProduct" type="button" class="btn btn-outline-primary">รายงานสินค้า</a>
        </div>
    </div>
    <div class="db-sale">
        <div class="dashboard-main">
            <div class="card-group">
                <div class="db-card-main">
                    <h6 id="monthN-buy">รายจ่ายในเดือนนี้</h6>
                    <div class="db-content">
                        <h2>{{ $totalMonthlyPrice }} บาท</h2>
                    </div>
                </div>
                <div class="db-card-main">
                    <h6 id="monthN-quan">จำนวนรายการซื้อในเดือนนี้</h6>
                    <div class="db-content">
                        <h2>{{ $TotalProductBuy }} รายการ</h2>
                    </div>
                </div>
                <div class="db-card-main">
                    <h6 id="yearN-buy">รายจ่ายในปีนี้</h6>
                    <div class="db-content">
                        <h2>{{ $totalYearPrice }} บาท</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-main">
            <div class="db-large">
                <canvas id="LineChart2" style="margin-top: 50px; padding-left: 10px; padding-right: 10px"></canvas>
            </div>
            <div class="db-large-side">
                <div class="dbT">
                    <table class="table-listP-db">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">ชื่อสินค้า</th>
                                <th scope="col">ราคา(บาท)</th>
                                <th scope="col">จำนวน</th>
                                <th scope="col">วันที่ซื้อ</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productSales as $order)
                                <tr>
                                    <td><img src="{{ asset('uploads/productbuys/' . $order->Product_image) }}" alt="" width="70px"
                                        height="70px"></td>
                                    <td>{{ $order->Product_Name }}</td>
                                    <td>{{ $order->Product_price }}</td>
                                    <td>{{ $order->Product_quantity}} {{ $order->Product_unit }}</td>
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

        // new Chart(lc, {
        //     type: 'line',
        //     data: {
        //         labels: sellLabels,
        //         datasets: [{
        //             label: 'ยอดขายรวมในแต่ละเดือน (บาท)',
        //             data: sellTotals,
        //             borderWidth: 2,
        //             backgroundColor: 'rgba(11, 156, 49, 0.6)',
        //             borderColor: 'rgba(11, 156, 49, 1)',
        //             fill: true
        //         }]
        //     },
        //     options: {
        //         scales: {
        //             y: {
        //                 beginAtZero: true
        //             }
        //         }
        //     }
        // });
        new Chart(lc2, {
            type: 'line',
            data: {
                labels: sellLabels,
                datasets: [{
                    label: 'ยอดซื้อรวมในแต่ละเดือน (บาท)',
                    data: sellTotals,
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
    </script>

    <script>
    fetch('/MonthNow') // เปลี่ยน URL ตาม API ของคุณ
        .then(response => response.json())
        .then(data => {
            document.getElementById('monthN-buy').textContent = `รายจ่ายในเดือน ${data.monthName}`;
            document.getElementById('monthN-quan').textContent = `จำนวนรายการซื้อในเดือน ${data.monthName}`;
            document.getElementById('yearN-buy').textContent = `รายจ่ายในปี ${data.year}`; 
        })
        .catch(error => console.error('Error fetching data:', error));
</script>

@endsection
