<?php

declare(strict_types=1);

namespace App\Filament\Resources\BannerResource\Pages;

use App\Filament\Resources\BannerResource;
use App\Traits\FilamentTranslatableCreatePage;
use Filament\Resources\Pages\CreateRecord;

class CreateBanner extends CreateRecord
{
    use FilamentTranslatableCreatePage;

    protected static string $resource = BannerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
