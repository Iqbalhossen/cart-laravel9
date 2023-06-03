@extends('master')
@section('assignment')

@if(!empty(Session::get('cart')))

<div class="container">
  <div class="row">
    <div class="col-8">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Product name</th>
            <th scope="col">Price</th>
            <th scope="col">Quantity</th>
            <th scope="col">Subtotal</th>
          </tr>
        </thead>

        <!------ Cart Page show section  ------>
        <tbody id="cartPage">
        </tbody>
        <!------ Cart Page show section  ------>

      </table>
    </div>
    <div class="col-4">
      <table class="table">
        <tbody>
          <tr>        
            <td>Total : </td>

            <!-- Total price section  -->
            <td colspan="2" id="totalpage"></td>
            <!-- Total price section  -->

          </tr>
        </tbody>
      </table>
      <a href="{{route('checkout.page')}}" class="btn btn-primary">CheckOut</a>
    </div>
  </div>
</div>
@else

<div class="card mb-3" style="max-width: 540px;">
  <div class="row g-0">
    <div class="col-md-4">
    </div>
    <div class="col-md-8">
      <div class="card-body">
        <a href="{{route('product.page')}}"><h5 class="card-title">Shop now</h5></a>
        
      </div>
    </div>
  </div>
</div>


@endif




@endsection