<?php

declare(strict_types=1);

namespace App\Traits;

trait FilamentTranslatableCreatePage
{
    protected function afterCreate(): void
    {
        $data = $this->form->getRawState();
        if (isset($data['translations']) && is_array($data['translations'])) {
            foreach ($data['translations'] as $locale => $translationData) {
                if (is_array($translationData)) {
                    $this->record->translations()->create(
                        array_merge(['locale' => $locale], $translationData)
                      );
                }
            }
        }
    }
}
