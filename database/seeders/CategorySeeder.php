<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Technology', 'description' => 'Tech news and tutorials'],
            ['name' => 'Travel', 'description' => 'Travel guides and stories'],
            ['name' => 'Food', 'description' => 'Recipes and food reviews'],
            ['name' => 'Lifestyle', 'description' => 'Lifestyle tips and advice'],
            ['name' => 'Business', 'description' => 'Business news and insights'],
        ];

        foreach ($categories as $index => $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}