<?php

namespace App\Notifications;

use App\Models\Camera;
use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
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
        return ['broadcast'];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => 'Kamera ' . $this->camera->name . ' bermasalah',
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('notification');
    }
}
