<?php

namespace App\Http\Controllers\Api;

use App\Events\Chat\SendMessage;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class MessageController extends Controller
{
    protected $message;
    public function __construct(Message $message) {
        $this->message = $message;
    }
    
    public function listMessage($id) {
        $userFrom =  Auth::user();
        $userTo =  User::where('id', $id)->first();

        $message = $this->message->where(
            ["from"=> $userFrom->id,"to"=>$userTo->id]
            )->orWhere(
            ["to"=> $userTo->id,"from"=>$userFrom->id]
            )->orderBy("created_at","ASC")->get();


        return response()->json(["message" => $message], 200);
    }

    public function sendMessage(Request $request) {
        $userFrom =  Auth::user();
        $userTo =  User::where('id', $request->to)->first();

        $message = new Message();
        $message->from = $userFrom->id;
        $message->to = $userTo->id;
        $message->content = filter_var($request->content, FILTER_SANITIZE_STRIPPED);
        $message->save();

        Event::dispatch(new SendMessage($message,$request->to));

        return response()->json(["message" => "Message sent successfully"], 200);
    }

    
}
