{{-- resources/views/communities/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    {{-- コミュニティヘッダー --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $community->name }}</h1>
                @if($community->description)
                    <p class="text-gray-600 mb-3">{{ $community->description }}</p>
                @endif
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        {{ $community->membersCount() }}/{{ $community->max_members }} メンバー
                    </span>
                    <span>オーナー: {{ $community->owner->name }}</span>
                </div>
            </div>
            <div class="flex gap-2">
                @if($community->canManage(Auth::id()))
                    <button class="btn btn-outline btn-sm" onclick="invite_modal.showModal()">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        メンバー招待
                    </button>
                @endif
                @if(!$community->isOwner(Auth::id()))
                    <form method="POST" action="{{ route('communities.leave', $community) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-error btn-outline btn-sm" 
                                onclick="return confirm('このコミュニティを退出しますか？')">
                            退出
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- 投稿フォーム --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="POST" action="{{ route('microposts.store') }}">
            @csrf
            <input type="hidden" name="community_id" value="{{ $community->id }}">
            <div class="flex gap-3">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full overflow-hidden">
                        <img src="{{ Gravatar::get(Auth::user()->email) }}" alt="" class="w-full h-full object-cover" />
                    </div>
                </div>
                <div class="flex-1">
                    <textarea name="content" 
                            class="textarea textarea-bordered w-full @error('content') textarea-error @enderror" 
                            rows="3" 
                            placeholder="コミュニティで何を共有しますか？">{{ old('content') }}</textarea>
                    @error('content')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                    <div class="flex justify-end mt-3">
                        <button type="submit" class="btn btn-primary">投稿</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- 投稿一覧 --}}
    <div class="bg-white rounded-lg shadow-md">
        @if($microposts->count() > 0)
            @include('microposts.microposts', ['microposts' => $microposts])
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">まだ投稿がありません</h3>
                <p class="text-gray-500">最初の投稿をしてコミュニティを盛り上げましょう！</p>
            </div>
        @endif
    </div>
</div>

{{-- 招待モーダル --}}
@if($community->canManage(Auth::id()))
<dialog id="invite_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">メンバーを招待</h3>
        <p class="text-gray-600 mb-4">以下の招待コードを共有してメンバーを招待できます。</p>
        
        <div class="form-control mb-4">
            <label class="label">
                <span class="label-text">招待コード</span>
            </label>
            <div class="flex gap-2">
                <input type="text" value="{{ $community->invite_code }}" 
                        class="input input-bordered flex-1" readonly id="invite-code">
                <button type="button" class="btn btn-outline" onclick="copyInviteCode()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="alert alert-info">
            <svg class="stroke-current shrink-0 w-6 h-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm">このコードを知っている人は誰でもコミュニティに参加できます。信頼できる人にのみ共有してください。</span>
        </div>
        
        <div class="modal-action">
            <button type="button" class="btn" onclick="invite_modal.close()">閉じる</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
function copyInviteCode() {
    const inviteCode = document.getElementById('invite-code');
    inviteCode.select();
    document.execCommand('copy');
    
    // 成功メッセージを表示（簡易版）
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
    setTimeout(() => {
        btn.innerHTML = originalText;
    }, 2000);
}
</script>
@endif
@endsection