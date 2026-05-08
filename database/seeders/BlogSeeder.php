<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = Admin::first()?->id ?? 1;

        $categories = [
            ['en' => 'Plant Care', 'bn' => 'গাছের যত্ন'],
            ['en' => 'Gardening Tips', 'bn' => 'বাগান টিপস'],
            ['en' => 'Plant Guide', 'bn' => 'গাছের গাইড'],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $slug = Str::slug($cat['en']);
            $blogCat = BlogCategory::firstOrCreate(['slug' => $slug]);
            $blogCat->setTranslation('en', ['name' => $cat['en']]);
            $blogCat->setTranslation('bn', ['name' => $cat['bn']]);
            $createdCategories[] = $blogCat->fresh();
        }

        $posts = [
            [
                'en' => [
                    'title' => 'How to Care for Indoor Plants in Bangladesh',
                    'excerpt' => 'Learn the best practices for keeping your indoor plants healthy in the humid Bangladeshi climate.',
                    'content' => '<p>Indoor plants can thrive in Bangladesh with the right care. Here are some essential tips to keep your plants healthy throughout the year.</p><h2>Watering</h2><p>Most indoor plants prefer to dry out slightly between waterings. Check the soil — if the top inch is dry, it is time to water.</p><h2>Light</h2><p>Place your plants near windows that receive bright indirect light.</p>',
                ],
                'bn' => [
                    'title' => 'বাংলাদেশে ইনডোর গাছের যত্ন কীভাবে নেবেন',
                    'excerpt' => 'বাংলাদেশের আর্দ্র আবহাওয়ায় আপনার ইনডোর গাছকে সুস্থ রাখার সেরা পদ্ধতি জানুন।',
                    'content' => '<p>সঠিক যত্নের মাধ্যমে বাংলাদেশে ইনডোর গাছ সুন্দরভাবে বেড়ে উঠতে পারে।</p>',
                ],
                'category_index' => 0,
            ],
            [
                'en' => [
                    'title' => 'Top 10 Low-Maintenance Plants for Beginners',
                    'excerpt' => 'Starting your plant journey? These 10 plants are perfect for beginners who want beautiful greenery without the hassle.',
                    'content' => '<p>Whether you have a busy lifestyle or are new to gardening, these plants will thrive with minimal attention.</p><h2>1. Pothos (Money Plant)</h2><p>Extremely forgiving and grows in almost any condition.</p><h2>2. Snake Plant</h2><p>Survives in low light and requires infrequent watering.</p>',
                ],
                'bn' => [
                    'title' => 'শিক্ষানবিসদের জন্য সেরা ১০টি সহজ গাছ',
                    'excerpt' => 'গাছপালার সাথে আপনার যাত্রা শুরু করতে চান? এই ১০টি গাছ কম পরিশ্রমে সুন্দর সবুজ পরিবেশ তৈরি করবে।',
                    'content' => '<p>ব্যস্ত জীবনযাত্রায় বা বাগান করার নতুন অভিজ্ঞতায় এই গাছগুলো আপনার সেরা সঙ্গী।</p>',
                ],
                'category_index' => 2,
            ],
            [
                'en' => [
                    'title' => 'Rooftop Gardening Guide for Dhaka',
                    'excerpt' => 'Transform your rooftop into a green paradise with these practical tips tailored for Dhaka urban gardeners.',
                    'content' => '<p>Rooftop gardening is gaining popularity in Dhaka as urban residents seek to connect with nature.</p><h2>Choosing Containers</h2><p>Use lightweight plastic or fabric grow bags to reduce structural load.</p>',
                ],
                'bn' => [
                    'title' => 'ঢাকার ছাদ বাগানের সম্পূর্ণ গাইড',
                    'excerpt' => 'ঢাকার শহুরে বাগানপ্রেমীদের জন্য ছাদকে সবুজ স্বর্গে পরিণত করার ব্যবহারিক টিপস।',
                    'content' => '<p>ঢাকায় ছাদ বাগান ক্রমশ জনপ্রিয় হয়ে উঠছে।</p>',
                ],
                'category_index' => 1,
            ],
        ];

        foreach ($posts as $post) {
            $slug = Str::slug($post['en']['title']);
            $blog = Blog::firstOrCreate(['slug' => $slug], [
                'blog_category_id' => $createdCategories[$post['category_index']]->id,
                'author_id' => $adminId,
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 30)),
            ]);
            $blog->setTranslation('en', $post['en']);
            $blog->setTranslation('bn', $post['bn']);
        }
    }
}
