<?php

declare(strict_types=1);

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use App\Traits\FilamentTranslatableCreatePage;
use Filament\Resources\Pages\CreateRecord;

class CreateBlog extends CreateRecord
{
    use FilamentTranslatableCreatePage;

    protected static string $resource = BlogResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
