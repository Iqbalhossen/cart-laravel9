@extends('master')
@section('assignment')

@if(!empty(Session::get('cart')))

@php
$data = Session::get('cart') ;
@endphp

@endif

<div class="row row-cols-4 row-cols-md-4 g-4 my-5">
  @foreach($product as $key => $item)
  <div class="col">
    <div class="card">
      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSreAlx8vw_nSEP7lJzvHzk__lcXehVxw02kQ&usqp=CAU" class="card-img-top" alt="...">
      <div class="card-body">
        <a href="http://">
          <h5 class="card-title" id="pname">{{$item->name}}</h5>
        </a>
        <p class="card-text">€ {{$item->price}}</p>
        <button class="btn btn-primary btn-cart" id="{{ $item->id }}" type="submit" onclick="addToCart(this.id)">
          <i class="w-icon-cart"></i>
          <span>Add to Cart</span>
        </button>
      </div>
    </div>
  </div>
  @endforeach



</div>
@endsection