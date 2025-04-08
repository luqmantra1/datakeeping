<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class DocumentApprovalStatus extends Notification
{
    protected $document;
    protected $status;

    // Constructor to pass document and status
    public function __construct(Document $document, $status)
    {
        $this->document = $document;
        $this->status = $status;
    }

    // Determine which channels to use
    public function via($notifiable)
    {
        return ['mail', 'database']; // Email and Database channels
    }

    // Build the email notification
    public function toMail($notifiable)
{
    return (new MailMessage)
        ->line('Your document "' . $this->document->title . '" has been ' . $this->status . '.')
        ->action('View Document', url('/documents/' . $this->document->id))
        ->line('Thank you for using our application!');
}

public function toDatabase($notifiable)
{
    return [
        'document_id' => $this->document->id,
        'title' => $this->document->title,
        'status' => $this->status,
    ];
}

}
