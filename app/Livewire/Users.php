<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Users extends Component
{

    public function message($userId){
        // dd($userId);
        $authenticatedid = Auth::id();
        $existConv = Conversation::where(function ($query) use($authenticatedid,$userId){
            $query->where('sender_id',$authenticatedid)
                ->where('receiver_id',$userId);

        })->orwhere(function ($query) use($authenticatedid,$userId){
            $query->where('sender_id',$userId)
                ->where('receiver_id',$authenticatedid);

        })->first();

        // jika sudah ada conv dengan id ini akan langsung redirect ke route
        if($existConv){
            // dd('conversation ID :', $existConv->id);
            return redirect()->route('chat.chat',['query'=>$existConv->id]);
        }

        // membuat conversasion
        $createConv = Conversation::create([
            'sender_id' => $authenticatedid,
            'receiver_id' => $userId
        ]);

        //setelah membuat conversation langsung redirect ke route
        return redirect()->route('chat.chat',
        ['query'=>$createConv->id]
        );

        // dd($createConv);
    }
    public function render()
    {
        // return view('livewire.users',['users' => User::all()]);
        return view('livewire.users',['users' => User::where('id','!=',Auth::id())->get()]);


    }
}
