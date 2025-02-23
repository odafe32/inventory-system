<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category; 

class MainController extends Controller
{
    //
public function showDashboard(){
    return view('inventory.dashboard', [
        'meta_title' => 'Dashboard - Larkon | inventory management Services',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
        'page_title' => 'Dashboard',
    ]);
}

//products list
public function showProduct()
{
    try {
        // Get products with pagination
        $products = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('inventory.product.product', [
            'meta_title' => 'Products - Larkon',
            'meta_desc' => 'A Management system that helps businesses keep track of their products...',
            'meta_image' => url('images/favicon.ico'),
            'page_title' => 'Product',
            'products' => $products
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching products: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error loading products. Please try again.');
    }
}

public function deleteProduct($id)
{
    try {
        $product = Product::findOrFail($id);
        
        // Delete the product image if it exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        // Delete the product
        $product->delete();

        return redirect()->route('product-list')
            ->with('success', 'Product deleted successfully!');

    } catch (\Exception $e) {
        \Log::error('Error deleting product: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Failed to delete product. Please try again.');
    }
}



public function newProduct()
{
    $categories = Category::all();
    $tagNumber = Product::generateTagNumber();
    
    return view('inventory.product.create-product', [
        'meta_title' => 'Create Product - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
        'page_title' => 'Create Product',
        'categories' => $categories,
        'generated_tag' => $tagNumber
    ]);
}

public function storeProduct(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Add this validation
            'brand' => 'required|string',
            'weight' => 'nullable|numeric|min:0',
            'gender' => 'required|in:Men,Women,Unisex',
            'description' => 'required|string',
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'string|in:XS,S,M,L,XL,XXL',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Generate tag number
        $validated['tag_number'] = Product::generateTagNumber();

        // Convert sizes array to string
        $validated['size'] = implode(',', $request->sizes);
        unset($validated['sizes']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        // Create product with category
        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully!',
            'redirect' => route('product-list')
        ]);

    } catch (\Exception $e) {
        \Log::error('Error creating product: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to create product. ' . $e->getMessage()
        ], 422);
    }
}
public function editProduct($id)
{
    try {
        $product = Product::with('category')->findOrFail($id);
        $categories = Category::all();
        
        return view('inventory.product.edit-product', [
            'meta_title' => 'Edit Product - Larkon',
            'meta_desc' => 'Edit product details...',
            'meta_image' => url('images/favicon.ico'),
            'page_title' => 'Edit Product',
            'product' => $product,
            'categories' => $categories
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching product for edit: ' . $e->getMessage());
        return redirect()->route('product-list')
            ->with('error', 'Product not found.');
    }
}

public function updateProduct(Request $request, $id)
{
    try {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string',
            'weight' => 'nullable|numeric|min:0',
            'gender' => 'required|in:Men,Women,Unisex',
            'description' => 'required|string',
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'string|in:XS,S,M,L,XL,XXL',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Convert sizes array to string
        $validated['size'] = implode(',', $request->sizes);
        unset($validated['sizes']);

        // Handle image upload if new image is provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            // Store new image
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        // Update product
        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'redirect' => route('product-list')
        ]);

    } catch (\Exception $e) {
        \Log::error('Error updating product: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to update product. ' . $e->getMessage()
        ], 422);
    }
}

//product details
public function showProductDetails($id)
{
    try {
        $product = Product::with('category')->findOrFail($id);
        
        return view('inventory.product.product-details', [
            'meta_title' => 'Product Details - Larkon',
            'meta_desc' => 'A Management system that helps businesses keep track of their products...',
            'meta_image' => url('images/favicon.ico'),
            'page_title' => 'Product Details',
            'product' => $product
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching product details: ' . $e->getMessage());
        return redirect()->route('product-list')
            ->with('error', 'Product not found.');
    }
}
//catogries
public function showCategory()
{
    try {
        // Fetch categories with their creators, paginated
        $categories = Category::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('inventory.category.category', [
            'meta_title' => 'Categories - Larkon',
            'meta_desc' => 'Manage your product categories',
            'page_title' => 'Categories',
            'categories' => $categories
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching categories: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error loading categories. Please try again.');
    }
}

    public function createCategory()
    {
        $user = Auth::user();
        return view('inventory.category.create-category', [
            'meta_title' => 'Create Category - Larkon',
            'meta_desc' => 'Create a new product category',
            'page_title' => 'Create Category',
            'creator_name' => $user->business_name,
            'generated_tag_id' => Category::generateTagId()
        ]);
    }

public function storeCategory(Request $request)
{
    // Debug incoming request
    \Log::info('Category creation attempt', [
        'request_data' => $request->all(),
        'files' => $request->hasFile('thumbnail') ? 'Has thumbnail' : 'No thumbnail'
    ]);

    try {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'created_by' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string'
        ]);

        \Log::info('Validation passed', ['validated_data' => $validated]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('categories', 'public');
            $validated['thumbnail'] = $path;
            \Log::info('Thumbnail stored', ['path' => $path]);
        }

        // Generate tag_id
        $validated['tag_id'] = Category::generateTagId();
        \Log::info('Generated tag_id', ['tag_id' => $validated['tag_id']]);

        // Create category with detailed error logging
        try {
            $category = Category::create($validated);
            \Log::info('Category created successfully', ['category_id' => $category->id]);
        } catch (\Exception $e) {
            \Log::error('Database insertion failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        return redirect()->route('category')
            ->with('success', 'Category created successfully!');
    } catch (\Exception $e) {
        \Log::error('Category creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->with('error', 'Failed to create category. Error: ' . $e->getMessage())
            ->withInput();
    }
}

      public function editCategory($id)
    {
        try {
            $category = Category::with('creator')->findOrFail($id);
            
            return view('inventory.category.edit-category', [
                'meta_title' => 'Edit Category - Larkon',
                'meta_desc' => 'Edit product category',
                'page_title' => 'Edit Category',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading category for edit: ' . $e->getMessage());
            return redirect()->route('category')
                ->with('error', 'Category not found.');
        }
    }

    public function updateCategory(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'created_by' => 'required|exists:users,id',
                'description' => 'nullable|string',
                'meta_title' => 'nullable|string|max:255',
                'meta_keywords' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string'
            ]);

            // Handle thumbnail upload if a new one is provided
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail
                if ($category->thumbnail && Storage::disk('public')->exists($category->thumbnail)) {
                    Storage::disk('public')->delete($category->thumbnail);
                }
                
                // Store new thumbnail
                $path = $request->file('thumbnail')->store('categories', 'public');
                $validated['thumbnail'] = $path;
            }

            // Update category
            $category->update($validated);

            return redirect()->route('category')
                ->with('success', 'Category updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Error updating category: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update category. Please try again.')
                ->withInput();
        }
    }


 public function deleteCategory($id)
    {
        try {
            // Find the category
            $category = Category::findOrFail($id);
            
            // Delete the thumbnail file if it exists
            if ($category->thumbnail && Storage::disk('public')->exists($category->thumbnail)) {
                Storage::disk('public')->delete($category->thumbnail);
            }
            
            // Delete the category from database
            $category->delete();

            return redirect()->route('category')
                ->with('success', 'Category deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Error deleting category: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to delete category. Please try again.');
        }
    }


//invoicesz
public function showInvoices(){
    return view('inventory.invoices.invoices ', [
        'meta_title' => 'Invoices - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
          'page_title' => 'Invoices', 
    ]);
}
public function CreateInvoices(){
    return view('inventory.invoices.create-invoices ', [
        'meta_title' => 'Create Invoices - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
          'page_title' => 'Create Invoice', 
    ]);
}
public function EditInvoices(){
    return view('inventory.invoices.edit-invoices ', [
        'meta_title' => 'Edit Invoices - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
          'page_title' => 'Edit Invoice', 
    ]);
}
public function invoicesDetails(){
    return view('inventory.invoices.invoices-details ', [
        'meta_title' => ' Invoices - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
          'page_title' => 'Invoice Detail', 
    ]);
}

//orders

public function showOrders(){
    return view('inventory.orders.orders ', [
        'meta_title' => 'Orders - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
          'page_title' => 'Orders', 
    ]);
}

public function showOrderDetails(){
    return view('inventory.orders.orders-details ', [
        'meta_title' => ' Orders Details - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
         'page_title' => 'Orders Detail', 
    ]);
}

//profile

public function showProfile(){
    return view('inventory.profile ', [
        'meta_title' => ' Profile - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
         'page_title' => 'Profile ', 
    ]);
}
}