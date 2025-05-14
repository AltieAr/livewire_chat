<?php

namespace App\Providers;
use Carbon\Carbon;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       Carbon::macro('shortDiffForHumans', function () {
            $diff = $this->diffInSeconds();

            if ($diff < 10) {
                return $diff . 's'; // Menampilkan dalam detik
            } elseif ($diff < 60) {
                return $diff . 's'; // Menampilkan dalam detik
            } elseif ($diff < 3600) {
                return floor($diff / 60) . 'm'; // Menampilkan dalam menit
            } elseif ($diff < 86400) {
                return floor($diff / 3600) . 'h'; // Menampilkan dalam jam
            } else {
                return floor($diff / 86400) . 'd'; // Menampilkan dalam hari
            }
        });

    }
}
