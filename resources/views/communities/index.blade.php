{{-- resources/views/communities/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">マイコミュニティ</h1>
        <div class="flex gap-3">
            <a href="{{ route('communities.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                コミュニティ作成
            </a>
            <button class="btn btn-outline" onclick="join_modal.showModal()">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                招待コードで参加
            </button>
        </div>
    </div>

    @if($myCommunities->count() > 0)
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($myCommunities as $community)
                <div class="card bg-white shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="card-body">
                        <div class="flex items-start justify-between mb-3">
                            <h2 class="card-title text-lg">{{ $community->name }}</h2>
                            @if($community->pivot->role === 'owner')
                                <span class="badge badge-primary badge-sm">オーナー</span>
                            @elseif($community->pivot->role === 'admin')
                                <span class="badge badge-secondary badge-sm">管理者</span>
                            @endif
                        </div>
                        
                        @if($community->description)
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $community->description }}</p>
                        @endif
                        
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $community->membersCount() }}/{{ $community->max_members }}
                            </span>
                            <span>{{ \Carbon\Carbon::parse($community->pivot->joined_at)->format('Y/m/d') }} 参加</span>
                        </div>
                        
                        <div class="card-actions justify-end">
                            <a href="{{ route('communities.show', $community) }}" class="btn btn-primary btn-sm">
                                コミュニティを見る
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">コミュニティがありません</h3>
            <p class="text-gray-500 mb-6">新しいコミュニティを作成するか、招待コードで参加してみましょう。</p>
            <div class="flex justify-center gap-3">
                <a href="{{ route('communities.create') }}" class="btn btn-primary">コミュニティ作成</a>
                <button class="btn btn-outline" onclick="join_modal.showModal()">招待コードで参加</button>
            </div>
        </div>
    @endif
</div>

{{-- 招待コードで参加モーダル --}}
<dialog id="join_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">招待コードで参加</h3>
        <form method="POST" action="{{ route('communities.join') }}">
            @csrf
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">招待コード</span>
                </label>
                <input type="text" name="invite_code" class="input input-bordered" placeholder="8文字の招待コードを入力" required>
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="join_modal.close()">キャンセル</button>
                <button type="submit" class="btn btn-primary">参加する</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
@endsection