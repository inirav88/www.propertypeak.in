<?php

namespace App\Modules\RealEstate\Notifications;

use App\Modules\RealEstate\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertyRejected extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Property $property,
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
        $propertyTitle = $this->property->title;
        $editUrl = $this->property->added_by_role === 'developer'
            ? url('/developer/properties/' . $this->property->slug . '/edit')
            : url('/agent/properties/' . $this->property->slug . '/edit');

        $mailMessage = (new MailMessage)
            ->subject('Property Listing Update')
            ->greeting("Hello {$notifiable->name},")
            ->line("We regret to inform you that your property listing could not be approved at this time.")
            ->line("**Property:** {$propertyTitle}");

        if ($this->reason) {
            $mailMessage->line("**Reason:** {$this->reason}");
        }

        $mailMessage
            ->line('You can review and update your listing to address any issues.')
            ->action('Edit Property', $editUrl)
            ->line('If you have any questions, please contact our support team.');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $editUrl = $this->property->added_by_role === 'developer'
            ? '/developer/properties/' . $this->property->slug . '/edit'
            : '/agent/properties/' . $this->property->slug . '/edit';

        return [
            'title' => 'Property Not Approved',
            'message' => "Your property '{$this->property->title}' was not approved." . ($this->reason ? " Reason: {$this->reason}" : ''),
            'property_id' => $this->property->id,
            'property_title' => $this->property->title,
            'property_slug' => $this->property->slug,
            'reason' => $this->reason,
            'action_url' => $editUrl,
            'action_text' => 'Edit Property',
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(object $notifiable): string
    {
        return 'property_rejected';
    }
}
