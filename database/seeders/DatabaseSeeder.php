<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create test user without using factory defaults
        $user = User::create([
            'first_name' => 'Joseph ',
            'last_name' => 'Sule Godfrey',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'business_name' => 'Fashion Store',
            'business_type' => 'Retail',
            'business_address' => '123 Fashion Street',
            'username' => 'odafe32',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Create fashion categories
        $categories = [
            [
                'title' => 'Clothing',
                'thumbnail' => '/images/categories/clothing.jpg',
                'created_by' => $user->id,
                'tag_id' => Str::slug('clothing'),
                'description' => 'Trendy and comfortable clothing for all occasions',
                'meta_title' => 'Fashion Clothing Collection',
                'meta_keywords' => 'clothes, fashion, apparel, dresses, shirts',
                'meta_description' => 'Discover our latest clothing collection featuring trendy and comfortable pieces'
            ],
            [
                'title' => 'Jewelry',
                'thumbnail' => '/images/categories/jewelry.jpg',
                'created_by' => $user->id,
                'tag_id' => Str::slug('jewelry'),
                'description' => 'Elegant jewelry pieces and accessories',
                'meta_title' => 'Fashion Jewelry Collection',
                'meta_keywords' => 'jewelry, accessories, necklaces, bracelets, rings',
                'meta_description' => 'Browse our stunning collection of fashion jewelry and accessories'
            ],
            [
                'title' => 'Shoes',
                'thumbnail' => '/images/categories/shoes.jpg',
                'created_by' => $user->id,
                'tag_id' => Str::slug('shoes'),
                'description' => 'Stylish footwear for every occasion',
                'meta_title' => 'Trendy Shoe Collection',
                'meta_keywords' => 'shoes, footwear, sneakers, heels, boots',
                'meta_description' => 'Step out in style with our fashionable shoe collection'
            ],
            [
                'title' => 'Shorts',
                'thumbnail' => '/images/categories/shorts.jpg',
                'created_by' => $user->id,
                'tag_id' => Str::slug('shorts'),
                'description' => 'Comfortable and stylish shorts collection',
                'meta_title' => 'Fashion Shorts Collection',
                'meta_keywords' => 'shorts, summer wear, casual wear, beach wear',
                'meta_description' => 'Stay cool and stylish with our shorts collection'
            ],
            [
                'title' => 'Caps & Hats',
                'thumbnail' => '/images/categories/caps.jpg',
                'created_by' => $user->id,
                'tag_id' => Str::slug('caps-and-hats'),
                'description' => 'Trendy caps and hats for all seasons',
                'meta_title' => 'Fashion Caps & Hats Collection',
                'meta_keywords' => 'caps, hats, headwear, beanies, fashion accessories',
                'meta_description' => 'Complete your look with our stylish collection of caps and hats'
            ],
            [
                'title' => 'Accessories',
                'thumbnail' => '/images/categories/accessories.jpg',
                'created_by' => $user->id,
                'tag_id' => Str::slug('accessories'),
                'description' => 'Fashion accessories to complement your style',
                'meta_title' => 'Fashion Accessories Collection',
                'meta_keywords' => 'accessories, belts, scarves, sunglasses, bags',
                'meta_description' => 'Enhance your outfit with our trendy fashion accessories'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
        
        // Call the Product Seeder
        $this->call([
            ProductSeeder::class,
              
            
        ]);
    }
}