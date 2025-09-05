@extends('layoutOwner')
@section('title')
    Product
@endsection
@section('contents')
    @if (session('error'))
        <h6 class="alert alert-danger">{{ session('error') }}</h6>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="margin-left: 50px">
                <h3 style="margin-top: 60px; margin-bottom: 30px; margin-left: 20px">เพิ่มรายการสินค้า</h3>
                <div class="card Pro">
                    <div class="card-body">
                        <h4 style="margin-left: 10%;">รายละเอียด</h4>
                        <div class="form-addP">
                            <form action="{{ url('productbuycreate') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="inputBakery">
                                    <p>ชื่อสินค้า : <input type="text" class="form-control-sm" name="pbname" required></p>
                                    <p>ราคา : <input type="number" class="form-control-sm" name="pbprice" required></p>
                                    <p>จำนวน : 
                                        <input type="number" class="form-control-sm" name="pbquan" required>
                                    </p>
                                    <p>หน่วย:
                                        <select id="pbunit" name="pbunit" class="form-control-sm" required>
                                            <option selected disabled>เลือกหน่วย</option>
                                            <option value="ชิ้น">ชิ้น</option>
                                            <option value="กิโลกรัม">กิโลกรัม</option>
                                            <option value="มิลลิกรัม">มิลลิกรัม</option>
                                            <option value="กรัม">กรัม</option>
                                            <option value="ลิตร">ลิตร</option>
                                            <option value="มิลลิลิตร">มิลลิลิตร</option>
                                            <option value="แพ็ค">แพ็ค</option>
                                            <option value="ฟอง">ฟอง</option>
                                        </select>
                                    </p>
                                    <input type="file" class="form-control fileIP" name="pbimg" accept="image/*" onchange="previewImage(event)" required>
                                </div>
                                <div class="form-button">
                                    <a href="/ProductBuy" class="btn btn-danger">ยกเลิก</a>
                                    <button type="submit" class="btn btn-primary">ยืนยัน</button>
                                </div>
                            </form>
                            {{-- <div id="imagePreview"></div> --}}
                            <img id="imagePreview" src="{{ asset('images\preview.png') }}" alt="Main Image" width="300px" height="300px">
                        </div>
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
    </script>
@endsection