<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApsRequestSubmitted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($apsRequest)
    {
        $this->apsRequest = $apsRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];// tambah 'mail' jika mau notif ke email
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pengajuan Mutasi APS Baru')
            ->line('Ada pengajuan Mutasi APS baru dari ' . $this->apsRequest->user->first_name .' '. $this->apsRequest->user->last_name)
            ->action('Lihat Pengajuan', url('/permohonan-mutasi/riwayat/' . $this->apsRequest->id));
    }

    public function toDatabase($notifiable)
    {
        $user = $this->apsRequest->user ?? null;
        $fullName = $user ? $user->first_name . ' ' . $user->last_name : 'Pengguna Tidak Dikenal';
    
        return [
            'message' => 'Pengajuan Mutasi APS baru dari ' . $fullName,
            'url' => '/permohonan-mutasi/riwayat/' . $this->apsRequest->id
        ];
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
            //
        ];
    }
}
