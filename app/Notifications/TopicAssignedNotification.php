<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TopicAssignedNotification extends Notification implements ShouldQueue
{
    public function __construct(public ResearchTopic $topic) {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Research Topic Assignment')
            ->line("You've been assigned to review a new topic:")
            ->line($this->topic->title)
            ->action('Review Topic', route('supervisor.dashboard'));
    }

    public function toArray($notifiable)
    {
        return [
            'topic_id' => $this->topic->id,
            'title' => $this->topic->title,
            'message' => 'New topic assigned for review',
        ];
    }
}
