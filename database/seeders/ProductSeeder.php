<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\DifficultyLevel;
use App\Enums\PlantType;
use App\Enums\SunlightRequirement;
use App\Enums\WateringFrequency;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    private array $plants = [
        ['en' => 'Money Plant', 'bn' => 'মানি প্ল্যান্ট', 'price' => 150, 'type' => 'indoor'],
        ['en' => 'Snake Plant', 'bn' => 'স্নেক প্ল্যান্ট', 'price' => 250, 'type' => 'indoor'],
        ['en' => 'Peace Lily', 'bn' => 'পিস লিলি', 'price' => 350, 'type' => 'indoor'],
        ['en' => 'Pothos', 'bn' => 'পোথোস', 'price' => 120, 'type' => 'indoor'],
        ['en' => 'Spider Plant', 'bn' => 'স্পাইডার প্ল্যান্ট', 'price' => 180, 'type' => 'indoor'],
        ['en' => 'ZZ Plant', 'bn' => 'জেডজেড প্ল্যান্ট', 'price' => 450, 'type' => 'indoor'],
        ['en' => 'Rubber Tree', 'bn' => 'রাবার ট্রি', 'price' => 550, 'type' => 'indoor'],
        ['en' => 'Fiddle Leaf Fig', 'bn' => 'ফিডল লিফ ফিগ', 'price' => 800, 'type' => 'indoor'],
        ['en' => 'Aloe Vera', 'bn' => 'অ্যালো ভেরা', 'price' => 200, 'type' => 'both'],
        ['en' => 'Cactus Mix', 'bn' => 'ক্যাকটাস মিক্স', 'price' => 150, 'type' => 'indoor'],
        ['en' => 'Bougainvillea', 'bn' => 'বোগেনভিলিয়া', 'price' => 300, 'type' => 'outdoor'],
        ['en' => 'Hibiscus', 'bn' => 'জবা ফুল', 'price' => 250, 'type' => 'outdoor'],
        ['en' => 'Marigold', 'bn' => 'গাঁদা ফুল', 'price' => 80, 'type' => 'outdoor'],
        ['en' => 'Rose Bush', 'bn' => 'গোলাপ গাছ', 'price' => 400, 'type' => 'outdoor'],
        ['en' => 'Jasmine', 'bn' => 'জুঁই ফুল', 'price' => 200, 'type' => 'outdoor'],
        ['en' => 'Bamboo Palm', 'bn' => 'বাঁশ পাম', 'price' => 600, 'type' => 'indoor'],
        ['en' => 'Bird of Paradise', 'bn' => 'বার্ড অব প্যারাডাইস', 'price' => 1200, 'type' => 'indoor'],
        ['en' => 'Monstera Deliciosa', 'bn' => 'মনস্টেরা ডেলিসিওসা', 'price' => 950, 'type' => 'indoor'],
        ['en' => 'Philodendron', 'bn' => 'ফিলোডেন্ড্রন', 'price' => 380, 'type' => 'indoor'],
        ['en' => 'Dracaena', 'bn' => 'ড্রাসেনা', 'price' => 420, 'type' => 'indoor'],
        ['en' => 'Tulsi (Holy Basil)', 'bn' => 'তুলসী গাছ', 'price' => 100, 'type' => 'both'],
        ['en' => 'Neem Tree Sapling', 'bn' => 'নিম গাছের চারা', 'price' => 150, 'type' => 'outdoor'],
        ['en' => 'Lemon Tree', 'bn' => 'লেবু গাছ', 'price' => 350, 'type' => 'outdoor'],
        ['en' => 'Mango Sapling', 'bn' => 'আম গাছের চারা', 'price' => 500, 'type' => 'outdoor'],
        ['en' => 'Banana Sapling', 'bn' => 'কলা গাছের চারা', 'price' => 280, 'type' => 'outdoor'],
        ['en' => 'Guava Tree', 'bn' => 'পেয়ারা গাছ', 'price' => 400, 'type' => 'outdoor'],
        ['en' => 'Papaya Plant', 'bn' => 'পেঁপে গাছ', 'price' => 180, 'type' => 'outdoor'],
        ['en' => 'Areca Palm', 'bn' => 'আরেকা পাম', 'price' => 750, 'type' => 'both'],
        ['en' => 'Fern Varieties', 'bn' => 'ফার্ন জাতীয় গাছ', 'price' => 220, 'type' => 'indoor'],
        ['en' => 'Lucky Bamboo', 'bn' => 'লাকি ব্যাম্বু', 'price' => 300, 'type' => 'indoor'],
    ];

    public function run(): void
    {
        $categories = Category::whereNull('parent_id')->get();
        $indoorCategory = $categories->first(fn ($c) => str_contains($c->slug, 'indoor'));
        $outdoorCategory = $categories->first(fn ($c) => str_contains($c->slug, 'outdoor'));
        $flowerCategory = $categories->first(fn ($c) => str_contains($c->slug, 'flower'));

        foreach ($this->plants as $index => $plant) {
            $type = $plant['type'];
            $category = match($type) {
                'indoor' => $indoorCategory,
                'outdoor' => $outdoorCategory ?? $indoorCategory,
                'both' => $indoorCategory,
                default => $indoorCategory,
            };

            $isFeatured = $index < 8;
            $isNew = $index >= 20;

            $product = Product::create([
                'category_id' => $category?->id ?? 1,
                'slug' => Str::slug($plant['en']),
                'sku' => 'GNG-' . str_pad((string)($index + 1), 4, '0', STR_PAD_LEFT),
                'price' => $plant['price'],
                'compare_price' => rand(0, 1) ? $plant['price'] * 1.2 : null,
                'stock_quantity' => rand(5, 50),
                'low_stock_threshold' => 5,
                'is_active' => true,
                'is_featured' => $isFeatured,
                'is_new_arrival' => $isNew,
                'requires_shipping' => true,
                'plant_type' => PlantType::from($type === 'both' ? 'both' : $type),
                'sunlight' => SunlightRequirement::cases()[array_rand(SunlightRequirement::cases())],
                'watering' => WateringFrequency::cases()[array_rand(WateringFrequency::cases())],
                'difficulty' => DifficultyLevel::cases()[array_rand(DifficultyLevel::cases())],
            ]);

            // Assign images
            $imagePath = 'products/money_plant.png'; // Default generic fallback
            if ($plant['en'] === 'Money Plant' || $plant['en'] === 'Pothos') {
                $imagePath = 'products/money_plant.png';
            } elseif ($plant['en'] === 'Tulsi (Holy Basil)') {
                $imagePath = 'products/tulsi_plant.png';
            } elseif ($plant['en'] === 'Mango Sapling' || $plant['en'] === 'Lemon Tree' || str_contains($plant['en'], 'Sapling') || str_contains($plant['en'], 'Tree')) {
                $imagePath = 'products/mango_sapling.png';
            }

            $product->images()->create([
                'path' => $imagePath,
                'alt_text' => $plant['en'],
                'is_primary' => true,
                'sort_order' => 1,
            ]);

            $product->translations()->createMany([
                [
                    'locale' => 'en',
                    'name' => $plant['en'],
                    'short_description' => "Beautiful {$plant['en']} for your home or garden.",
                    'description' => "<p>The {$plant['en']} is a popular choice for plant lovers. Perfect for both beginners and experienced gardeners.</p>",
                    'care_instructions' => "Water regularly. Keep in appropriate light conditions. Fertilize monthly.",
                    'meta_title' => "Buy {$plant['en']} Online | GardenNGrow Bangladesh",
                    'meta_description' => "Buy {$plant['en']} online in Bangladesh. Home delivery available. Best quality plants at GardenNGrow.",
                ],
                [
                    'locale' => 'bn',
                    'name' => $plant['bn'],
                    'short_description' => "{$plant['bn']} আপনার বাড়ি বা বাগানের জন্য সুন্দর।",
                    'description' => "<p>{$plant['bn']} গাছপ্রেমীদের কাছে অত্যন্ত জনপ্রিয়। শিক্ষানবিশ ও অভিজ্ঞ সবার জন্য উপযুক্ত।</p>",
                    'care_instructions' => "নিয়মিত পানি দিন। উপযুক্ত আলোতে রাখুন। মাসে একবার সার দিন।",
                ],
            ]);
        }
    }
}
