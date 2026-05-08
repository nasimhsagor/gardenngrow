<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;

class SeoService
{
    public function productSchema(Product $product): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->short_description,
            'sku' => $product->sku,
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => 'BDT',
                'price' => $product->price,
                'availability' => $product->isInStock()
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
            ],
        ];
    }

    public function breadcrumbSchema(array $items): array
    {
        $list = [];
        foreach ($items as $position => $item) {
            $list[] = [
                '@type' => 'ListItem',
                'position' => $position + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $list,
        ];
    }

    public function organizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'GardenNGrow',
            'url' => config('app.url'),
            'description' => 'Online nursery and plant store in Bangladesh',
        ];
    }
}
