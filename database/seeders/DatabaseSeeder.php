<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // users (password for all = "password")
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $users = User::factory(9)->create()->push($testUser);

        // categories
        $categories = collect(['Technology', 'Travel', 'Food', 'Lifestyle', 'Programming', 'Health'])
            ->map(fn ($name) => Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]));

        // tags
        $tags = collect(['laravel', 'react-native', 'php', 'javascript', 'tips', 'beginner', 'tutorial', 'news'])
            ->map(fn ($name) => Tag::create(['name' => $name]));

        // posts (each with 1-3 tags)
        $posts = collect();

        foreach (range(1, 30) as $i) {
            $isPublished = fake()->boolean(75);

            $post = Post::create([
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'title' => rtrim(fake()->sentence(6), '.'),
                'content' => implode("\n\n", fake()->paragraphs(4)),
                'cover_image' => null,
                'status' => $isPublished ? 'published' : 'draft',
                'published_at' => $isPublished ? fake()->dateTimeBetween('-2 months') : null,
            ]);

            $post->tags()->sync($tags->random(rand(1, 3))->pluck('id'));

            $posts->push($post);
        }

        // comments, likes, bookmarks
        // (comments has a unique user_id + post_id, so pick distinct users per post)
        $comments = [];
        $likes = [];
        $bookmarks = [];

        foreach ($posts as $post) {
            foreach ($users->random(rand(0, 4)) as $user) {
                $comments[] = [
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                    'content' => fake()->sentence(12),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            foreach ($users->random(rand(0, 6)) as $user) {
                $likes[] = ['user_id' => $user->id, 'post_id' => $post->id, 'created_at' => now(), 'updated_at' => now()];
            }

            foreach ($users->random(rand(0, 3)) as $user) {
                $bookmarks[] = ['user_id' => $user->id, 'post_id' => $post->id, 'created_at' => now(), 'updated_at' => now()];
            }
        }

        DB::table('comments')->insert($comments);
        DB::table('likes')->insert($likes);
        DB::table('bookmarks')->insert($bookmarks);

        // follows (a user can't follow themselves)
        $follows = [];

        foreach ($users as $follower) {
            $others = $users->where('id', '!=', $follower->id);

            foreach ($others->random(rand(1, 4)) as $following) {
                $follows[] = [
                    'follower_id' => $follower->id,
                    'following_id' => $following->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('follows')->insert($follows);
    }
}
