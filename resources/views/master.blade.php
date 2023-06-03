<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

    @include('layouts.header')
    <div class="container my-5">
        <!-- <div class="my-4">

            @if(!empty(Session::get('cart')))

            @php
            $data = Session::get('cart') ;
            @endphp
            <a href="{{route('cart.page')}}">Cart ({{count($data)}})</a>

            @else
            <a href="{{route('cart.page')}}">Cart (0) </a>

            @endif
            
        </div> -->
        @yield('assignment')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>



    <!------------------------------------- Product Add to Cart Ajax Section  ----------------------------->



    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        ////////// Start Add To Cart Product 
        function addToCart(product_id) {
            var product_name = $('#pname').text();
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    product_name: product_name,
                },
                url: "/cart/data/store/" + product_id,
                success: function(data) {
                    couponRemove();
                    window.location.href = "/cart";
                    // console.log(data.success);
                    // End Message
                }
            })
        }
        ////////// End Add To Cart Product
    </script>

    <!---------------------------------- /// Load My Cart /// -------------->

    <script type="text/javascript">
        function total() {
            $.ajax({
                type: 'GET',
                url: '/user/get-cart-total-price',
                dataType: 'json',
                success: function(response) {
                    // console.log(response.total);
                    var rows = ""
                    if (response.total > 0) {
                        rows += `€ ${response.total}`
                        $('#totalpage').html(rows);
                    } else {
                        rows += `0`
                        $('#totalpage').html(rows);
                    }

                }
            })
        }
        total();

        function cart() {
            $.ajax({
                type: 'GET',
                url: '/user/get-cart-product',
                dataType: 'json',
                success: function(response) {
                    var rows = ""
                    $.each(response.carts, function(key, value) {
                        rows += ` <tr>
                        <td>${value.product_name}</td>
                        <td>€ ${value.price}</td>
                        <td>
                        
                        ${value.quantity > 1
                                ? `<button type="submit" class="btn btn-danger btn-sm" id="${value.product_id}" onclick="cartDecrement(${value.product_id})"  style="padding-top:5px;padding-bottom:5px;padding-left:12px;padding-right:12px;">-</button> `
                                : `<button type="submit" class="btn btn-danger btn-sm" disabled  style="padding-top:5px;padding-bottom:5px;padding-left:12px;padding-right:12px;" >-</button> `
                                }      
                            <input type="text" value="${value.quantity}" min="1" max="100" disabled="" style="width:25px;" >      
                            <button type="submit" class="btn btn-success btn-sm" id="${value.product_id}" onclick="cartIncrement(${value.product_id})" style="padding-top:5px;padding-bottom:5px;padding-left:12px;padding-right:12px;">+</button> 
                            
                        </td>
                        <td>€ ${value.price * value.quantity}</td>
                        <td>
                        <button type="submit" class="btn btn-close" id="${value.product_id}" onclick="cartRemove(${value.product_id})"><i
                                                                            class="fas fa-times"></i></button>
                                                                            </td>
                        </tr>`
                    });

                    $('#cartPage').html(rows);
                }
            })
        }
        cart();



        ///  Cart remove Start 
        function cartRemove(product_id) {
            $.ajax({
                type: 'GET',
                url: '/user/cart-remove/' + product_id,
                dataType: 'json',
                success: function(data) {
                    // console.log(data.success)
                    cart();
                    total();
                    couponRemove();
                }
            });
        }
        // End Cart remove  



        // -------- CART INCREMENT --------//
        function cartIncrement(product_id) {
            // console.log(product_id);
            $.ajax({
                type: 'GET',
                url: "/cart-increment/" + product_id,
                dataType: 'json',
                success: function(data) {
                    cart();
                    total();
                    couponRemove();
                }
            });
        }
        // ---------- END CART INCREMENT -----///


        // -------- CART Decrement  --------//
        function cartDecrement(product_id) {
            $.ajax({
                type: 'GET',
                url: "/cart-decrement/" + product_id,
                dataType: 'json',
                success: function(data) {
                    cart();
                    total();
                    couponRemove();
                }
            });
        }
        // ---------- END CART Decrement -----///


        /// <!-- End Load Wisch list Data  -->
    </script>

    <!-- //End Load My cart / -->





    <!---------------- checkout Ajax  Section  Start------------------->

    <script type="text/javascript">
        //// Total Price Section start
        function totalPrice() {
            $.ajax({
                type: 'GET',
                url: '/user/get-checkout-total-price',
                dataType: 'json',
                success: function(response) {
                    // console.log(response);
                    var rows = ""
                    if (response.total[0]?.total > 0) {
                        rows += `€ ${response.total[0].total}  <p class="ms-5 fs-5">(coupon name : ${response.total[0].coupon_name}) 
                            <button type="submit" onclick="couponRemove()">X</button>
                            </p>`
                        $('#totalAmountpage').html(rows);
                    } else {
                        rows += `€ ${response.total}`
                        $('#totalAmountpage').html(rows);
                    }

                }
            })
        }
        totalPrice();
        //// Total Price Section end

        //// Coupon Section start
        function applyCoupon() {
            var coupon_name = $('#coupon_name').val();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                data: {
                    coupon_name: coupon_name
                },
                url: "{{ url('/coupon-apply') }}",
                success: function(data) {
                    totalPrice();
                    if (data.error == true) {
                        var rows = ""

                        rows += `<div class="alert alert-warning alert-dismissible fade show" role="alert" >
                                ${data.message}        
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`
                        $('#DiscounterrorMessage').html(rows);
                    } else if (data.status == true) {
                        $('#DiscounterrorMessage').hide();
                    }
                }
            })
        }

        //// Coupon Section End

        //////////////// =========== Start Coupon Remove Start ================= ////  -->
        function couponRemove() {
            $.ajax({
                type: 'GET',
                url: "{{ url('/coupon-remove') }}",
                dataType: 'json',
                success: function(data) {
                    totalPrice();
                }
            });
        }
        //////////////// =========== Start Coupon Remove end ================= ////  -->
    </script>
    <!---------------- checkout Ajax  Section  End------------------->



    <!--/////////// Contact Information section start  //////////////////-->

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        ///// Contact Information store section start
        function ContactInformationSubmit() {
            var firstName = $('#firstName').val();
            var lastName = $('#lastName').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            var password = $('#password').val();
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    firstName: firstName,
                    lastName: lastName,
                    email: email,
                    phone: phone,
                    password: password,
                },
                url: "/user/information/store",
                success: function(data) {
                    // console.log(data);
                    var rows = ""
                    if (data.success === false) {

                        rows += `<div class="alert alert-warning alert-dismissible fade show" role="alert" >
                                ${data.message}        
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`
                        $('#errorMessage').html(rows);

                    }
                    window.location.href = "/shipping";
                    // console.log(data.success);

                }
            })
        }
        ///// Contact Information store section End

        ///// Contact Information Show section start
        function ContactInformationShow() {
            $.ajax({
                type: 'GET',
                url: '/contact-information-info-show',
                dataType: 'json',
                success: function(response) {
                    // console.log(response.shippingInfo[0].id);
                    var rows = ""
                    rows += ` <div class="col-6">
                                <div class="mb-3">
                                    <h3>
                                    ${response.userInfo[0].firstName} ${response.userInfo[0].lastName}
                                    </h3>
                                    <h5>${response.userInfo[0].email}</h5>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary" onclick="ContactInformationUpdate()">Edit</button>
                                    </div>
                                 </div> `
                    $('#showUserInfo').html(rows);
                }
            })
        }
        ContactInformationShow();
        ///// Contact Information Show section End

        ///// Contact Information edit section start
        function ContactInformationUpdate() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: '/user/information/edit',
                dataType: 'json',
                success: function(response) {
                    $('#showUserInfo').hide();
                    var rows = ""
                    $.each(response.data, function(key, value) {
                        rows += `<div class="row" >
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="exampleInputPassword1" class="form-label">first name</label>
                                        <input type="text" class="form-control" value="${value.firstName}" id='firstName' >                          
                                 </div>
                             </div>
                                <div class="col-6">
                                <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">last name</label>
                            <input type="text" class="form-control" value="${value.lastName}" id="lastName">
                        </div>
                                </div>
                                <div class="col-6">

                                <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">email</label>
                            <input type="email" class="form-control" value="${value.email}" id="email">
                        </div>
                                </div>
                                <div class="col-6">

                                <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">phone optional</label>
                            <input type="tel" class="form-control" value="${value.phone ? value.phone : ''}" id="phone">
                        </div>
                                </div>
                            </div>                  
                        <button type="submit" class="btn btn-primary" onclick="ContactInformationUpdateSubmit()">Update</button>
                        </div>`
                    });
                    $('#editUserInfo').html(rows);
                }
            })
        }
        //// Contact Information edit section end





        //// Contact Information update section start
        function ContactInformationUpdateSubmit() {
            var firstName = $('#firstName').val();
            var lastName = $('#lastName').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    firstName: firstName,
                    lastName: lastName,
                    email: email,
                    phone: phone,
                },
                url: "/user/information/update/store",
                success: function(data) {
                    // console.log(data);
                    var rows = ""
                    if (data.success === false) {
                        // error message 
                        rows += `<div class="alert alert-warning alert-dismissible fade show" role="alert" >
                        ${data.message}        
                             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`
                        $('#errorMessage').html(rows);
                        // error message 
                    } else {
                        $('#showUserInfo').show();
                        $('#editUserInfo').hide();
                        ContactInformationShow()
                    }
                }
            })
        }
        //// Contact Information update section end
    </script>
    <!--/////////// Contact Information section end  //////////////////-->


    <!--/////////// Shipping Information section start  //////////////////-->
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Shipping store section start 
        function ShippingSubmit() {
            var address = $('#address').val();
            var aptnum = $('#aptnum').val();
            var city = $('#city').val();
            var state = $('#state').val();
            var country = $('#country').val();

            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    address: address,
                    aptnum: aptnum,
                    city: city,
                    state: state,
                    country: country,
                },
                url: "/user/shipping/store",
                success: function(data) {
                    console.log(data)
                    var rows = ""
                    if (data.success === false) {
                        // error message 
                        rows += `<div class="alert alert-warning alert-dismissible fade show" role="alert" >
                                 ${data.message}        
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`
                        $('#shippingerrorMessage').html(rows);
                        // error message 
                    } else {
                        window.location.href = "/summary";
                    }
                }
            })
        }
        // Shipping store section ends

        // Shipping show section ends
        function shippingInfoShow() {
            $.ajax({
                type: 'GET',
                url: '/shipping-info-show',
                dataType: 'json',
                success: function(response) {
                    // console.log(response.shippingInfo[0].id);
                    var rows = ""
                    rows += ` <div class="col-6">
                        <div class="mb-3">
                            <h3>
                            ${response.shippingInfo[0].address}
                            </h3>
                            <h5>${response.shippingInfo[0].city}</h5>
                            <h5>${response.shippingInfo[0].state}</h5>
                            <h5>${response.shippingInfo[0].country}</h5>
                        </div>
                        </div>
                        <div class="col-6">
                        <div class="mb-3">
                        <button type="submit" class="btn btn-primary" onclick="shippingInfoEdit()">Edit</button>
                        </div>
                        </div> `
                    $('#showshippingInfo').html(rows);
                }
            })
        }
        shippingInfoShow();
        // Shipping show section ends



        // Shipping edit section start
        function shippingInfoEdit() {
            $.ajax({
                type: 'GET',
                url: '/shipping/information/edit',
                dataType: 'json',
                success: function(response) {
                    console.log(response)
                    $('#showshippingInfo').hide();
                    $('#showshippingInfoEdit').show();
                    var rows = ""
                    $.each(response.shippingInfo, function(key, value) {
                        rows += ` <div class="col-6">
                        <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">address</label>
                        <input type="text" class="form-control" value="${value.address}" id='address' >
                        </div>
                        </div>
                        <div class="col-6">
                        <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">apt num (optional)</label>
                        <input type="text" class="form-control" value="${value.aptnum ? value.aptnum : ''}" id="aptnum">
                        </div>
                        </div>
                        <div class="col-6">
                        <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">city</label>
                        <input type="text" class="form-control" value="${value.city}" id="city">
                        </div>
                        </div>
                            <div class="col-6">
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">State</label>
                                <input type="text" class="form-control" value="${value.state}" id="state">
                            </div>
                            <div class="col-6">
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">country</label>
                                <input type="text" class="form-control" value="${value.country}" id="country">
                            </div>
                        </div>
                        </div>
                        <button type="submit" class="btn btn-primary" onclick="ShippingUpdate()">Update</button>
                        </div>`
                    });
                    $('#showshippingInfoEdit').html(rows);
                }
            })
        }
        // Shipping edit section ends



        // Shipping update section start 
        function ShippingUpdate() {
            var address = $('#address').val();
            var aptnum = $('#aptnum').val();
            var city = $('#city').val();
            var state = $('#state').val();
            var country = $('#country').val();
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    address: address,
                    aptnum: aptnum,
                    city: city,
                    state: state,
                    country: country,
                },
                url: "/user/shipping/update",
                success: function(data) {
                    console.log(data)
                    var rows = ""
                    if (data.success === false) {
                        // error message 
                        rows += `<div class="alert alert-warning alert-dismissible fade show" role="alert" >
                                  ${data.message}        
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`
                        $('#shippingerrorMessage').html(rows);
                        //   error messge 
                    } else {
                        shippingInfoShow();
                        $('#showshippingInfo').show();
                        $('#showshippingInfoEdit').hide();
                    }
                }
            })
        }
        // Shipping update section ends
    </script>
    <!--/////////// Shipping Information section end  //////////////////-->























    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function passwordShow() {
            var createUser = $('#createUser').val();
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    createUser: createUser,
                },
                url: "/new/user/password/show",
                success: function(data) {
                    $('#showpassword').show();
                    var rows = ""
                    rows += `<div class="mb-3">
                            <label for="exampleInputPassword1"  class="form-label">Password</label>

                            <input type="password" name='password' class="form-control" id="password">

                        </div>`
                        $('#showpassword').html(rows);
                }
            })
        }

        function passwordHide() {

            var alreadyUser = $('#alreadyUser').val();
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    alreadyUser: alreadyUser,
                },
                url: "/new/user/password/hide",
                success: function(data) {
                    $('#showpassword').hide();
                }
            })
        }



        // Shipping store section ends
    </script>








</body>

</html>