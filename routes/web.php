<?php

use App\Livewire\Users;
use App\Livewire\Chat\Chat;
use App\Livewire\Chat\Index;
use App\Livewire\Chat\ChatList;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Broadcast;





//kalau dia sudah login dan mencoba mengakses halaman auth akan di redirect ke('/dashboard')
Route::middleware('redirectlogin')->group(function () {

});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Index::class)->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/chat', Index::class)->name('chat.index');
    Route::post('/chat/{query}', Chat::class)->name('chat.chat.post');
    Route::get('/chat/{query}', Chat::class)->name('chat.chat');
    Route::get('/users', Users::class)->name('users');
    Route::post('/broadcasting/auth', function (Illuminate\Http\Request $request) {
    return Broadcast::auth($request);
    });
});





require __DIR__.'/auth.php';
