<?php

namespace App\Modules\RealEstate\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private string $type,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $typeLabel = ucfirst($this->type);
        $userName = $notifiable->name;
        $dashboardUrl = $this->type === 'developer' 
            ? url('/developer/dashboard')
            : url('/agent/dashboard');

        return (new MailMessage)
            ->subject("Your {$typeLabel} Account Has Been Approved!")
            ->greeting("Congratulations {$userName}!")
            ->line("We are pleased to inform you that your {$typeLabel} account has been approved.")
            ->line("You can now access your dashboard and start adding properties.")
            ->action('Go to Dashboard', $dashboardUrl)
            ->line('Thank you for choosing our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $dashboardUrl = $this->type === 'developer' 
            ? '/developer/dashboard'
            : '/agent/dashboard';

        return [
            'title' => 'Account Approved',
            'message' => "Your {$this->type} account has been approved. You can now start using the platform.",
            'type' => $this->type,
            'action_url' => $dashboardUrl,
            'action_text' => 'Go to Dashboard',
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(object $notifiable): string
    {
        return 'account_approved';
    }
}
