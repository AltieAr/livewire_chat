<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Livewire\Component;
use App\Notifications\MessageSent;
use App\Notifications\MessageRead;
use Illuminate\Support\Facades\Auth;




class ChatBox extends Component
{

    public $query;
    public $selectedConversations;
    public $body = '';
    public $loadedMessages;
    public $paginate_var=10;
    protected $listeners=[
        'loadMore'
    ];

    public function getListeners(){
        $auth_id = auth()->user()->id;

        return [
            'loadMore',
            "echo-private:users.{$auth_id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated"=>'broadcastedNotifications'
        ];
    }

    public function broadcastedNotifications($event){
        // dd($event);
        if($event['type']== MessageSent::class) {
            if($event['conversation_id']==$this->selectedConversations->id){
                $this->dispatch('scroll-bottom');
                $newMessage= Message::find($event['message_id']);

                #push message
                $this->loadedMessages->push($newMessage);

                #mark as read
                $newMessage->read_at= now();
                $newMessage->save();

                #broadcast
                 $this->selectedConversations->getReceiver()
                ->notify(new MessageRead($this->selectedConversations->id)

                );


            }
        }
    }


    public function loadMore(){
        // dd('detected');
        #increment
        $this->paginate_var+=10;

        #call loadMessage()
        $this->loadMessage();

        #update chat height
        $this->dispatch('update-chat-height');
    }

    public function loadMessage(){
        #get count
        $count = Message::where('conversation_id',$this->selectedConversations->id)->count();

        #skip and query
        $this->loadedMessages = Message::where('conversation_id',$this->selectedConversations->id)
        ->skip($count-$this->paginate_var)
        ->take($this->paginate_var)
        ->get();


        Message::where('conversation_id', $this->selectedConversations->id)
        ->where('receiver_id', auth()->id())
        ->whereNull('read_at')
        ->update(['read_at' => now()]);

        return $this->loadedMessages;
    }


    public function sendMessage(){
        $this->validate([
            'body' => 'required|string'
        ]);

        $createMessage = Message::create([
            'conversation_id' => $this->selectedConversations->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversations->getReceiver()->id,
            'body' => $this->body
        ]);

        $this->selectedConversations->touch();






        $this->body = '';
        $this->reset('body');

        #push message
        $this->loadedMessages->push($createMessage);

        // scroll to bottom
        $this->dispatch('scroll-bottom');
        // dd($createMessage);
        // dd($this->selectedConversations);
        // dd($this->body);


        // dd([
        //     'user' => Auth()->user(),
        //     'message' => $createMessage,
        //     'conversation' => $this->selectedConversations,
        //     'receiver_id' => $this->selectedConversations->getReceiver()->id,
        // ]);

        #broadcast
        $this->selectedConversations->getReceiver()
            ->notify(new MessageSent(
                Auth()->user(),
                $createMessage,
                $this->selectedConversations,
                $this->selectedConversations->getReceiver()->id
            ));


        $this->dispatch('messageSent', conversationId: $this->selectedConversations->id);
    }

    public function mount(){
        $this->loadMessage();
    }
    public function render()
    {
        // $test=env('VITE_APP_ID');
        // dd($test);
        return view('livewire.chat.chat-box',[
            'receive' => $this->selectedConversations->getReceiver()->name
        ]);

    }

    public function markAsRead()
{
    foreach ($this->selectedConversations->messages()->unread()->where('receiver_id', auth()->id())->get() as $message) {
        $message->markAsRead(); // pastikan method ini ubah status read
    }

    // Kirim event broadcast ke pengirim pesan
    Notification::route('broadcast', 'users.' . $message->sender_id)
        ->notify(new MessageRead($this->selectedConversations->id));
}
}
