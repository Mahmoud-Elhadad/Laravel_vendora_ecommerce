<?php

namespace App\Http\Controllers;

use App\Models\UserMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function show_dash_message()
    {
        $messages = UserMessage::all();
        $unseen_messages = UserMessage::where('view', '0')->count();

        return view('Dashboard.pages.message', compact('messages', 'unseen_messages'));
    }

    public function update_view_message(Request $request)
    {
        UserMessage::where('id', $request->message_id)->update([
            'view' => '1',
        ]);

        return UserMessage::where('view', '0')->count();
    }

    public function delete_message(int $id)
    {
        UserMessage::where('id', $id)->delete();

        return to_route('dash.show.message');
    }
}
