<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Traits\FilamentTranslatableCreatePage;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    use FilamentTranslatableCreatePage;

    protected static string $resource = PageResource::class;
}
