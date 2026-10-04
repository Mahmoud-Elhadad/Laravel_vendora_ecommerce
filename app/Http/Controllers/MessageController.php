<?php

namespace App\Http\Controllers;

use App\Models\UserMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    public function show_dash_message()
    {
        Gate::forUser(auth("dashboard")->user())->authorize("show-dashboard");
        $messages = UserMessage::all();
        $unseen_messages = UserMessage::where('view', '0')->count();

        return view('Dashboard.pages.message', compact('messages', 'unseen_messages'));
    }

    public function update_view_message(Request $request)
    {
        Gate::forUser(auth("dashboard")->user())->authorize("show-dashboard");
        UserMessage::where('id', $request->message_id)->update([
            'view' => '1',
        ]);

        return UserMessage::where('view', '0')->count();
    }

    public function delete_message(int $id)
    {
        Gate::forUser(auth("dashboard")->user())->authorize("show-dashboard");
        UserMessage::where('id', $id)->delete();

        return to_route('dash.show.message');
    }
}
