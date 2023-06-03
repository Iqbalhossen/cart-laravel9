@extends('master')
@section('assignment')

@php

$contactInfo = DB::table('user_infos')->where('userId', Auth::id())->latest()->first();

@endphp
<div class="container py-5">
  <h3>
    Contact Information
  </h3>

  <div class='ContactInformation'>

    <!-- Error message show section  -->
    <div id="errorMessage">
    </div>
    <!-- Error message show section  -->

  </div>

  <!-- form id use to ajax must important -->
  <div class="row">
    <div class="col-6">
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">first name</label>
        @if($contactInfo)
        <input type="text" class="form-control" value="{{$contactInfo->firstName}}" id='firstName'>
        @else
        <input type="text" class="form-control" id='firstName'>
        @endif
      </div>
    </div>
    <div class="col-6">
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">last name</label>
        @if($contactInfo)
        <input type="text" class="form-control" value="{{$contactInfo->lastName}}" id="lastName">
        @else
        <input type="text" class="form-control" id="lastName">
        @endif
      </div>
    </div>
    <div class="col-6">
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">email</label>
        @if($contactInfo)
        <input type="email" class="form-control" value="{{$contactInfo->email}}" id="email">
        @else
        <input type="email" class="form-control" id="email">
        @endif
      </div>
    </div>
    <div class="col-6">
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">phone optional</label>
        @if($contactInfo)
        <input type="tel" class="form-control" value="{{$contactInfo->phone}}" id="phone">
        @else
        <input type="tel" class="form-control" id="phone">
        @endif
      </div>
    </div>
    <div class="col-6" id='showpassword'>
      
    </div>

    <div class="col-12">
      <div class="d-flex ">
        <div class="mb-3 me-5">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="flexRadioDefault" value="1" onclick="passwordShow()" id='createUser'>
            <label class="form-check-label" for="createUser">
              Create New User
            </label>
          </div>

        </div>
        <div class="mb-3">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="flexRadioDefault" value="2" onclick="passwordHide()" id='alreadyUser' checked>
            <label class="form-check-label" for="alreadyUser">
              Already have a account
            </label>
          </div>
        </div>
      </div>
    </div>

  </div>
  <button type="submit" class="btn btn-primary" onclick="ContactInformationSubmit()">Next</button>
</div>

<hr>
<h4>Shipping</h4>
<hr>
<h4>Summary</h4>
<div class="pt-5">

  <!-- checkout product show  -->
  @foreach($data as $item)
  <div class='d-flex justify-content-evenly'>
    <h4>{{$item['product_name']}}
      <br> <span class='fs-6'>{{$item['quantity']}} @ € {{$item['price']}}</span>
    </h4>
    <h6>€ {{$item['price'] * $item['quantity']}}</h6>
  </div>
  <hr>
  @endforeach
  <!-- checkout product show  -->

</div>
<div class='d-flex justify-content-evenly'>
  <h4>Subtotal</h4>
  <h5>€ {{$sum}}</h5>
</div>

<!-- coupon apply -->
<div class="mb-3">
  <div id="DiscounterrorMessage">
  </div>

  <label for="exampleInputEmail1" class="form-label">Discount code</label>
  <input type="text" id='coupon_name' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
</div>
<button type="submit" class="btn btn-primary" onclick="applyCoupon()" id='couponField'>Apply</button>
<!-- checkout product show  -->

<div class='d-flex justify-content-evenly'>
  <h4>Total</h4>
  <h5 id='totalAmountpage'> </h5>
</div>
</div>


@endsection