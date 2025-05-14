<?php

namespace App\Livewire\Chat;

use Livewire\Component;

class Index extends Component
{
    public $selectedConversations;
    public function render()
    {

        $user = auth()->user();

        // dd($conversations);

            return view('livewire.chat.index',[
                'conversations'=> $user?->conversations()?->latest('updated_at')?->get()
            ]);


            // return view('livewire.chat.chat-list',['users' => User::where('id','!=',Auth::id())->get()]);

    }

}
