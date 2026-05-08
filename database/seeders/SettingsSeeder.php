<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\SettingType;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['group' => 'general', 'key' => 'site_name', 'value' => 'GardenNGrow', 'type' => SettingType::Text],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Your Online Plant Paradise', 'type' => SettingType::Text],
            ['group' => 'general', 'key' => 'site_logo', 'value' => null, 'type' => SettingType::Image],
            ['group' => 'general', 'key' => 'site_favicon', 'value' => null, 'type' => SettingType::Image],
            ['group' => 'general', 'key' => 'free_shipping_threshold', 'value' => '1500', 'type' => SettingType::Number],
            // Contact
            ['group' => 'contact', 'key' => 'phone', 'value' => '+880 1700-000000', 'type' => SettingType::Text],
            ['group' => 'contact', 'key' => 'email', 'value' => 'info@gardenngrow.com', 'type' => SettingType::Text],
            ['group' => 'contact', 'key' => 'address', 'value' => 'Dhaka, Bangladesh', 'type' => SettingType::Textarea],
            ['group' => 'contact', 'key' => 'whatsapp', 'value' => '8801700000000', 'type' => SettingType::Text],
            // Social
            ['group' => 'social', 'key' => 'facebook', 'value' => 'https://facebook.com/gardenngrow', 'type' => SettingType::Text],
            ['group' => 'social', 'key' => 'instagram', 'value' => 'https://instagram.com/gardenngrow', 'type' => SettingType::Text],
            ['group' => 'social', 'key' => 'youtube', 'value' => '', 'type' => SettingType::Text],
            // SEO
            ['group' => 'seo', 'key' => 'meta_title', 'value' => 'GardenNGrow - Online Nursery Bangladesh', 'type' => SettingType::Text],
            ['group' => 'seo', 'key' => 'meta_description', 'value' => 'Buy plants online in Bangladesh. Indoor plants, outdoor plants, pots, seeds delivered to your door.', 'type' => SettingType::Textarea],
            ['group' => 'seo', 'key' => 'google_analytics_id', 'value' => '', 'type' => SettingType::Text],
            ['group' => 'seo', 'key' => 'facebook_pixel_id', 'value' => '', 'type' => SettingType::Text],
            // Payment
            ['group' => 'payment', 'key' => 'cod_enabled', 'value' => '1', 'type' => SettingType::Boolean],
            ['group' => 'payment', 'key' => 'sslcommerz_enabled', 'value' => '1', 'type' => SettingType::Boolean],
            ['group' => 'payment', 'key' => 'stripe_enabled', 'value' => '0', 'type' => SettingType::Boolean],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
