<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Models\Message as ModelsMessage;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('chat');
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages()
    {
      return ModelsMessage::with('user')->get();
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        $message = new \App\Models\Message();
        $message->user_id = $user->id;
        $message->message = $request->message;

        $files_arr=[];

        if($request->has('files')){
            foreach($request->file('files') as $file){
                array_push($files_arr, uploadfile($file, 'files'));
            }
            $message->files = implode(",", $files_arr);
        }else{
            $message->files = null;
        }

        $message->save();

        //   broadcast(new MessageSent($user, $message))->toOthers();
        broadcast(new MessageSent($user, $message));

        return ['status' => 'Message Sent!'];
    }
}
