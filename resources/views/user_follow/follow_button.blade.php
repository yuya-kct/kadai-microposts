@if (Auth::id() != $user->id)
    @if (Auth::user()->is_following($user->id))
        {{-- アンフォローボタン --}}
        <form method="POST" action="{{ route('user.unfollow', $user->id) }}" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="group relative px-4 py-1.5 border border-gray-300 rounded-full text-sm font-medium text-gray-900 hover:border-red-200 hover:bg-red-50 hover:text-red-600 transition-all duration-200 min-w-[90px]" 
                    onclick="return confirm('{{ $user->name }}さんのフォローを外しますか？')">
                <span class="group-hover:hidden flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    フォロー中
                </span>
                <span class="hidden group-hover:flex items-center text-red-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    フォロー解除
                </span>
            </button>
        </form>
    @else
        {{-- フォローボタン --}}
        <form method="POST" action="{{ route('user.follow', $user->id) }}" class="inline">
            @csrf
            <button type="submit" 
                    class="px-4 py-1.5 bg-black text-white rounded-full text-sm font-medium hover:bg-gray-800 transition-colors duration-200 min-w-[90px] flex items-center justify-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                フォロー
            </button>
        </form>
    @endif
@endif