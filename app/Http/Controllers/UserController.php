<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class UserController extends Controller
{
    public function index() {
        if (Auth::check() && Auth::user()->user_type == "user") {
            return view('dashboard');
        } elseif (Auth::check() && Auth::user()->user_type == "admin") {
            return view('admin.dashboard');
        }
    }

    public function homePage() {
        $products = Product::all();

        return view('index', compact('products'));
    }
}
