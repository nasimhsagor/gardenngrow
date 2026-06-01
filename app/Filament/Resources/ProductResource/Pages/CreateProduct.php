<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Traits\FilamentTranslatableCreatePage;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use FilamentTranslatableCreatePage;

    protected static string $resource = ProductResource::class;
}
