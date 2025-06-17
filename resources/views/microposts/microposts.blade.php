<div class="mt-4">
    @if (isset($microposts))
        <div class="divide-y divide-gray-200">
            @foreach ($microposts as $micropost)
                <div class="px-4 py-3 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex gap-3">
                        {{-- アバター --}}
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full overflow-hidden">
                                <img src="{{ Gravatar::get($micropost->user->email) }}" alt="" class="w-full h-full object-cover" />
                            </div>
                        </div>
                        
                        {{-- メインコンテンツ --}}
                        <div class="flex-1 min-w-0">
                            {{-- ヘッダー情報 --}}
                            <div class="flex items-center gap-2 mb-1">
                                <a class="font-bold text-gray-900 hover:underline" href="{{ route('users.show', $micropost->user->id) }}">
                                    {{ $micropost->user->name }}
                                </a>
                                <span class="text-gray-500 text-sm">·</span>
                                <span class="text-gray-500 text-sm">{{ $micropost->created_at->diffForHumans() }}</span>
                            </div>
                            
                            {{-- 投稿内容 --}}
                            <div class="mb-3">
                                <p class="text-gray-900 text-[15px] leading-5 whitespace-pre-wrap">{!! nl2br(e($micropost->content)) !!}</p>
                            </div>
                            
                            {{-- スタンプ表示エリア --}}
                            <div class="mb-3">
                                @php
                                    $userStamp = $micropost->stamps()->where('user_id', Auth::id())->first();
                                @endphp
                                
                                {{-- 自分がスタンプを押している場合 --}}
                                @if ($userStamp)
                                    <div class="flex items-center gap-2 mb-2">
                                        <img src="{{ asset($userStamp->image_path) }}" alt="{{ $userStamp->name }}" class="w-6 h-6">
                                        <form action="{{ route('microposts.stamps.destroy', $micropost->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-blue-500 hover:text-blue-700 hover:underline">
                                                取り消し
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                
                                {{-- 自分の投稿の場合：押されたスタンプを表示 --}}
                                @if (Auth::id() == $micropost->user_id && $micropost->stamps()->count() > 0)
                                    <div class="flex items-center gap-1 mb-2">
                                        @foreach ($micropost->stamps as $stamp)
                                            <img src="{{ asset($stamp->image_path) }}" alt="{{ $stamp->name }}" class="w-6 h-6">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            
                            {{-- アクションボタンエリア --}}
                            <div class="flex items-center justify-between max-w-md">
                                {{-- お気に入りボタン --}}
                                <div class="flex items-center">
                                    @include('favorite.favorite_button', ['micropost' => $micropost])
                                </div>
                                
                                {{-- スタンプボタン --}}
                                @if (!$userStamp && Auth::id() != $micropost->user_id)
                                    <div class="flex items-center">
                                        <div class="dropdown dropdown-top">
                                            <label tabindex="0" class="btn btn-ghost btn-sm rounded-full p-2 hover:bg-gray-100">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.01M15 10h1.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </label>
                                            <div tabindex="0" class="dropdown-content menu p-2 shadow-lg bg-white rounded-box w-52 border">
                                                <form action="{{ route('microposts.stamps', $micropost->id) }}" method="POST">
                                                    @csrf
                                                    <div class="grid grid-cols-3 gap-2 p-2">
                                                        @foreach (\App\Models\Stamp::all() as $stamp)
                                                            <button type="submit" name="stamp_id" value="{{ $stamp->id }}" 
                                                                    class="p-2 hover:bg-gray-100 rounded flex flex-col items-center">
                                                                <img src="{{ asset($stamp->image_path) }}" alt="{{ $stamp->name }}" class="w-8 h-8">
                                                                <span class="text-xs mt-1">{{ $stamp->name }}</span>
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                {{-- 削除ボタン（自分の投稿のみ） --}}
                                @if (Auth::id() == $micropost->user_id)
                                    <div class="flex items-center">
                                        <div class="dropdown dropdown-top dropdown-end">
                                            <label tabindex="0" class="btn btn-ghost btn-sm rounded-full p-2 hover:bg-gray-100">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zM12 13a1 1 0 110-2 1 1 0 010 2zM12 20a1 1 0 110-2 1 1 0 010 2z"></path>
                                                </svg>
                                            </label>
                                            <div tabindex="0" class="dropdown-content menu p-2 shadow-lg bg-white rounded-box w-40 border">
                                                <form method="POST" action="{{ route('microposts.destroy', $micropost->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:bg-red-50 px-3 py-2 rounded text-sm w-full text-left"
                                                            onclick="return confirm('この投稿を削除しますか？')">
                                                        削除
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- エラーメッセージ --}}
                            @error('stamp_id')
                                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- ページネーション --}}
        <div class="mt-6">
            {{ $microposts->links() }}
        </div>
    @endif
</div>