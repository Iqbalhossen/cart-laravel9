@extends('master')
@section('assignment')



<div class="container py-5">
  <h3>
    Contact Information
  </h3>

  <div class='ContactInformation'>

    <!-- error message show  -->
    <div id="errorMessage">
    </div>
    <!-- error message show  -->

  </div>

  <!-- show User Info -->
  <div class="row" id='showUserInfo'>
  </div>
  <!-- show User Info -->

</div>

<!-- edit User Info show  -->
<div id="editUserInfo">
</div>
<!-- edit User Info show  -->

<hr>
<h4>Shipping</h4>

<div id="shipping">
  <div class='shippingMessage'>
    <!-- shipping error Message show  -->

    <div id="shippingerrorMessage">
    </div>
    <!-- shipping error Message show  -->

  </div>

  <!-- shipping Form section  -->
  <div class="row">
    <div class="col-6">
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">address</label>
        <input type="text" class="form-control"  id='address'>
      </div>
    </div>
    <div class="col-6">
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">apt num (optional)</label>
        <input type="text" class="form-control" id="aptnum">
      </div>
    </div>
    <div class="col-6">
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">city</label>
        <input type="text" class="form-control" id="city">
      </div>
    </div>
    <div class="col-6">
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">State</label>
        <input type="text" class="form-control" id="state">
      </div>
      <div class="col-6">
        <div class="mb-3">
          <label for="exampleInputPassword1" class="form-label">country</label>
          <input type="text" class="form-control" id="country">
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary" onclick="ShippingSubmit()">Next</button>
  </div>
</div>
<!-- shipping Form section  -->

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


@endsection