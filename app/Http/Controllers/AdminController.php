<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

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
}
