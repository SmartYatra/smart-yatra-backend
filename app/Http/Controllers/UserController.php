<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getNotifications($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Retrieve the user's notifications
        $notifications = $user->notifications;

        return response()->json(['success' => true, 'notifications' => $notifications]);
    }
}
