@if (Auth::id() == $user->id)
    <div class="bg-white border-b border-gray-200 p-4">
        <form method="POST" action="{{ route('microposts.store') }}">
            @csrf
            <div class="flex gap-3">
                {{-- プロフィール画像 --}}
                <div class="flex-shrink-0">
                    <img src="{{ Gravatar::get($user->email, ['size' => 100]) }}" 
                        alt="{{ $user->name }}" 
                        class="w-12 h-12 rounded-full object-cover">
                </div>
                
                {{-- 投稿入力エリア --}}
                <div class="flex-1">
                    <textarea name="content" 
                            rows="3" 
                            class="w-full text-xl placeholder-gray-500 border-none resize-none focus:outline-none" 
                            placeholder="文字を入力"
                            required></textarea>
                    
                    {{-- 投稿オプションとボタン --}}
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                        
                        {{-- 投稿ボタン --}}
                        <button type="submit" 
                                class="px-6 py-1.5 bg-blue-500 text-white rounded-full font-bold text-sm hover:bg-blue-600 transition-colors duration-200 disabled:opacity-50">
                            投稿
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endif