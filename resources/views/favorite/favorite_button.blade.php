@if (Auth::id() != $micropost->user_id)
    @if (Auth::user()->is_favorites($micropost->id))
        {{-- unfavoriteボタンのフォーム --}}
        <form method="POST" action="{{ route('favorites.unfavorite', $micropost->id) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-green-400 hover:bg-green-300 text-white rounded px-4 py-2" 
                onclick="return confirm('id = {{ $micropost->id }} のお気に入りを外します。よろしいですか？')">Unfavorite</button>
        </form>
    @else
        {{-- favoriteボタンのフォーム --}}
        <form method="POST" action="{{ route('favorites.favorite', $micropost->id) }}">
            @csrf
            <button type="submit" class="btn normal-case">Favorite</button>
        </form>
    @endif
@endif