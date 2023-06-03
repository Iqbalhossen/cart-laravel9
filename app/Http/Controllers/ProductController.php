<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
class ProductController extends Controller
{
    public function ProductPage(){
        $product = Product::get();   
        return view('productPage',compact('product'));
    }
    public function Index(){
        $product = Product::get();   
        return view('productPage',compact('product'));
    }
}
