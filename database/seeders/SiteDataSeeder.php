<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add Site Logo setting if it doesn't exist
        Setting::firstOrCreate(
            ['key' => 'site_logo'],
            [
                'group' => 'general',
                'value' => '', // Empty for default text logo, can be updated via admin
                'type' => 'image',
            ]
        );

        $pages = [
            [
                'slug' => 'faq',
                'en' => [
                    'title' => 'Frequently Asked Questions',
                    'content' => '<h2>General Questions</h2><p>Here you can find answers to our most frequently asked questions.</p>',
                ],
                'bn' => [
                    'title' => 'সাধারণ জিজ্ঞাসা',
                    'content' => '<h2>সাধারণ প্রশ্নসমূহ</h2><p>এখানে আপনি আমাদের সবচেয়ে সাধারণ প্রশ্নগুলির উত্তর পেতে পারেন।</p>',
                ]
            ],
            [
                'slug' => 'return-policy',
                'en' => [
                    'title' => 'Return Policy',
                    'content' => '<h2>Return & Refund Policy</h2><p>We accept returns within 7 days of purchase for damaged plants.</p>',
                ],
                'bn' => [
                    'title' => 'রিটার্ন পলিসি',
                    'content' => '<h2>রিটার্ন এবং রিফান্ড পলিসি</h2><p>ক্ষতিগ্রস্ত গাছের জন্য আমরা ক্রয়ের ৭ দিনের মধ্যে রিটার্ন গ্রহণ করি।</p>',
                ]
            ],
            [
                'slug' => 'about',
                'en' => [
                    'title' => 'About Us',
                    'content' => '<h2>Welcome to GardenNGrow</h2><p>We are passionate about bringing greenery into your life.</p>',
                ],
                'bn' => [
                    'title' => 'আমাদের সম্পর্কে',
                    'content' => '<h2>GardenNGrow এ স্বাগতম</h2><p>আমরা আপনার জীবনে সবুজ নিয়ে আসতে আগ্রহী।</p>',
                ]
            ],
            [
                'slug' => 'terms',
                'en' => [
                    'title' => 'Terms & Conditions',
                    'content' => '<h2>Terms of Service</h2><p>By using our website, you agree to our terms and conditions.</p>',
                ],
                'bn' => [
                    'title' => 'শর্তাবলী',
                    'content' => '<h2>সেবার শর্তাবলী</h2><p>আমাদের ওয়েবসাইট ব্যবহার করে, আপনি আমাদের শর্তাবলীতে সম্মত হচ্ছেন।</p>',
                ]
            ],
            [
                'slug' => 'privacy',
                'en' => [
                    'title' => 'Privacy Policy',
                    'content' => '<h2>Privacy Policy</h2><p>Your privacy is important to us. We do not sell your data.</p>',
                ],
                'bn' => [
                    'title' => 'গোপনীয়তা নীতি',
                    'content' => '<h2>গোপনীয়তা নীতি</h2><p>আপনার গোপনীয়তা আমাদের কাছে গুরুত্বপূর্ণ। আমরা আপনার ডেটা বিক্রি করি না।</p>',
                ]
            ]
        ];

        foreach ($pages as $data) {
            $page = Page::firstOrCreate(['slug' => $data['slug']]);

            $page->setTranslation('en', [
                'title' => $data['en']['title'],
                'content' => $data['en']['content'],
            ]);

            $page->setTranslation('bn', [
                'title' => $data['bn']['title'],
                'content' => $data['bn']['content'],
            ]);
        }
    }
}
