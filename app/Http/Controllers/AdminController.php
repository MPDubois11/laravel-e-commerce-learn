<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;

class AdminController extends Controller
{
    public function admin() {
        return redirect('dashboard');
    }

    public function addCategory() {
        return view('admin.addcategory');
    }

    public function postAddCategory(Request $request) {
        $category = new Category();
        $category->category = $request->category;
        $category->save();
        return redirect()->back()->with('category_message', 'Category added successfully.');
    }

    public function viewCategories() {
        $categories = Category::all();

        return view('admin.viewcategories', compact('categories'));
    }

    public function deleteCategory($id) {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('delete_category', 'The category has been successfully deleted.');
    }

    public function updateCategory($id) {
        $category = Category::findOrFail($id);
        
        return view('admin.updatecategory', compact('category'));
    }

    public function postUpdateCategory(Request $request, $id) {
        $category = Category::findOrFail($id);

        $category->category = $request->category;
        $category->save();
        return redirect()->back()->with('category_updated_message', 'Category updated successfully.');
    }

    public function addProduct() {
        $categories = Category::all();

        return view('admin.addproduct', compact('categories'));
    }

    public function postAddProduct(Request $request) {
        $product = new Product();
        $product->name = $request->product_name;
        
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('products'), $imageName);
            $product->image = $imageName;
        }

        $product->description = $request->product_description;
        $product->price = $request->product_price;
        $product->quantity = $request->product_quantity;
        $product->category_id = $request->product_category;
        $product->save();
        return redirect()->back()->with('product_message', 'Product added successfully.');
    }

    public function viewProducts() {
        $products = Product::paginate(12);

        return view('admin.viewproducts', compact('products'));
    }

    public function deleteProduct($id) {
        $product = Product::findOrFail($id);
        $image_path = public_path('products/' . $product->image);
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        $product->delete();

        return redirect()->back()->with('delete_product', 'The product has been successfully deleted.');
    }

    public function updateProduct($id) {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        
        return view('admin.updateproduct', compact('product', 'categories'));
    }

    public function postUpdateProduct(Request $request, $id) {
        $product = Product::findOrFail($id);

        $product->name = $request->product_name;
        
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('products'), $imageName);
            $product->image = $imageName;
        }

        $product->description = $request->product_description;
        $product->price = $request->product_price;
        $product->quantity = $request->product_quantity;
        $product->category_id = $request->product_category;
        $product->save();

        return redirect()->back()->with('product_updated_message', 'Product updated successfully.');
    }

    public function searchProduct(Request $request) {
        $search = $request->search;
        
        $products = Product::where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('description', 'LIKE', '%' . $search . '%')
            ->orWhereHas('category', function ($query) use ($search) {
                $query->where('category', 'LIKE', '%' . $search . '%');
            })
            ->paginate(12);

        return view('admin.viewproducts', compact('products'));
    }

    public function viewOrders() {
        $orders = Order::latest()->get();

        return view('admin.vieworders', compact('orders'));
    }

    public function viewOrder($id) {
        $order = Order::with('items.product', 'user')->findOrFail($id);

        return view('admin.vieworder', compact('order'));
    }

    public function updateOrderStatus(Request $request, $id) {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('status_updated', 'Order status updated successfully.');
    }

}
