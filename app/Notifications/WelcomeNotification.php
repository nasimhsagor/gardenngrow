<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to GardenNGrow! 🌱')
            ->greeting("Welcome, {$notifiable->name}! 🌿")
            ->line('Thank you for joining GardenNGrow — Bangladesh\'s premier online plant store.')
            ->line('Discover hundreds of indoor and outdoor plants, pots, seeds, and gardening accessories.')
            ->action('Start Shopping', route('shop.index'))
            ->line('Use code **WELCOME10** for 10% off your first order!')
            ->salutation('Happy Gardening! 🌿 — GardenNGrow Team');
    }
}
