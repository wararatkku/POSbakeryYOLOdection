{{-- @extends('layoutOwner')
@section('title')
    อัปโหลดรูปภาพ AI Bakery
@endsection
@section('contents')
    @if (session('status'))
        <h6 class="alert alert-success">{{ session('status') }}</h6>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 style="margin-top: 8%; margin-bottom: 20px; margin-left: 20px">อัปโหลดรูปภาพ AI Bakery</h3>
                <div class="card File">
                    <div class="card-body">
                        <h4 style="margin-left: 10%;">รายละเอียด</h4>
                        <div class="form-addP">
                            <form action="{{ url('UploadAIB') }}" method="POST" enctype="multipart/form-data">
                                @csrf                              
                                <p>ชื่อเบเกอรี่ : <input type="text" class="form-control-sm" name="bname" required></p>
                                <input type="file" class="form-control fileIP" name="TImg[]" multiple required>
                                <div class="form-button upAIB">
                                    <a href="/File" class="btn btn-danger">ยกเลิก</a>
                                    <button type="submit" class="btn btn-primary">ยืนยัน</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
@endsection --}}
@extends('layoutOwner')
@section('title')
    อัปโหลดรูปภาพ AI Bakery
@endsection
@section('contents')
    @if (session('status'))
        <h6 class="alert alert-success">{{ session('status') }}</h6>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 style="margin-top: 8%; margin-bottom: 20px; margin-left: 20px">อัปโหลดรูปภาพ AI Bakery</h3>
                <div class="card File">
                    <div class="card-body">
                        <h4 style="margin-left: 10%;">รายละเอียด</h4>
                        <div class="form-addP">
                            <form action="{{ url('UploadAIB') }}" method="POST" enctype="multipart/form-data">
                                @csrf                              
                                <p>เลือกเบเกอรี่ : 
                                    <select id="bakerySelect" class="form-control-sm" name="selected_bakery">
                                        <option value="">-- Select Bakery --</option>
                                        @foreach($bakeries as $bakery)
                                            <option value="{{ $bakery->Bakery_name }}">{{ $bakery->Bakery_name }}</option>
                                        @endforeach
                                    </select>
                                </p>
                                <p>ชื่อเบเกอรี่ : 
                                    <input type="text" class="form-control-sm" id="bname" name="bname" required>
                                </p>
                                <input type="file" class="form-control fileIP" name="TImg[]" multiple required>
                                <div class="form-button upAIB">
                                    <a href="/File" class="btn btn-danger">ยกเลิก</a>
                                    <button type="submit" class="btn btn-primary">ยืนยัน</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bakerySelect = document.getElementById('bakerySelect');
        const bnameInput = document.getElementById('bname');

        bakerySelect.addEventListener('change', function() {
            if (this.value) {
                bnameInput.value = this.value;
                bnameInput.disabled = true;
            } else {
                bnameInput.value = '';
                bnameInput.disabled = false;
            }
        });
    });
</script>
