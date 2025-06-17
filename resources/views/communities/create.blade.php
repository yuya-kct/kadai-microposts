{{-- resources/views/communities/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">新しいコミュニティを作成</h1>
        <p class="text-gray-600">閉じたコミュニティを作成して、メンバー同士で交流しましょう。</p>
    </div>

    <div class="card bg-white shadow-md">
        <div class="card-body">
            <form method="POST" action="{{ route('communities.store') }}">
                @csrf
                
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium">コミュニティ名 <span class="text-red-500">*</span></span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                            class="input input-bordered @error('name') input-error @enderror" 
                            placeholder="例: 読書好きの集い" required>
                    @error('name')
                        <label class="label">
                            <span class="label-text-alt text-red-500">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium">説明</span>
                    </label>
                    <textarea name="description" 
                              class="textarea textarea-bordered @error('description') textarea-error @enderror" 
                              rows="4" 
                              placeholder="コミュニティの目的や活動内容を説明してください">{{ old('description') }}</textarea>
                    @error('description')
                        <label class="label">
                            <span class="label-text-alt text-red-500">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-medium">最大メンバー数 <span class="text-red-500">*</span></span>
                    </label>
                    <input type="number" name="max_members" value="{{ old('max_members', 50) }}" 
                           min="2" max="1000"
                           class="input input-bordered @error('max_members') input-error @enderror" required>
                    <label class="label">
                        <span class="label-text-alt text-gray-500">2〜1000人の範囲で設定してください</span>
                    </label>
                    @error('max_members')
                        <label class="label">
                            <span class="label-text-alt text-red-500">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="alert alert-info mb-6">
                    <svg class="stroke-current shrink-0 w-6 h-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="font-bold">コミュニティについて</h3>
                        <div class="text-sm">
                            • 作成されたコミュニティは非公開になります<br>
                            • 招待コードを知っている人のみが参加できます<br>
                            • あなたがオーナーとして管理権限を持ちます
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('communities.index') }}" class="btn btn-outline">キャンセル</a>
                    <button type="submit" class="btn btn-primary">コミュニティを作成</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection