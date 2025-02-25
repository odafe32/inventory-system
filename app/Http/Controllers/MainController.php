<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category; 
use App\Models\Order; 
use App\Models\OrderItem; 
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MainController extends Controller
{
    public function boot()
{
    Paginator::defaultView('vendor.pagination.custom');
    Paginator::defaultSimpleView('vendor.pagination.custom');
}
    //
public function showDashboard(){
    // Get count of total orders
    $totalOrders = Order::count();
    
    // Get total revenue (sum of all order totals)
    $totalRevenue = Order::sum('total');
    
    // Calculate revenue growth compared to previous month
    $currentMonth = now()->month;
    $previousMonth = now()->subMonth()->month;
    
    $currentMonthRevenue = Order::whereMonth('created_at', $currentMonth)->sum('total');
    $previousMonthRevenue = Order::whereMonth('created_at', $previousMonth)->sum('total');
    
    $revenueGrowth = 0;
    if ($previousMonthRevenue > 0) {
        $revenueGrowth = (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100;
    }
    
    // Get count of total products
    $totalProducts = Product::count();
    
    // Get count of total categories
    $totalCategories = Category::count();
    
    // Get recent orders
    $recentOrders = Order::with(['orderItems.product', 'createdBy'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    // Format recent orders data
    $recentOrders->transform(function ($order) {
        // Get product image for the first item (if exists)
        $productImage = null;
        if ($order->orderItems->isNotEmpty() && $order->orderItems[0]->product) {
            $productImage = $order->orderItems[0]->product->image;
        }
        
        // Format order data
        $order->formatted_date = $order->created_at->format('d F Y');
        $order->formatted_total = '₦' . number_format($order->total, 2);
        $order->product_image = $productImage;
        $order->status_class = $this->getOrderStatusClass($order->order_status);
        $order->payment_type = $order->payment_status === 'Paid' ? 'Credit Card' : 'Pending';
        
        return $order;
    });
    
    // Get monthly order counts for the performance chart (last 12 months)
    $ordersByMonth = [];
    $revenueByMonth = [];
    
    for ($i = 0; $i < 12; $i++) {
        $date = now()->subMonths($i);
        $monthYear = $date->format('M Y');
        
        $monthlyOrders = Order::whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->count();
            
        $monthlyRevenue = Order::whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->sum('total');
            
        $ordersByMonth[$monthYear] = $monthlyOrders;
        $revenueByMonth[$monthYear] = $monthlyRevenue;
    }
    
    // Reverse arrays to show in chronological order
    $ordersByMonth = array_reverse($ordersByMonth);
    $revenueByMonth = array_reverse($revenueByMonth);
    
    return view('inventory.dashboard', [
        'meta_title' => 'Dashboard - Larkon | Inventory Management Services',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
        'page_title' => 'Dashboard',
        'total_orders' => $totalOrders,
        'total_revenue' => $totalRevenue,
        'revenue_growth' => $revenueGrowth,
        'total_products' => $totalProducts,
        'total_categories' => $totalCategories,
        'recent_orders' => $recentOrders,
        'orders_by_month' => $ordersByMonth,
        'revenue_by_month' => $revenueByMonth,
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
// Add these methods to your MainController

public function showInvoices()
{
    try {
        // Get invoices with their creators, sorted by newest first
        $invoices = Invoice::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('inventory.invoices.invoices', [
            'meta_title' => 'Invoices - Larkon',
            'meta_desc' => 'Manage your invoices',
            'meta_image' => url('images/favicon.ico'),
            'page_title' => 'Invoices',
            'invoices' => $invoices
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching invoices: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error loading invoices. Please try again.');
    }
}

public function CreateInvoices()
{
    // Generate a new invoice number
    $invoiceNumber = Invoice::generateInvoiceNumber();
    
    // Get current user
    $user = Auth::user();
    
    return view('inventory.invoices.create-invoices', [
        'meta_title' => 'Create Invoice - Larkon',
        'meta_desc' => 'Create a new invoice',
        'meta_image' => url('images/favicon.ico'),
        'page_title' => 'Create Invoice',
        'invoice_number' => $invoiceNumber,
        'user' => $user
    ]);
}

public function storeInvoice(Request $request)
{
    try {
        // Validate the request
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_address' => 'required|string',
            'sender_phone' => 'required|string|max:20',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:Paid,Pending,Cancel',
            'issue_from' => 'required|string|max:255',
            'issue_from_address' => 'required|string',
            'issue_from_phone' => 'required|string|max:20',
            'issue_from_email' => 'required|email|max:255',
            'issue_for' => 'required|string|max:255',
            'issue_for_address' => 'required|string',
            'issue_for_phone' => 'required|string|max:20',
            'issue_for_email' => 'required|email|max:255',
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string|max:255',
            'products.*.size' => 'nullable|string|max:255',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.tax' => 'nullable|numeric|min:0',
            'products.*.total' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        \DB::beginTransaction();

        try {
            // Handle logo upload if provided
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('invoices/logos', 'public');
                $validated['logo'] = $logoPath;
            }

            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number,
                'created_by' => auth()->id(),
                'sender_name' => $request->sender_name,
                'sender_address' => $request->sender_address,
                'sender_phone' => $request->sender_phone,
                'issue_from' => $request->issue_from,
                'issue_from_address' => $request->issue_from_address,
                'issue_from_phone' => $request->issue_from_phone,
                'issue_from_email' => $request->issue_from_email,
                'issue_for' => $request->issue_for,
                'issue_for_address' => $request->issue_for_address,
                'issue_for_phone' => $request->issue_for_phone,
                'issue_for_email' => $request->issue_for_email,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'amount' => $request->amount,
                'status' => $request->status,
                'subtotal' => $request->subtotal,
                'discount' => $request->discount ?? 0,
                'tax' => $request->tax ?? 0,
                'grand_total' => $request->grand_total,
                'logo' => $logoPath ?? null,
            ]);

            // Create invoice items
            foreach ($request->products as $product) {
                // Handle product image if provided
                $productImagePath = null;
                if (isset($product['image']) && $product['image']) {
                    $productImagePath = $product['image']->store('invoices/products', 'public');
                }

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_name' => $product['name'],
                    'product_size' => $product['size'] ?? null,
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'tax' => $product['tax'] ?? 0,
                    'total' => $product['total'],
                    'product_image' => $productImagePath,
                ]);
            }

            \DB::commit();

            // Return response based on request type
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice created successfully!',
                    'redirect' => route('invoices-details', $invoice->id)
                ]);
            }

            return redirect()->route('invoices-details', $invoice->id)
                ->with('success', 'Invoice created successfully!');

        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        
        return redirect()->back()->withErrors($e->errors())->withInput();
        
    } catch (\Exception $e) {
        \Log::error('Error creating invoice: ' . $e->getMessage());
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create invoice. ' . $e->getMessage()
            ], 500);
        }
        
        return redirect()->back()
            ->with('error', 'Failed to create invoice. Please try again.')
            ->withInput();
    }
}

public function invoicesDetails($id)
{
    try {
        $invoice = Invoice::with(['items', 'creator'])->findOrFail($id);
        
        return view('inventory.invoices.invoices-details', [
            'meta_title' => 'Invoice Details - Larkon',
            'meta_desc' => 'View invoice details',
            'meta_image' => url('images/favicon.ico'),
            'page_title' => 'Invoice Detail',
            'invoice' => $invoice
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching invoice details: ' . $e->getMessage());
        return redirect()->route('invoices')
            ->with('error', 'Invoice not found.');
    }
}

public function EditInvoices($id)
{
    try {
        $invoice = Invoice::with(['items', 'creator'])->findOrFail($id);
        
        return view('inventory.invoices.edit-invoices', [
            'meta_title' => 'Edit Invoice - Larkon',
            'meta_desc' => 'Edit invoice details',
            'meta_image' => url('images/favicon.ico'),
            'page_title' => 'Edit Invoice',
            'invoice' => $invoice
        ]);
    } catch (\Exception $e) {
        \Log::error('Error loading invoice for edit: ' . $e->getMessage());
        return redirect()->route('invoices')
            ->with('error', 'Invoice not found.');
    }
}

public function deleteInvoice($id)
{
    try {
        $invoice = Invoice::findOrFail($id);
        
        // Delete logo if exists
        if ($invoice->logo && Storage::disk('public')->exists($invoice->logo)) {
            Storage::disk('public')->delete($invoice->logo);
        }
        
        // Delete product images if exist
        foreach ($invoice->items as $item) {
            if ($item->product_image && Storage::disk('public')->exists($item->product_image)) {
                Storage::disk('public')->delete($item->product_image);
            }
        }
        
        // Delete invoice and its items (cascade deletion will handle the items)
        $invoice->delete();
        
        return redirect()->route('invoices')
            ->with('success', 'Invoice deleted successfully!');
    } catch (\Exception $e) {
        \Log::error('Error deleting invoice: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Failed to delete invoice. Please try again.');
    }
}

public function updateInvoice(Request $request, $id)
{
    try {
        // Find the invoice
        $invoice = Invoice::with('items')->findOrFail($id);
        
        // Validate the request
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_address' => 'required|string',
            'sender_phone' => 'required|string|max:20',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:Paid,Pending,Cancel',
            'issue_from' => 'required|string|max:255',
            'issue_from_address' => 'required|string',
            'issue_from_phone' => 'required|string|max:20',
            'issue_from_email' => 'required|email|max:255',
            'issue_for' => 'required|string|max:255',
            'issue_for_address' => 'required|string',
            'issue_for_phone' => 'required|string|max:20',
            'issue_for_email' => 'required|email|max:255',
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string|max:255',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.tax' => 'nullable|numeric|min:0',
            'products.*.total' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        \DB::beginTransaction();

        try {
            // Handle logo upload if provided
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($invoice->logo && Storage::disk('public')->exists($invoice->logo)) {
                    Storage::disk('public')->delete($invoice->logo);
                }
                
                $logoPath = $request->file('logo')->store('invoices/logos', 'public');
                $validated['logo'] = $logoPath;
            }

            // Update invoice
            $invoice->update([
                'sender_name' => $request->sender_name,
                'sender_address' => $request->sender_address,
                'sender_phone' => $request->sender_phone,
                'issue_from' => $request->issue_from,
                'issue_from_address' => $request->issue_from_address,
                'issue_from_phone' => $request->issue_from_phone,
                'issue_from_email' => $request->issue_from_email,
                'issue_for' => $request->issue_for,
                'issue_for_address' => $request->issue_for_address,
                'issue_for_phone' => $request->issue_for_phone,
                'issue_for_email' => $request->issue_for_email,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'amount' => $request->amount,
                'status' => $request->status,
                'subtotal' => $request->subtotal,
                'discount' => $request->discount ?? 0,
                'tax' => $request->tax ?? 0,
                'grand_total' => $request->grand_total,
                'logo' => isset($logoPath) ? $logoPath : $invoice->logo,
            ]);

            // Get IDs of current items to track what should be deleted
            $existingItemIds = $invoice->items->pluck('id')->toArray();
            $updatedItemIds = [];
            
            // Update or create invoice items
            foreach ($request->products as $product) {
                if (isset($product['id'])) {
                    // Update existing item
                    $item = InvoiceItem::find($product['id']);
                    if ($item) {
                        $item->update([
                            'product_name' => $product['name'],
                            'product_size' => $product['size'] ?? null,
                            'quantity' => $product['quantity'],
                            'price' => $product['price'],
                            'tax' => $product['tax'] ?? 0,
                            'total' => $product['total'],
                        ]);
                        $updatedItemIds[] = $item->id;
                    }
                } else {
                    // Create new item
                    $newItem = InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_name' => $product['name'],
                        'product_size' => $product['size'] ?? null,
                        'quantity' => $product['quantity'],
                        'price' => $product['price'],
                        'tax' => $product['tax'] ?? 0,
                        'total' => $product['total'],
                    ]);
                    $updatedItemIds[] = $newItem->id;
                }
            }
            
            // Delete items that weren't updated or are no longer needed
            $itemsToDelete = array_diff($existingItemIds, $updatedItemIds);
            if (!empty($itemsToDelete)) {
                InvoiceItem::whereIn('id', $itemsToDelete)->delete();
            }

            \DB::commit();

            return redirect()->route('invoices-details', $invoice->id)
                ->with('success', 'Invoice updated successfully!');

        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }

    } catch (\Exception $e) {
        \Log::error('Error updating invoice: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Failed to update invoice: ' . $e->getMessage())
            ->withInput();
    }
}


//orders

public function showOrders()
{
  // Get orders with relationships, newest first
    $orders = Order::with(['orderItems', 'createdBy'])
        ->orderBy('created_at', 'desc')
        ->paginate(10); // T
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
                'created_by' => auth()->id() // Add the user who created the order
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
            }

            \DB::commit();

            // Return response based on request type
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully! Order ID: ' . $order->order_id,
                    'redirect' => route('orders-list')  // Changed from 'ord' to 'orders-list'
                ]);
            }

            return redirect()->route('orders-list')  // Changed from 'ord' to 'orders-list'
                ->with('success', 'Order created successfully! Order ID: ' . $order->order_id);

        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        
        return redirect()->back()->withErrors($e->errors())->withInput();
        
    } catch (\Exception $e) {
        \Log::error('Error creating order: ' . $e->getMessage());
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order. ' . $e->getMessage()
            ], 500);
        }
        
        return redirect()->back()
            ->with('error', 'Failed to create order. Please try again.')
            ->withInput();
    }
}


public function showOrderDetails($id)
{
    try {
        // Get order with related items and creator
        $order = Order::with(['orderItems.product', 'createdBy'])
            ->findOrFail($id);

        // Format the data for display
        $order->formatted_total = '₦' . number_format($order->total, 2);
        $order->formatted_subtotal = '₦' . number_format($order->subtotal, 2);
        $order->formatted_tax = '₦' . number_format($order->tax, 2);
        $order->formatted_date = $order->created_at->format('M d, Y');
        $order->payment_status_class = $this->getPaymentStatusClass($order->payment_status);
        $order->order_status_class = $this->getOrderStatusClass($order->order_status);

        return view('inventory.orders.orders-details', [
            'meta_title' => 'Order Details - Larkon',
            'meta_desc' => 'A Management system that helps businesses keep track of their products...',
            'meta_image' => url('images/favicon.ico'),
            'page_title' => 'Order Details',
            'order' => $order
        ]);

    } catch (\Exception $e) {
        \Log::error('Error fetching order details: ' . $e->getMessage());
        return redirect()->route('ord')
            ->with('error', 'Order not found.');
    }
}


public function editOrder($id)
{
    try {
        $order = Order::with(['orderItems.product'])->findOrFail($id);
        $products = Product::all(); // Get all products for the dropdown

        return view('inventory.orders.edit-order', [
            'meta_title' => 'Edit Order - Larkon',
            'meta_desc' => 'Edit order details and items',
            'meta_image' => url('images/favicon.ico'),
            'page_title' => 'Edit Order',
            'order' => $order,
            'products' => $products
        ]);
    } catch (\Exception $e) {
        \Log::error('Error loading order for edit: ' . $e->getMessage());
        return redirect()->route('orders-list')
            ->with('error', 'Order not found.');
    }
}
// In MainController.php

public function updateOrder(Request $request, $id)
{
    try {
        // Find the order or fail
        $order = Order::findOrFail($id);
        
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

            // Update order
            $order->update([
                'customer_name' => $request->customer_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'priority' => $request->priority,
                'payment_status' => $request->payment_status,
                'order_status' => $request->order_status,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total
            ]);

            // Delete existing order items
            $order->orderItems()->delete();

            // Create new order items
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
            }

            \DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order updated successfully!',
                    'redirect' => route('orders-details', $order->id)
                ]);
            }

            return redirect()->route('orders-details', $order->id)
                ->with('success', 'Order updated successfully!');

        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }
        
        return redirect()->back()->withErrors($e->errors())->withInput();
        
    } catch (\Exception $e) {
        \Log::error('Error updating order: ' . $e->getMessage());
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage()
            ], 500);
        }
        
        return redirect()->back()
            ->with('error', 'Failed to update order. Please try again.')
            ->withInput();
    }
}
public function deleteOrder($id)
{
    try {
        $order = Order::findOrFail($id);
        
        \DB::beginTransaction();
        
        // Delete order items first
        $order->orderItems()->delete();
        
        // Delete the order
        $order->delete();
        
        \DB::commit();
        
        return redirect()->route('orders-list')
            ->with('success', 'Order deleted successfully!');
                
    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('Error deleting order: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Failed to delete order. Please try again.');
    }
}
//profile

public function showProfile()
{
    $user = Auth::user();
    
    return view('inventory.profile', [
        'meta_title' => 'Profile - Larkon',
        'meta_desc' => 'Manage your profile and business information',
        'meta_image' => url('images/favicon.ico'),
        'page_title' => 'Profile',
        'user' => $user
    ]);
}

public function updateProfile(Request $request)
{
    try {
        $user = Auth::user();
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'business_address' => 'required|string',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Handle profile image upload if provided
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            $validated['profile_image'] = $imagePath;
        }
        
        // Update user
        $user->update($validated);
        
        return redirect()->route('profile')
            ->with('success', 'Profile updated successfully!');
            
    } catch (\Exception $e) {
        \Log::error('Error updating profile: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Failed to update profile. Please try again.')
            ->withInput();
    }
}

public function changePassword(Request $request)
{
    try {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Current password is incorrect.')
                ->withInput();
        }
        
        // Update password
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('profile')
            ->with('success', 'Password changed successfully!');
            
    } catch (\Exception $e) {
        \Log::error('Error changing password: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Failed to change password. Please try again.');
    }
}
}