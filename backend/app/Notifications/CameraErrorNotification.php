<?php

namespace App\Notifications;

use App\Models\Camera;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CameraErrorNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $camera;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Camera $camera)
    {
        $this->camera = $camera;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => "{$this->camera->name} gagal mengambil snapshot"
        ];
    }
}
