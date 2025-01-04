<?php

namespace App\Notifications\Filament;

use App\Filament\Resources\TaskResource;
use App\Models\Task;
use Filament\Notifications\Actions\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;
use \Illuminate\Database\Eloquent\Model;

class DatabaseNotification extends Notification
{
    use Queueable;

    /**
     * @var array<string, mixed>
     */
    protected array $filamentNotification;

    /**
     * Create a new notification instance.
     * 
     */
    public function __construct()
    {
        $this->filamentNotification = FilamentNotification::make()
            ->title($this->title())
            ->body($this->body())
            ->icon($this->icon())
            ->actions($this->actions())
            ->getDatabaseMessage();
    }

    public function title(): string
    {
        return 'Base Notification';
    }

    public function body(): string
    {
        return 'This is a base notification.';
    }

    public function icon(): string
    {
        return 'heroicon-o-check-circle';
    }

    public function actions(): array
    {
        return [];
    }

    /**
     * @param  Model $notifiable
     * @return array<string>
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * @param  Model  $notifiable
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable): array
    {
        return $this->filamentNotification;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->filamentNotification;
    }
}
