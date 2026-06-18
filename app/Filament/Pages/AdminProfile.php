<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;

class AdminProfile extends Page
{
    protected static ?string $navigationIcon = "heroicon-o-user-circle";
    protected static ?string $navigationLabel = "Profile";
    protected static ?string $title = "Admin Profile";
    protected static ?int $navigationSort = 100;
    protected static ?string $slug = "profile";
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = "filament.pages.admin-profile";

    public ?array $data = [];

    public function mount(): void
    {
        $admin = auth()->guard('admin')->user();

        $this->form->fill([
            'name' => $admin->name,
            'email' => $admin->email,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Profile Information')
                    ->description('Update your profile information.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ]),

                Forms\Components\Section::make('Change Password')
                    ->description('Update your password to keep your account secure.')
                    ->schema([
                        Forms\Components\TextInput::make('current_password')
                            ->label('Current Password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $context): bool => $context === 'edit')
                            ->currentPassword()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('password')
                            ->label('New Password')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->confirmed()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->revealable()
                            ->dehydrated(false),
                    ])
                    ->visible(fn (): bool => true),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $admin = auth()->guard('admin')->user();

        // Validate current password if changing password
        if (!empty($data['password'])) {
            if (!Hash::check($data['current_password'], $admin->password)) {
                Notification::make()
                    ->title('Error')
                    ->body('The provided password does not match your current password.')
                    ->danger()
                    ->send();
                return;
            }
        }

        // Update basic profile information
        $admin->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        // Update password if provided
        if (!empty($data['password'])) {
            $admin->update([
                'password' => Hash::make($data['password']),
            ]);

            Notification::make()
                ->title('Password Updated')
                ->body('Your password has been changed successfully.')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Profile Updated')
                ->body('Your profile information has been updated successfully.')
                ->success()
                ->send();
        }
    }
}
