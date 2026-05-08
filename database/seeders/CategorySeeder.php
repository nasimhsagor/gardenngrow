<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    private array $categories = [
        ['en' => 'Indoor Plants', 'bn' => 'ইনডোর গাছ', 'icon' => '🪴', 'children' => [
            ['en' => 'Foliage Plants', 'bn' => 'পাতাবাহার'],
            ['en' => 'Succulents & Cacti', 'bn' => 'সাকুলেন্ট ও ক্যাকটাস'],
            ['en' => 'Air Purifying Plants', 'bn' => 'বায়ু বিশুদ্ধকারী গাছ'],
        ]],
        ['en' => 'Outdoor Plants', 'bn' => 'আউটডোর গাছ', 'icon' => '🌳', 'children' => [
            ['en' => 'Fruit Trees', 'bn' => 'ফলের গাছ'],
            ['en' => 'Shade Trees', 'bn' => 'ছায়া গাছ'],
            ['en' => 'Hedge Plants', 'bn' => 'হেজ গাছ'],
        ]],
        ['en' => 'Flower Plants', 'bn' => 'ফুলের গাছ', 'icon' => '🌸', 'children' => [
            ['en' => 'Seasonal Flowers', 'bn' => 'মৌসুমী ফুল'],
            ['en' => 'Perennial Flowers', 'bn' => 'বহুবর্ষজীবী ফুল'],
            ['en' => 'Bonsai', 'bn' => 'বনসাই'],
        ]],
        ['en' => 'Pots & Planters', 'bn' => 'টব ও প্ল্যান্টার', 'icon' => '🪣', 'children' => [
            ['en' => 'Ceramic Pots', 'bn' => 'সিরামিক টব'],
            ['en' => 'Plastic Pots', 'bn' => 'প্লাস্টিক টব'],
            ['en' => 'Hanging Planters', 'bn' => 'ঝুলন্ত প্ল্যান্টার'],
        ]],
        ['en' => 'Seeds', 'bn' => 'বীজ', 'icon' => '🌱', 'children' => [
            ['en' => 'Vegetable Seeds', 'bn' => 'সবজি বীজ'],
            ['en' => 'Flower Seeds', 'bn' => 'ফুলের বীজ'],
            ['en' => 'Herb Seeds', 'bn' => 'হার্ব বীজ'],
        ]],
        ['en' => 'Fertilizers & Soil', 'bn' => 'সার ও মাটি', 'icon' => '🌿', 'children' => [
            ['en' => 'Organic Fertilizer', 'bn' => 'জৈব সার'],
            ['en' => 'Potting Mix', 'bn' => 'পটিং মিক্স'],
            ['en' => 'Compost', 'bn' => 'কম্পোস্ট'],
        ]],
        ['en' => 'Gardening Tools', 'bn' => 'বাগানের সরঞ্জাম', 'icon' => '🛠️', 'children' => [
            ['en' => 'Hand Tools', 'bn' => 'হাতের সরঞ্জাম'],
            ['en' => 'Watering Equipment', 'bn' => 'সেচ সরঞ্জাম'],
            ['en' => 'Accessories', 'bn' => 'আনুষঙ্গিক'],
        ]],
    ];

    public function run(): void
    {
        foreach ($this->categories as $index => $cat) {
            $parent = Category::create([
                'slug' => Str::slug($cat['en']),
                'icon' => $cat['icon'] ?? '🌿',
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);

            $parent->translations()->createMany([
                ['locale' => 'en', 'name' => $cat['en'], 'description' => null],
                ['locale' => 'bn', 'name' => $cat['bn'], 'description' => null],
            ]);

            foreach ($cat['children'] as $childIndex => $child) {
                $childCat = Category::create([
                    'parent_id' => $parent->id,
                    'slug' => Str::slug($child['en']),
                    'sort_order' => $childIndex + 1,
                    'is_active' => true,
                ]);

                $childCat->translations()->createMany([
                    ['locale' => 'en', 'name' => $child['en']],
                    ['locale' => 'bn', 'name' => $child['bn']],
                ]);
            }
        }
    }
}
