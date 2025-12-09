@extends('admin.maindesign')

@section('content')
@if (session('product_updated_message'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        {{ session('product_updated_message') }}
    </div>
@endif

<div class="container-fluid add-product">
    <form action="{{ route('admin.postupdateproduct', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="product_image" value="{{ $product->image }}">
        @if ($product->image)
        <div class="petite-image">
            <img src="/products/{{ $product->image }}">
            <p><strong>Image:</strong> {{ $product->image }}</p>
        </div>
        
        @endif
        <input type="text" value="{{ $product->name }}" name="product_name" placeholder="Enter product name">
        <textarea name="product_description" placeholder="Please enter a product description." rows="10">{{ $product->description }}</textarea>
        <input type="number" value="{{ $product->price }}" name="product_price" placeholder="Please enter the product's price.">
        <input type="number" value="{{ $product->quantity }}" name="product_quantity" placeholder="Please enter the product's quantity.">
        <select name="product_category">
            <option value="" disabled>-- Select a category --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->category }}</option>
            @endforeach
        </select>
        <button class="form-button" type="submit">Update Product</button>
    </form>
</div>
@endsection