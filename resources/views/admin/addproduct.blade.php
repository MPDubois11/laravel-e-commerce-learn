@extends('admin.maindesign')

@section('content')
@if (session('product_message'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        {{ session('product_message') }}
    </div>
@endif

<div class="container-fluid add-product">
    <form action="{{ route('admin.postaddproduct') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="product_image">
        <input type="text" name="product_name" placeholder="Enter product name">
        <textarea name="product_description" placeholder="Please enter a product description." rows="10"></textarea>
        <input type="number" name="product_price" placeholder="Please enter the product's price.">
        <input type="number" name="product_quantity" placeholder="Please enter the product's quantity.">
        <select name="product_category">
            <option value="" selected disabled>-- Select a category --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->category }}</option>
            @endforeach
        </select>
        <button class="form-button" type="submit">Create Product</button>
    </form>
</div>
@endsection