<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('admins-notifications', function (Admin $admin) {
    return true;
}, ['guards' => ['dashboard']]);
