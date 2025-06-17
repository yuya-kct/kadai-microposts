@if (Auth::id() != $micropost->user_id) {{-- 自分以外の投稿はお気に入りボタンを表示できる --}}
    @if (Auth::user()->is_favorites($micropost->id))
        {{-- unfavoriteボタンのフォーム --}}
        <form method="POST" action="{{ route('favorites.unfavorite', $micropost->id) }}" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="group flex items-center gap-2 p-2 rounded-full hover:bg-yellow-50 transition-colors duration-200" 
                onclick="return confirm('お気に入りを外しますか？')">
                <div class="flex items-center justify-center w-8 h-8 rounded-full group-hover:bg-yellow-100">
                    {{-- お気に入り済みアイコン（塗りつぶし黄色スター） --}}
                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <span class="text-sm text-yellow-500 group-hover:text-yellow-600">
                    {{ $micropost->favorites()->count() }}
                </span>
            </button>
        </form>
    @else
        {{-- favoriteボタンのフォーム --}}
        <form method="POST" action="{{ route('favorites.favorite', $micropost->id) }}" class="inline">
            @csrf
            <button type="submit" class="group flex items-center gap-2 p-2 rounded-full hover:bg-yellow-50 transition-colors duration-200">
                <div class="flex items-center justify-center w-8 h-8 rounded-full group-hover:bg-yellow-100">
                    {{-- お気に入り前アイコン（アウトライングレースター） --}}
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <span class="text-sm text-gray-500 group-hover:text-yellow-500">
                    {{ $micropost->favorites()->count() }}
                </span>
            </button>
        </form>
    @endif
@endif