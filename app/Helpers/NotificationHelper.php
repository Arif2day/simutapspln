<?php

namespace App\Helpers;

use DateTime;
use App\Models\Notifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationHelper{
    public static function sendNotif($target,$notif) {   
        try {
            $dataNotif = $notif->toDatabase(null);
            $data = new Notifications();
            $data->id = Str::uuid()->toString();
            $data->type = get_class($notif);
            $data->notifiable_type = \App\Models\Users::class;
            $data->notifiable_id = $target;
            $data->data = json_encode($dataNotif);
            $data->created_at = now();
            $data->updated_at = now();
            $data->save();
            // dd("SUKSES", $data);
        } catch (\Exception $e) {
            dd("GAGAL", $e->getMessage(), $e->getTraceAsString());
        }        
    }
}