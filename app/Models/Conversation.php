<?php

namespace App\Models;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id'
    ];

    // Ganti nama relasi menjadi messages()
    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function getReceiver() {
        if($this->sender_id == auth()->id()) {
            return User::firstWhere('id', $this->receiver_id);
        } else {
            return User::firstWhere('id', $this->sender_id);
        }
    }

    public function unreadMessagesCount() : int {
        return $unreadMessages= Message::where('conversation_id', '=', $this->id)
                      ->where('receiver_id', auth()->user()->id)
                      ->whereNull('read_at')
                      ->count();
    }
}
