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
            <div class="col-md-12" style="margin-left: 50px">
                <h3 style="margin-top: 60px; margin-bottom: 30px; margin-left: 20px">แก้ไขสินค้า</h3>
                <div class="card Pro">
                    <div class="card-body">
                        <h4 style="margin-left: 10%;">รายละเอียด</h4>
                        <div class="form-addP">
                            <form action="{{ route('updatePb', $productbuy->Product_ID) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="inputProduct">
                                    <p>ชื่อสินค้า : <input type="text" value="{{ $productbuy->Product_Name }}" class="form-control-sm" name="pbname"  required></p>
                                    <p>ราคา : <input type="number" value="{{ $productbuy->Product_price }}" class="form-control-sm" name="pbprice"  required></p>
                                    <p>จำนวน : <input type="number" value="{{ $productbuy->Product_quantity }}" class="form-control-sm" name="pbquan"  required></p>
                                    <p>หน่วย:
                                        <select id="pbunit" name="pbunit" class="form-control-sm" required>
                                            @foreach ($productbuy_type as $type)
                                            <option value="{{ $type }}"
                                                {{ $productbuy->Product_unit == $type ? 'selected' : '' }}>{{ $type }}
                                            </option>
                                        @endforeach
                                        </select>
                                    </p>
                                    <input type="file" class="form-control fileIP" name="pbimg">
                                </div>
                                <div class="form-button upB">
                                    <a href="/ProductBuy" class="btn btn-danger">ยกเลิก</a>
                                    <button type="submit" class="btn btn-primary">ยืนยัน</button>
                                </div>
                            </form>
                            <img id="imagePreview" src="{{ asset('uploads/productbuys/' . $productbuy->Product_image)}}" width="300px" height="300px" alt="image_preview">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection