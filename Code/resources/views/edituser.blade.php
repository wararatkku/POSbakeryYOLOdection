@extends('layoutAdmin')
@section('title')
    Edit-User
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
                <h3 style="margin-top: 60px; margin-bottom: 30px; margin-left: 20px">แก้ไขข้อมูลพนักงาน</h3>
                <div class="card Pro" style="width: 55%">
                    <div class="card-body">
                        <h4 style="margin-left: 10%; margin-top:20px">รายละเอียด</h4>
                        <div class="form-addP">
                            <form action="{{ route('updateUs', $user->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="inputBakery" style="margin-left: 40px; display: flex; flex-direction: row; justify-content: space-between;">
                                    <div style="flex: 1; display: flex; flex-direction: column;">
                                        <div class="nameuser">
                                            <p>ชื่อพนักงาน : <input type="text" value="{{ $user->name }}" class="form-control-sm" name="usname" required></p>
                                        </div>
                                        <div class="emailuser">
                                            <p>อีเมล :
                                                <input type="text" value="{{ $user->email }}" class="form-control-sm" name="usemail" required>
                                                @error('usemail')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </p>
                                        </div>
                                        <div class="passworduser">
                                            <p>รหัสผ่าน :
                                                <input type="password" class="form-control-sm" name="uspass">
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
                                                    <option value="0" {{ $user->is_admin == 0 ? 'selected' : '' }}>พนักงาน</option>
                                                    <option value="1" {{ $user->is_admin == 1 ? 'selected' : '' }}>เจ้าของร้าน</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="workuser">
                                            <div class="info-item date">
                                                <p>วันเริ่มทำงาน :</p>
                                            </div>
                                            <input type="date" name="ustime"
                                                   value="{{ \Carbon\Carbon::parse($user->work)->format('Y-m-d') }}"
                                                   style="margin: 0; position: relative; top: -10px;" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-button">
                                    <a href="/User" class="btn btn-danger">ยกเลิก</a>
                                    <button type="submit" class="btn btn-primary">ยืนยัน</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
@endsection

