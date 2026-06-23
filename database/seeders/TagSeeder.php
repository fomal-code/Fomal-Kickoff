<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run()
    {
        $tags = ['Laravel', 'PHP', 'JavaScript', 'Vue.js', 'React', 'CSS', 'HTML', 'MySQL', 'Tutorial', 'Guide'];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'slug' => Str::slug($tag),
            ]);
        }
    }
}