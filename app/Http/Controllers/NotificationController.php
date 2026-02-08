<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function update(Request $request){
        $notification  = Notification::find($request->id);
        if($notification){
            $notification->isSeen = '1';
            $notification->update();
        }
    }
}
