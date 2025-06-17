<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    {{-- ヘッダー背景 --}}
    <div class="h-32 bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500"></div>
    
    {{-- プロフィール情報 --}}
    <div class="px-6 pb-6">
        {{-- プロフィール画像 --}}
        <div class="flex justify-between items-start -mt-16 mb-4">
            <div class="relative">
                <img src="{{ Gravatar::get($user->email, ['size' => 300]) }}" 
                    alt="{{ $user->name }}" 
                    class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-white object-cover">
            </div>
            
            {{-- フォローボタン --}}
            <div class="mt-16">
                @include('user_follow.follow_button')
            </div>
        </div>
        
        {{-- ユーザー名とメール --}}
        <div class="mb-4">
            <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>
            <p class="text-gray-500">{{ $user->email }}</p>
        </div>
        
        {{-- 統計情報 --}}
        <div class="flex space-x-6">
            <a href="{{ route('users.show', $user->id) }}" class="hover:underline">
                <span class="font-bold text-gray-900">{{ $user->microposts_count ?? 0 }}</span>
                <span class="text-gray-500 text-sm ml-1">投稿</span>
            </a>
            <a href="{{ route('users.followings', $user->id) }}" class="hover:underline">
                <span class="font-bold text-gray-900">{{ $user->followings_count ?? 0 }}</span>
                <span class="text-gray-500 text-sm ml-1">フォロー中</span>
            </a>
            <a href="{{ route('users.followers', $user->id) }}" class="hover:underline">
                <span class="font-bold text-gray-900">{{ $user->followers_count ?? 0 }}</span>
                <span class="text-gray-500 text-sm ml-1">フォロワー</span>
            </a>
            <a href="{{ route('users.favorites', $user->id) }}" class="hover:underline">
                <span class="font-bold text-gray-900">{{ $user->favorites_count ?? 0 }}</span>
                <span class="text-gray-500 text-sm ml-1">お気に入り</span>
            </a>
        </div>
    </div>
</div>