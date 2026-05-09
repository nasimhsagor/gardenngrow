<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\SettingType;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = "heroicon-o-cog-6-tooth";
    protected static ?string $navigationGroup = "Settings";
    protected static ?string $navigationLabel = "Site Settings";
    protected static ?int $navigationSort = 1;

    protected static string $view = "filament.pages.settings";

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::all()->keyBy("key");

        $siteLogo = $settings->get("site_logo")?->value ?? "";

        // Backward compatibility: older records may contain JSON arrays or the literal "Array" string.
        if (is_string($siteLogo) && str_starts_with(trim($siteLogo), "[")) {
            $decoded = json_decode($siteLogo, true);
            $siteLogo = is_array($decoded)
                ? (string) ($decoded[0] ?? "")
                : $siteLogo;
        }

        if ($siteLogo === "Array") {
            $siteLogo = "";
        }

        $this->form->fill([
            "site_name" => $settings->get("site_name")?->value ?? "",
            "site_logo" => $siteLogo,
            "site_favicon" => $settings->get("site_favicon")?->value ?? "",
            "free_shipping_threshold" =>
                $settings->get("free_shipping_threshold")?->value ?? "",
            "phone" => $settings->get("phone")?->value ?? "",
            "email" => $settings->get("email")?->value ?? "",
            "address" => $settings->get("address")?->value ?? "",
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make("General")
                    ->schema([
                        Forms\Components\TextInput::make("site_name")->label(
                            "Site Name",
                        ),
                        Forms\Components\FileUpload::make("site_logo")
                            ->label("Site Logo")
                            ->image()
                            ->disk("public")
                            ->visibility("public")
                            ->directory("settings")
                            ->imagePreviewHeight("120")
                            ->maxSize(2048),
                        Forms\Components\TextInput::make(
                            "free_shipping_threshold",
                        )
                            ->label("Free Shipping Threshold (৳)")
                            ->numeric(),
                        Forms\Components\FileUpload::make("site_favicon")
                            ->label("Site Icon")
                            ->image()
                            ->disk("public")
                            ->visibility("public")
                            ->directory("settings")
                            ->imagePreviewHeight("120")
                            ->maxSize(2048),
                    ])
                    ->columns(2),
                Forms\Components\Section::make("Contact")
                    ->schema([
                        Forms\Components\TextInput::make("phone")->label(
                            "Phone",
                        ),
                        Forms\Components\TextInput::make("email")
                            ->label("Email")
                            ->email(),
                        Forms\Components\Textarea::make("address")
                            ->label("Address")
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ])
            ->statePath("data");
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            // FileUpload may return an array depending on Livewire/Filament state.
            if ($key === "site_logo" && is_array($value)) {
                $value = $value[0] ?? "";
            }

            Setting::set($key, $value);
        }

        Notification::make()
            ->title("Settings saved successfully")
            ->success()
            ->send();
    }
}
