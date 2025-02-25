<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get all categories
        $categories = Category::all();

        // Products for Clothing category
        $clothingCategory = $categories->where('title', 'Clothing')->first();
        if ($clothingCategory) {
            $clothingProducts = [
                [
                    'tag_number' => 'CLT-' . Str::random(6),
                    'name' => 'Summer Cotton T-Shirt',
                    'category_id' => $clothingCategory->id,
                    'brand' => 'FashionBrand',
                    'weight' => 0.25,
                    'gender' => 'Unisex',
                    'description' => 'Comfortable cotton t-shirt perfect for summer days. Made with premium fabric that allows your skin to breathe.',
                    'stock' => 50,
                    'size' => 'M',
                    'price' => 29.99,
                    'discount' => 5.00,
                    'tax' => 7.50,
                    'image' => '/images/products/tshirt.jpg',
                ],
                [
                    'tag_number' => 'CLT-' . Str::random(6),
                    'name' => 'Casual Denim Jacket',
                    'category_id' => $clothingCategory->id,
                    'brand' => 'DenimCo',
                    'weight' => 0.85,
                    'gender' => 'Unisex',
                    'description' => 'Classic denim jacket that never goes out of style. Pair it with anything for an effortlessly cool look.',
                    'stock' => 30,
                    'size' => 'L',
                    'price' => 89.99,
                    'discount' => 0.00,
                    'tax' => 7.50,
                    'image' => '/images/products/denim_jacket.jpg',
                ],
                [
                    'tag_number' => 'CLT-' . Str::random(6),
                    'name' => 'Floral Summer Dress',
                    'category_id' => $clothingCategory->id,
                    'brand' => 'Florals',
                    'weight' => 0.45,
                    'gender' => 'Female',
                    'description' => 'Beautiful floral pattern dress perfect for summer outings and casual events.',
                    'stock' => 25,
                    'size' => 'S',
                    'price' => 59.99,
                    'discount' => 10.00,
                    'tax' => 7.50,
                    'image' => '/images/products/floral_dress.jpg',
                ],
            ];

            foreach ($clothingProducts as $product) {
                Product::create($product);
            }
        }

        // Products for Jewelry category
        $jewelryCategory = $categories->where('title', 'Jewelry')->first();
        if ($jewelryCategory) {
            $jewelryProducts = [
                [
                    'tag_number' => 'JWL-' . Str::random(6),
                    'name' => 'Gold Plated Necklace',
                    'category_id' => $jewelryCategory->id,
                    'brand' => 'GoldLux',
                    'weight' => 0.05,
                    'gender' => 'Female',
                    'description' => 'Elegant gold plated necklace with a minimalist pendant design. Perfect for everyday wear or special occasions.',
                    'stock' => 20,
                    'size' => null,
                    'price' => 49.99,
                    'discount' => 0.00,
                    'tax' => 7.50,
                    'image' => '/images/products/gold_necklace.jpg',
                ],
                [
                    'tag_number' => 'JWL-' . Str::random(6),
                    'name' => 'Silver Hoop Earrings',
                    'category_id' => $jewelryCategory->id,
                    'brand' => 'SilverCraft',
                    'weight' => 0.02,
                    'gender' => 'Female',
                    'description' => 'Classic silver hoop earrings that add a touch of elegance to any outfit.',
                    'stock' => 35,
                    'size' => null,
                    'price' => 24.99,
                    'discount' => 0.00,
                    'tax' => 7.50,
                    'image' => '/images/products/hoop_earrings.jpg',
                ],
            ];

            foreach ($jewelryProducts as $product) {
                Product::create($product);
            }
        }

        // Products for Shoes category
        $shoesCategory = $categories->where('title', 'Shoes')->first();
        if ($shoesCategory) {
            $shoesProducts = [
                [
                    'tag_number' => 'SHO-' . Str::random(6),
                    'name' => 'Classic White Sneakers',
                    'category_id' => $shoesCategory->id,
                    'brand' => 'UrbanStep',
                    'weight' => 0.75,
                    'gender' => 'Unisex',
                    'description' => 'Timeless white sneakers that pair perfectly with any casual outfit. Comfortable for all-day wear.',
                    'stock' => 40,
                    'size' => '42',
                    'price' => 79.99,
                    'discount' => 0.00,
                    'tax' => 7.50,
                    'image' => '/images/products/white_sneakers.jpg',
                ],
                [
                    'tag_number' => 'SHO-' . Str::random(6),
                    'name' => 'Leather Ankle Boots',
                    'category_id' => $shoesCategory->id,
                    'brand' => 'BootMaster',
                    'weight' => 1.20,
                    'gender' => 'Female',
                    'description' => 'Stylish leather ankle boots with a comfortable heel, perfect for both casual and smart outfits.',
                    'stock' => 15,
                    'size' => '38',
                    'price' => 119.99,
                    'discount' => 15.00,
                    'tax' => 7.50,
                    'image' => '/images/products/ankle_boots.jpg',
                ],
            ];

            foreach ($shoesProducts as $product) {
                Product::create($product);
            }
        }

        // Products for Shorts category
        $shortsCategory = $categories->where('title', 'Shorts')->first();
        if ($shortsCategory) {
            $shortsProducts = [
                [
                    'tag_number' => 'SHT-' . Str::random(6),
                    'name' => 'Denim Shorts',
                    'category_id' => $shortsCategory->id,
                    'brand' => 'DenimCo',
                    'weight' => 0.35,
                    'gender' => 'Female',
                    'description' => 'Classic denim shorts with a slight distressed finish, perfect for warm weather.',
                    'stock' => 45,
                    'size' => 'M',
                    'price' => 39.99,
                    'discount' => 5.00,
                    'tax' => 7.50,
                    'image' => '/images/products/denim_shorts.jpg',
                ],
                [
                    'tag_number' => 'SHT-' . Str::random(6),
                    'name' => 'Athletic Shorts',
                    'category_id' => $shortsCategory->id,
                    'brand' => 'SportFit',
                    'weight' => 0.20,
                    'gender' => 'Male',
                    'description' => 'Lightweight athletic shorts with quick-dry technology, ideal for workouts and casual wear.',
                    'stock' => 50,
                    'size' => 'L',
                    'price' => 34.99,
                    'discount' => 0.00,
                    'tax' => 7.50,
                    'image' => '/images/products/athletic_shorts.jpg',
                ],
            ];

            foreach ($shortsProducts as $product) {
                Product::create($product);
            }
        }

        // Products for Caps & Hats category
        $capsCategory = $categories->where('title', 'Caps & Hats')->first();
        if ($capsCategory) {
            $capsProducts = [
                [
                    'tag_number' => 'CAP-' . Str::random(6),
                    'name' => 'Baseball Cap',
                    'category_id' => $capsCategory->id,
                    'brand' => 'CapCo',
                    'weight' => 0.15,
                    'gender' => 'Unisex',
                    'description' => 'Classic baseball cap with embroidered logo. Adjustable strap for perfect fit.',
                    'stock' => 60,
                    'size' => 'One Size',
                    'price' => 24.99,
                    'discount' => 0.00,
                    'tax' => 7.50,
                    'image' => '/images/products/baseball_cap.jpg',
                ],
                [
                    'tag_number' => 'CAP-' . Str::random(6),
                    'name' => 'Straw Sun Hat',
                    'category_id' => $capsCategory->id,
                    'brand' => 'SummerShade',
                    'weight' => 0.25,
                    'gender' => 'Female',
                    'description' => 'Wide-brim straw sun hat, perfect for beach days and outdoor events. Provides excellent sun protection.',
                    'stock' => 25,
                    'size' => 'One Size',
                    'price' => 29.99,
                    'discount' => 10.00,
                    'tax' => 7.50,
                    'image' => '/images/products/straw_hat.jpg',
                ],
            ];

            foreach ($capsProducts as $product) {
                Product::create($product);
            }
        }

        // Products for Accessories category
        $accessoriesCategory = $categories->where('title', 'Accessories')->first();
        if ($accessoriesCategory) {
            $accessoriesProducts = [
                [
                    'tag_number' => 'ACC-' . Str::random(6),
                    'name' => 'Leather Belt',
                    'category_id' => $accessoriesCategory->id,
                    'brand' => 'LeatherCraft',
                    'weight' => 0.30,
                    'gender' => 'Male',
                    'description' => 'Premium leather belt with classic buckle. A versatile addition to any wardrobe.',
                    'stock' => 40,
                    'size' => '34',
                    'price' => 44.99,
                    'discount' => 0.00,
                    'tax' => 7.50,
                    'image' => '/images/products/leather_belt.jpg',
                ],
                [
                    'tag_number' => 'ACC-' . Str::random(6),
                    'name' => 'Silk Scarf',
                    'category_id' => $accessoriesCategory->id,
                    'brand' => 'SilkLuxury',
                    'weight' => 0.10,
                    'gender' => 'Female',
                    'description' => 'Elegant silk scarf with a vibrant pattern. Adds a touch of sophistication to any outfit.',
                    'stock' => 30,
                    'size' => null,
                    'price' => 39.99,
                    'discount' => 5.00,
                    'tax' => 7.50,
                    'image' => '/images/products/silk_scarf.jpg',
                ],
                [
                    'tag_number' => 'ACC-' . Str::random(6),
                    'name' => 'Aviator Sunglasses',
                    'category_id' => $accessoriesCategory->id,
                    'brand' => 'SunStyle',
                    'weight' => 0.08,
                    'gender' => 'Unisex',
                    'description' => 'Classic aviator sunglasses with UV protection. Timeless style that suits any face shape.',
                    'stock' => 35,
                    'size' => null,
                    'price' => 69.99,
                    'discount' => 10.00,
                    'tax' => 7.50,
                    'image' => '/images/products/aviator_sunglasses.jpg',
                ],
            ];

            foreach ($accessoriesProducts as $product) {
                Product::create($product);
            }
        }
    }
}