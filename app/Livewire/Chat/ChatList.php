<?php

namespace App\Livewire\Chat;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatList extends Component
{
    public $selectedConversations;

    public function render()
    {
        $user = auth()->user();  // Hanya panggil sekali

        // Memuat percakapan terbaru dengan pesan-pesan terbaru
        $conversations = $user->conversations()
            ->with(['messages' => fn ($query) => $query->latest('created_at')])  // Memuat pesan terbaru
            ->latest('updated_at')  // Memuat percakapan terbaru berdasarkan waktu update
            ->get();

                // dd($conversations->first()->messages->first()?->created_at);


        return view('livewire.chat.chat-list', [
            'conversations' => $conversations  // Menggunakan variable yang sudah dimuat sebelumnya
        ]);
    }

    public function getListeners()
    {
        return [
            'messageSent' => 'refreshChatList',
        ];
    }

    public function refreshChatList($conversationId)
    {
        // // Bisa reload semua, atau hanya update conversation tertentu
        // $this->render(); // Atau sesuaikan methodmu
    }


}
