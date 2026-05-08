<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BannerType;
use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'type' => BannerType::HeroSlider,
                'sort_order' => 1,
                'link' => '/shop',
                'is_active' => true,
                'en' => ['title' => 'Bring Nature Indoors', 'subtitle' => 'Discover 500+ indoor plants delivered to your door'],
                'bn' => ['title' => 'প্রকৃতিকে ঘরে আনুন', 'subtitle' => '৫০০+ ইনডোর গাছ আপনার দরজায় পৌঁছে দেব'],
            ],
            [
                'type' => BannerType::HeroSlider,
                'sort_order' => 2,
                'link' => '/shop?category=outdoor',
                'is_active' => true,
                'en' => ['title' => 'Transform Your Garden', 'subtitle' => 'Premium outdoor plants for every space'],
                'bn' => ['title' => 'আপনার বাগান সাজান', 'subtitle' => 'প্রতিটি স্থানের জন্য প্রিমিয়াম আউটডোর গাছ'],
            ],
            [
                'type' => BannerType::Promotional,
                'sort_order' => 1,
                'link' => '/shop',
                'is_active' => true,
                'en' => ['title' => 'Free Delivery Above ৳1500', 'subtitle' => 'Shop now and save on shipping'],
                'bn' => ['title' => '৳১৫০০-এর উপরে বিনামূল্যে ডেলিভারি', 'subtitle' => 'এখনই কিনুন এবং শিপিংয়ে সাশ্রয় করুন'],
            ],
        ];

        foreach ($banners as $index => $data) {
            $imagePath = 'banners/placeholder.jpg';
            if ($index === 0) {
                $imagePath = 'banners/banner_indoor_plants.png';
            } elseif ($index === 1) {
                $imagePath = 'banners/banner_outdoor_garden.png';
            }

            $banner = Banner::firstOrCreate([
                'type' => $data['type'],
                'sort_order' => $data['sort_order'],
            ], [
                'link' => $data['link'],
                'is_active' => $data['is_active'],
                'image' => $imagePath,
            ]);
            $banner->setTranslation('en', $data['en']);
            $banner->setTranslation('bn', $data['bn']);
        }
    }
}
