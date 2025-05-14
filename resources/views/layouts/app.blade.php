<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="user-id" content="{{ auth()->id() }}">


        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 ">
            @include('components.layouts.navigation')

            <main>
                {{ $slot }}
            </main>
        </div>

        {{-- 🟢 Pastikan urutan ini benar --}}

        @livewireScripts


        <script>
            document.addEventListener("livewire:load", function () {
                const input = document.getElementById("chat-input");
                const button = document.getElementById("send-button");

                if (input && button) {
                    const toggleButton = () => {
                        const trimmedValue = input.value.trim();
                        button.disabled = trimmedValue === "";
                    };

                    // Cek awal saat halaman pertama dimuat
                    toggleButton();

                    // Cek setiap kali input berubah
                    input.addEventListener("input", toggleButton);
                }
            });



        </script>


    </body>
</html>
