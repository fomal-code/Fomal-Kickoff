<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $categories = Category::all();
        $tags = Tag::all();

        for ($i = 1; $i <= 20; $i++) {
            $title = "Sample Blog Post {$i}";
            $content = "This is the content for blog post {$i}. " . str_repeat("Lorem ipsum dolor sit amet, consectetur adipiscing elit. ", 50);
            
            $post = Post::create([
                'user_id' => $users->random()->id,
                'title' => $title,
                'slug' => Str::slug($title),
                'excerpt' => "This is an excerpt for post {$i}",
                'content' => $content,
                'status' => ['draft', 'published', 'published', 'published'][rand(0, 3)], // More published posts
                'published_at' => now()->subDays(rand(1, 30)),
                'reading_time' => rand(3, 10),
                'meta_description' => "Meta description for post {$i}",
                'is_featured' => rand(0, 5) === 0, // 1 in 6 chance of being featured
                'allow_comments' => true,
                'views_count' => rand(0, 1000),
            ]);

            // Attach random categories (1-3)
            $post->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );

            // Attach random tags (2-5)
        }
    }
}