<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Micropost;
use Illuminate\Support\Facades\Auth;

class MicropostsController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザーを取得
            $user = \Auth::user();
            // ユーザーの投稿の一覧を作成日時の降順で取得
            // （後のChapterで他ユーザーの投稿も取得するように変更しますが、現時点ではこのユーザーの投稿のみ取得します）
            
            // フォロー中のユーザーIDを取得
            $userIds = $user->followings()->pluck('users.id')->toArray();
            $userIds[] = $user->id;
            
            // タイムライン用投稿を取得（コミュニティ投稿は除外）
            $microposts = Micropost::whereIn('user_id', $userIds)
                                    ->whereNull('community_id') // コミュニティ投稿を除外
                                    ->with('stamps')
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(10);

            $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
        }

        // dashboardビューでそれらを表示
        return view('dashboard', $data);
    }

    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'content' => 'required|max:255',
            'community_id' => 'nullable|exists:communities,id'
        ]);

        // デバッグ: リクエストの内容を確認
        \Log::info('Micropost store request:', [
            'content' => $request->content,
            'community_id' => $request->community_id,
            'user_id' => Auth::id()
        ]);

        // 認証済みユーザー（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $micropost = Auth::user()->microposts()->create([
            'content' => $request->content,
            'community_id' => $request->community_id
        ]);

        // デバッグ: 作成された投稿を確認
        \Log::info('Created micropost:', [
            'id' => $micropost->id,
            'content' => $micropost->content,
            'community_id' => $micropost->community_id,
            'user_id' => $micropost->user_id
        ]);


        // コミュニティ投稿の場合はコミュニティページにリダイレクト
        if ($request->community_id) {
            return redirect()->route('communities.show', $request->community_id)
                        ->with('success', '投稿しました！');
        }

        // 前のURLへリダイレクトさせる
        return back();
    }

    public function destroy(string $id)
    {
        // idの値で投稿を検索して取得
        $micropost = Micropost::findOrFail($id);

        // 認証済みユーザー（閲覧者）がその投稿の所有者である場合は投稿を削除
        if (\Auth::id() === $micropost->user_id) {
            $micropost->delete();
            return back()
                ->with('success','Delete Successful');
        }

        // 前のURLへリダイレクトさせる
        return back()
            ->with('Delete Failed');
    }
}
