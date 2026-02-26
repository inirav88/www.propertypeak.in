<?php

namespace App\Modules\RealEstate\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountRejected extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private string $type,
        private ?string $reason = null,
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

        $mailMessage = (new MailMessage)
            ->subject("{$typeLabel} Account Application Update")
            ->greeting("Hello {$userName},")
            ->line("We regret to inform you that your {$typeLabel} account application has not been approved at this time.");

        if ($this->reason) {
            $mailMessage->line("**Reason:** {$this->reason}");
        }

        $mailMessage
            ->line("If you believe this was done in error or would like to provide additional information, please contact our support team.")
            ->line('Thank you for your interest in our platform.');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Account Not Approved',
            'message' => "Your {$this->type} account application has not been approved." . ($this->reason ? " Reason: {$this->reason}" : ''),
            'type' => $this->type,
            'reason' => $this->reason,
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(object $notifiable): string
    {
        return 'account_rejected';
    }
}
