@extends('master')
@section('assignment')

<div class="container py-5">
    <h3>
        Contact Information
    </h3>

    <div class='ContactInformation'>

        <!-- error messge show  -->
        <div id="errorMessage">
        </div>
        <!-- error messge show  -->

    </div>

    <!-- show User Info -->
    <div class="row" id='showUserInfo'>
    </div>
    <!-- show User Info -->

</div>

<!-- edit User Info -->

<div id="editUserInfo">
</div>
<!-- edit User Info -->

<hr>
<h4>Shipping</h4>

<div id="shipping">

    <div class='shippingMessage'>

        <!-- shipping error Message -->
        <div id="shippingerrorMessage">
        </div>
        <!-- shipping error Message -->

    </div>

    <!-- show shipping Info -->
    <div class="row" id='showshippingInfo'>
    </div>
    <!-- show shipping Info -->

    <!--show shipping Info Edit -->
    <div class="row" id='showshippingInfoEdit'>
    </div>
    <!--show shipping Info Edit -->

    <hr>
    <h4>Summary</h4>
    <div class="pt-5">
        @foreach($data as $item)
        <div class='d-flex justify-content-evenly'>
            <h4>{{$item['product_name']}}
                <br> <span class='fs-6'>{{$item['quantity']}} @ € {{$item['price']}}</span>
            </h4>
            <h6>€ {{$item['price'] * $item['quantity']}}</h6>
        </div>
        <hr>
        @endforeach
    </div>
    <div class='d-flex justify-content-evenly'>
        <h4>Subtotal</h4>
        <h5>€ {{$sum}}</h5>
    </div>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Discount code</label>
        <input type="text" id='coupon_name' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <button type="submit" class="btn btn-primary" onclick="applyCoupon()" id='couponField'>Apply</button>
    <div class='d-flex justify-content-evenly'>
        <h4>Total</h4>
        <h5 id='totalAmountpage'> </h5>
    </div>


   

</div>

<div class="container py-5 my-5 px-5 text-center">
<form action="{{route('oder.confirm')}}" method="post">
    @csrf
    <button type="submit" class="btn btn-primary">Confirm order</button>
    </form>
</div>

@endsection