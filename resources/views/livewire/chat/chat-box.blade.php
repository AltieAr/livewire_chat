<div
x-data="{
    height: 0,
    markAsRead: null,
    conversationElement: null
}"
x-init="
    conversationElement = document.getElementById('conversation');
    height = conversationElement.scrollHeight;
    $nextTick(() => conversationElement.scrollTop = height);

    Echo.private('users.{{ Auth()->user()->id }}')
        .notification((notification) => {
            if (
                notification['type'] === 'App\\Notifications\\MessageRead' &&
                notification['conversation_id'] == {{ $this->selectedConversations->id }}
            ) {
                markAsRead = true;
                window.Livewire.dispatch('messageRead', {
                    id: notification['conversation_id']
                });
            }
        });
"
@scroll-bottom.window="
    $nextTick(() => conversationElement.scrollTop = conversationElement.scrollHeight);
"
class="w-full overflow-hidden">

    <div class="border-b flex flex-col overflow-y-scroll grow h-full">
        <!-- HEADER -->
        <header class="w-full sticky inset-x-0 flex pb-[5px] pt-[5px] top-0 z-10 bg-white border-b">
            <div class="flex w-full items-center px-2 lg:px-4 gao-2 md:gap-5">
                <a class="shrink-0 lg:hidden" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                    </svg>
                </a>
                <div class="shrink-0">
                    <x-avatar class="h-9 w-9 lg:w-11 lg:h-11" />
                </div>
                @php $receiver = $receive @endphp
                <h6 class="font-bold truncate">{{ $receiver }}</h6>
            </div>
        </header>

        <!-- BODY -->
        <main
            @scroll="
                scrollTop = $el.scrollTop;
                if (scrollTop <= 0) {
                    window.Livewire.dispatch('loadMore');
                }
            "
            @update-chat-height.window="
                newHeight = $el.scrollHeight;
                oldHeight = height;
                $el.scrollTop = newHeight - oldHeight;
                height = newHeight;
            "
            id="conversation"
            class="flex flex-col gap-3 p-2.5 overflow-y-auto flex-grow overscroll-contain overflow-x-hidden w-full my-auto">

            @if ($loadedMessages)
                @foreach ($loadedMessages as $Message)
                    <div
                        @class([
                            'max-w-[85%] md:max-w-[78%] flex w-auto gap-2 relative mt-2',
                            'ml-auto' => $Message->sender_id === auth()->id()
                        ])>

                        <div @class([
                            'flex flex-wrap text-[15px] rounded-xl p-2.5 flex flex-col text-black bg-[#f6f6f8fb]',
                            'rounded-bl-none border border-gray-200/40' => !($Message->sender_id === auth()->id()),
                            'rounded-br-none bg-blue-500/80 text-white' => ($Message->sender_id === auth()->id())
                        ])>
                            <p class="whitespace-normal truncate text-sm md:text-base tracking-wide lg:tracking-normal">
                                {{ $Message->body }}
                            </p>

                            <div class="ml-auto flex gap-2">
                                <p @class([
                                    'text-xs',
                                    'text-gray-500' => !($Message->sender_id === auth()->id()),
                                    'text-gray' => ($Message->sender_id === auth()->id()),
                                ])>
                                    {{ $Message->created_at->format('g:i a') }}
                                </p>

                                @if ($Message->sender_id === auth()->id())
                                    <span x-data="{ markAsRead: @json($Message->isRead()) }" class="text-gray-300">
                                        @if ($Message->isRead())
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-all" viewBox="0 0 16 16">
                                                <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                                                <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                            </svg>
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </main>

        <!-- FOOTER -->
        <footer class="shrink-0 z-10 bg-white inset-x-0">
            <div class="p-2 border-t">
                <form wire:submit.prevent="sendMessage" class="w-full" method="POST">
                    <div class="grid grid-cols-12 gap-2">
                        <input
                            type="text"
                            wire:model.defer="body"
                            placeholder="Write your message here"
                            maxlength="1700"
                            class="col-span-10 bg-gray-100 border-0 rounded-lg focus:ring-0 focus:outline-none">

                        <button
                            type="submit"
                            class="col-span-2 bg-blue-500 text-white rounded-lg disabled:opacity-50">
                            Send
                        </button>
                    </div>
                </form>

                @error('body')
                    {{-- <p>{{ $message }}</p> --}}
                @enderror
            </div>
        </footer>
    </div>
</div>
