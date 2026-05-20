<?php

declare(strict_types=1);

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Traits\FilamentTranslatableCreatePage;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    use FilamentTranslatableCreatePage;

    protected static string $resource = CategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
