@extends('layoutAdmin')
@section('title')
    Create-User
@endsection

<style>
    input[type="date"],
    select {
        background-color: #BA9269;
        padding: 10px;
        position: relative;
        margin-left: 100px;
        margin-right: 100px;
        color: #ffffff;
        font-size: 14px;
        font-weight: bold;
        border: none;
        outline: none;
        border-radius: 5px;
    }
</style>

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="margin-left: 50px">
                <h3 style="margin-top: 60px; margin-bottom: 30px; margin-left: 20px">เพิ่มพนักงาน</h3>
                <div class="card Pro" style="width: 55%">
                    <div class="card-body">
                        <h4 style="margin-left: 10%; margin-top:20px">รายละเอียด</h4>
                        <div class="form-addP">
                            <form action="{{ url('insertUser') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="inputBakery" style="margin-left: 40px; display: flex; flex-direction: row; justify-content: space-between;">
                                    <div style="flex: 1; display: flex; flex-direction: column;">
                                        <div class="nameuser">
                                            <p>ชื่อพนักงาน : <input type="text" class="form-control-sm" name="usname" required></p>
                                        </div>
                                        <div class="emailuser">
                                            <p>อีเมล :
                                                <input type="text" class="form-control-sm" name="usemail" required>
                                                @error('usemail')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </p>
                                        </div>
                                        <div class="passworduser">
                                            <p>รหัสผ่าน :
                                                <input type="password" class="form-control-sm" name="uspass" required>
                                                @error('uspass')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div style="display: flex; flex-direction: column; justify-content: flex-start;">
                                        <div class="jobuser">
                                            <p>ตำแหน่ง:</p>
                                            <div>
                                                <select id="ustype" name="ustype" class="form-control-sm" style="position: relative; top: -12px;" required>
                                                    <option selected disabled>เลือกตำแหน่ง</option>
                                                    <option value="0">พนักงาน</option>
                                                    <option value="1">เจ้าของร้าน</option>
                                                </select>
                                            </div>
                                            @error('ustype')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                
                                        <div class="workuser">
                                            <div class="info-item date">
                                                <p>วันเริ่มทำงาน :</p>
                                            </div>
                                            <input type="date" name="ustime" style="margin: 0; position: relative; top: -10px;" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-button">
                                    <a href="/User" class="btn btn-danger">ยกเลิก</a>
                                    <button type="submit" class="btn btn-primary">ยืนยัน</button>
                                </div>
                            </form>
                            {{-- <div id="imagePreview"></div> --}}
                            {{-- <img id="imagePreview" src="{{ asset('images\preview.png') }}" alt="Main Image" width="300px" height="300px"> --}}
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

    {{-- <script>
        function previewImage(event) {
            var file = event.target.files[0];
            var reader = new FileReader();
            
            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
            }
            
            reader.readAsDataURL(file);
        }
    </script> --}}
@endsection
