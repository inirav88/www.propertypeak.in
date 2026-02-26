<?php

namespace App\Modules\RealEstate\Notifications;

use App\Modules\RealEstate\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertyApproved extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Property $property,
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
        $propertyUrl = route('properties.show', $this->property->slug);

        return (new MailMessage)
            ->subject('Your Property Has Been Approved!')
            ->greeting("Hello {$notifiable->name},")
            ->line("Great news! Your property listing has been approved and is now live on our platform.")
            ->line("**Property:** {$propertyTitle}")
            ->line("**Location:** {$this->property->city}, {$this->property->state}")
            ->action('View Property', $propertyUrl)
            ->line('Your property is now visible to potential buyers and renters.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Property Approved',
            'message' => "Your property '{$this->property->title}' has been approved and is now live.",
            'property_id' => $this->property->id,
            'property_title' => $this->property->title,
            'property_slug' => $this->property->slug,
            'action_url' => '/properties/' . $this->property->slug,
            'action_text' => 'View Property',
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(object $notifiable): string
    {
        return 'property_approved';
    }
}
