<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category; 
use App\Models\Order; 
use App\Models\OrderItem; 

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

public function showOrders()
{
    // Get orders with relationships, newest first
    $orders = Order::with(['orderItems', 'createdBy'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Format orders for display
    $orders->getCollection()->transform(function ($order) {
        // Get total items count
        $totalItems = $order->orderItems->sum('quantity');
        
        // Format amounts with Naira symbol
        $formattedTotal = '₦' . number_format($order->total, 2);
        
        // Get status classes
        $paymentStatusClass = $this->getPaymentStatusClass($order->payment_status);
        $orderStatusClass = $this->getOrderStatusClass($order->order_status);
        
        // Add formatted data to order object
        $order->formatted_total = $formattedTotal;
        $order->total_items = $totalItems;
        $order->payment_status_class = $paymentStatusClass;
        $order->order_status_class = $orderStatusClass;
        $order->formatted_date = $order->created_at->format('M d, Y');
        
        return $order;
    });

    return view('inventory.orders.orders', [
        'meta_title' => 'Orders - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
        'page_title' => 'Orders',
        'orders' => $orders
    ]);
}

private function getPaymentStatusClass($status)
{
    return match ($status) {
        'Paid' => 'bg-success text-light',
        'Unpaid' => 'bg-light text-dark',
        'Refund' => 'bg-light text-dark',
        default => 'bg-light text-dark'
    };
}

private function getOrderStatusClass($status)
{
    return match ($status) {
        'Draft' => 'border border-secondary text-secondary',
        'Packaging' => 'border border-warning text-warning',
        'Shipping' => 'border border-info text-info',
        'Completed' => 'border border-success text-success',
        'Canceled' => 'border border-danger text-danger',
        default => 'border border-secondary text-secondary'
    };
}
// In MainController.php
public function CreateOrders()
{
    try {
        // Get all products for the dropdown
        $products = Product::all();
        
        return view('inventory.orders.create-orders', [
            'meta_title' => 'Create Order - Larkon',
            'meta_desc' => 'Create a new order in the system',
            'meta_image' => url('images/favicon.ico'),
            'page_title' => 'Create Order',
            'products' => $products
        ]);
    } catch (\Exception $e) {
        \Log::error('Error loading create order page: ' . $e->getMessage());
        return redirect()->route('orders')
            ->with('error', 'Failed to load create order page. Please try again.');
    }
}

public function storeOrder(Request $request)
{
    try {
        // Validate the request
        $validated = $request->validate([
            'priority' => 'required|in:Normal,High',
            'payment_status' => 'required|in:Paid,Unpaid,Refund',
            'order_status' => 'required|in:Draft,Packaging,Shipping,Completed,Canceled',
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'required|string',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1'
        ]);

        \DB::beginTransaction();

        try {
            // Calculate totals
            $subtotal = 0;
            foreach ($request->products as $index => $productId) {
                $product = Product::findOrFail($productId);
                $quantity = $request->quantities[$index];
                $subtotal += $product->price * $quantity;
            }

            $tax = $subtotal * 0.075; // 7.5% tax
            $total = $subtotal + $tax;

            // Generate unique order ID
            $orderId = '#' . date('ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            while (Order::where('order_id', $orderId)->exists()) {
                $orderId = '#' . date('ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }

            // Create order
            $order = Order::create([
                'order_id' => $orderId,
                'customer_name' => $request->customer_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'priority' => $request->priority,
                'payment_status' => $request->payment_status,
                'order_status' => $request->order_status,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'created_by' => auth()->id()
            ]);

            // Create order items
            foreach ($request->products as $index => $productId) {
                $product = Product::findOrFail($productId);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'size' => $request->sizes[$index],
                    'quantity' => $request->quantities[$index],
                    'price' => $product->price,
                    'total' => $product->price * $request->quantities[$index]
                ]);

                // Uncomment if you want to update product stock
                // $product->decrement('stock', $request->quantities[$index]);
            }

            \DB::commit();

            // Send order confirmation email
            try {
                // Mail::to($request->email)->send(new OrderConfirmation($order));
            } catch (\Exception $e) {
                \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
                // Don't throw the error since order was created successfully
            }

            // Return response based on request type
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully! Order ID: ' . $order->order_id,
                    'order' => $order,
                    'redirect' => route('orders')
                ]);
            }

            return redirect()->route('orders')
                ->with('success', 'Order created successfully! Order ID: ' . $order->order_id);

        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        return back()->withErrors($e->errors())->withInput();

    } catch (\Exception $e) {
        \Log::error('Error creating order: ' . $e->getMessage());
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }

        return back()
            ->with('error', 'Failed to create order. Please try again.')
            ->withInput();
    }
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