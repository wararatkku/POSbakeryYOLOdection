@extends('layoutOwner')
@section('title')
    Product
@endsection
@section('contents')
    @if (session('status'))
        <h6 class="alert alert-success">{{ session('status') }}</h6>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 style="margin-top: 8%; margin-bottom: 20px; margin-left: 20px">แก้ไขสินค้า</h3>
                <div class="card Pro">
                    <div class="card-body">
                        <h4 style="margin-left: 10%;">รายละเอียด</h4>
                        <div class="form-addP">
                            <form action="{{ route('updateP', $bakery->Bakery_ID) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="inputBakery" style="margin-top: 5%">
                                    <p>ชื่อสินค้า : <input type="text" value="{{ $bakery->Bakery_name }}" class="form-control-sm" name="pname"  required></p>
                                    <p>ชื่อสินค้าภาษาอังกฤษ : <input type="text" value="{{ $bakery->Bakery_name_en }}" class="form-control-sm" name="pnameen"  required></p>
                                    {{-- <p>จำนวน : <input type="text" value="{{ $bakery->Bakery_quantity }}" class="form-control-sm" name="pquan"  required></p> --}}
                                    <p>ราคา : <input type="text" value="{{ $bakery->Bakery_price }}" class="form-control-sm" name="pprice"  required></p>
                                    <input type="file" class="form-control fileIP" name="pimg">
                                </div>
                                <div class="form-button upB">
                                    <a href="/Product" class="btn btn-danger">ยกเลิก</a>
                                    <button type="submit" class="btn btn-primary">ยืนยัน</button>
                                </div>
                            </form>
                            <img id="imagePreview" src="{{ asset('uploads/bakeries/' . $bakery->Bakery_image)}}" width="300px" height="300px" alt="image_preview">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection