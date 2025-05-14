<?php

namespace App\Livewire\Chat;
// use App\Models\Conversation;
use Livewire\Component;
use App\Models\Conversation;

class Chat extends Component
{
    public $query;
    public $receiver;
    public $Sender;
    public $selectedConversations;
    public function mount($query)
    {
        // dd($this);
        // dd($query);

        $this->selectedConversations = Conversation::findOrFail($this->query);

        // dd($this->selectedConversations);
        $this->receiver = $this->selectedConversations->receiver_id;
        $this->Sender = $this->selectedConversations->sender_id;

        $this->query = $query;





    }
    public function render()
    {
        return view('livewire.chat.chat');

    }
}
