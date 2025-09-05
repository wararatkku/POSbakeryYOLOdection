@extends('layoutOwner')
@section('title')
    Product
@endsection
@section('contents')
    <div class="container">
        <div class="row">
            <h3 style="display: inline-block; margin-top: 5%; margin-bottom: 20px; margin-left: 20px">เพิ่มสินค้าใหม่</h3>
            <div class="col-md-12" style="display:flex"">
                <div class="card Pro">
                    <div class="card-body">
                        <h4 style="margin-left: 10%;">รายละเอียด</h4>
                        <div class="form-addP">
                            <form action="{{ url('AddProduct') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="inputBakery" style="margin-top: 5%">
                                    <p>ชื่อสินค้า : <input id='pname' type="text" class="form-control-sm"
                                            name="pname" required></p>
                                    <p>ชื่อสินค้าภาษาอังกฤษ : <input type="text" class="form-control-sm" name="pnameen"
                                            required></p>
                                    {{-- <p>จำนวน : <input type="text" class="form-control-sm" name="pquan" required></p> --}}
                                    <p>ราคา : <input type="text" class="form-control-sm" name="pprice" required></p>
                                    <input type="file" class="form-control fileIP" name="pimg" accept="image/*"
                                        onchange="previewImage(event)" required>
                                </div>
                                <div class="form-button">
                                    <a href="/Product" class="btn btn-danger">ยกเลิก</a>
                                    <button type="submit" class="btn btn-primary">ยืนยัน</button>
                                </div>
                            </form>
                            {{-- <div id="imagePreview"></div> --}}
                            <img id="imagePreview" src="{{ asset('images\preview.png') }}" alt="Main Image" width="300px"
                                height="300px">
                        </div>
                    </div>
                </div>
                <div class="listBakery">
                    <h4
                        style=" text-align: center; position: sticky; top: 20px; background-color: #ddaf81; padding: 20px; color: #fff">
                        สินค้าที่มีในร้าน</h4>
                    <div class="table-lb">
                        <table class="listBT">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="bakeryList">
                                @foreach ($bakery as $bakery)
                                    <tr>
                                        <td><img src="{{ asset('uploads/bakeries/' . $bakery->Bakery_image) }}"
                                                alt="" width="50px" height="50px"></td>
                                        <td>{{ $bakery->Bakery_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    @if(session('status') == 'error')
                        <img src="images/cancel.png" alt="Error" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">มีเบเกอรี่นี้แล้ว</h3>
                    @endif    
                </div>
                <div class="modal-footer @if(session('status') == 'error') bg-danger @endif">
                    <div class="w-100 text-center">
                        <button type="button" class="btn" data-dismiss="modal" style="color: #fff; width: 100%">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    {{-- <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.style.backgroundImage = 'url(' + reader.result + ')';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script> --}}
    <script>
        function previewImage(event) {
            var file = event.target.files[0];
            var reader = new FileReader();

            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
            }

            reader.readAsDataURL(file);
        }
        document.getElementById('pname').addEventListener('input', function() {
            const query = this.value;
            let bakery = @json($bakery);

            // เรียกใช้ AJAX เพื่อค้นหาสินค้า
            fetch(`/search-bakery?query=${query}`) // Use backticks (`` ` ``) for the template literal
            .then(response => response.json())
            .then(data => {
                const bakeryList = document.getElementById('bakeryList');
                bakeryList.innerHTML = ''; // Clear the current list

                // Display matching products
                data.bakeries.forEach(bakery => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><img src="{{ asset('uploads/bakeries/') }}/${bakery.Bakery_image}" alt="" width="50px" height="50px"></td>
                        <td>${bakery.Bakery_name}</td>
                    `;
                        bakeryList.appendChild(row);
                    });
                });
        });
        $(document).ready(function() {
            @if(session('status'))
                $('#statusModal').modal('show');  
            @endif
        });
        
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
@endsection
