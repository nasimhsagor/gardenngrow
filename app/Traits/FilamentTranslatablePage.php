<?php

declare(strict_types=1);

namespace App\Traits;

trait FilamentTranslatablePage
{
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record && $this->record->translations) {
            foreach ($this->record->translations as $translation) {
                $locale = $translation->locale;
                foreach ($translation->toArray() as $key => $value) {
                    if (in_array($key, ['id', 'product_id', 'category_id', 'blog_id', 'page_id', 'banner_id', 'locale', 'created_at', 'updated_at'])) {
                        continue;
                    }
                    $data['translations'][$locale][$key] = $value;
                }
            }
        }
        return $data;
    }

    protected function afterSave(): void
    {
        $data = $this->form->getRawState();
        if (isset($data['translations']) && is_array($data['translations'])) {
            foreach ($data['translations'] as $locale => $translationData) {
                if (is_array($translationData)) {
                    $this->record->translations()->updateOrCreate(
                        ['locale' => $locale],
                        $translationData
                    );
                }
            }
        }
    }
}
