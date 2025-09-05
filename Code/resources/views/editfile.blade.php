@extends('layoutOwner')
@section('title')
    File
@endsection
@section('contents')
    @if (session('status'))
        <h6 class="alert alert-success">{{ session('status') }}</h6>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 style="margin-top: 8%; margin-bottom: 20px; margin-left: 20px">แก้ไขไฟล์เอกสาร</h3>
                <div class="card File">
                    <div class="card-body">
                        <h4 style="margin-left: 10%;">รายละเอียด</h4>
                        <div class="form-addP">
                            <form action="{{ route('updateF', $file->File_ID) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <p>ชื่อไฟล์เอกสาร : <input type="text" value="{{ $file->File_name }}"
                                        class="form-control-sm" name="fname" required></p>
                                <p>ประเภทไฟล์เอกสาร :
                                    <select name="ftype" class="form-control-sm" required>
                                        @foreach ($fileTypes as $type)
                                            <option value="{{ $type }}"
                                                {{ $file->File_Type == $type ? 'selected' : '' }}>{{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </p>

                                <input type="file" class="form-control fileIP" name="file">
                                <div class="form-button">
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
