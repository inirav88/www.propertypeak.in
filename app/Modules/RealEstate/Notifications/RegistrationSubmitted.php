<?php

namespace App\Modules\RealEstate\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private User $registeredUser,
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
        $userName = $this->registeredUser->name;
        $userEmail = $this->registeredUser->email;

        return (new MailMessage)
            ->subject("New {$typeLabel} Registration: {$userName}")
            ->greeting("Hello Admin,")
            ->line("A new {$typeLabel} has registered and is pending approval.")
            ->line("**Name:** {$userName}")
            ->line("**Email:** {$userEmail}")
            ->line("**Type:** {$typeLabel}")
            ->action('Review Application', url('/admin/' . $this->type . 's/pending'))
            ->line('Please review and approve/reject this application at your earliest convenience.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Registration Pending',
            'message' => "A new {$this->type} ({$this->registeredUser->name}) has registered and is pending approval.",
            'user_id' => $this->registeredUser->id,
            'user_name' => $this->registeredUser->name,
            'user_email' => $this->registeredUser->email,
            'type' => $this->type,
            'action_url' => '/admin/' . $this->type . 's/pending',
            'action_text' => 'Review Application',
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(object $notifiable): string
    {
        return 'registration_submitted';
    }
}
