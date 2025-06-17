<?php

namespace App\Http\Controllers;

use App\Models\Stamp;
use App\Models\Micropost;
use App\Models\MicropostStamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //追加

class StampsController extends Controller
{
    // スタンプ一覧を表示
    public function index()
    {
        $stamps = Stamp::all();
        return view('stamps.index', compact('stamps'));
    }

    // 特定の投稿にスタンプを付与
    //dd($request->all());
    public function store(Request $request, Micropost $micropost)
    {
        // バリデーションルールを追加
        $request->validate([
            'stamp_id' => 'required|exists:stamps,id',
        ]);
        
        $stampId = $request->input('stamp_id');
        $userId = \Auth::id();

        // 自分の投稿にはスタンプを押せないようにする
        if ($micropost->user_id === $userId) { //追加
            return back()->with('error', '自分の投稿にはスタンプを押せません。'); //追加
        }

        // 既にスタンプが付与されているか確認
        $existingStamp = MicropostStamp::where('micropost_id', $micropost->id)
                                        //->where('stamp_id', $stampId)　//コメントアウト化
                                        ->where('user_id', $userId)
                                        ->first();

        /*if (!$existingStamp) {
            // スタンプを付与
            $micropostStamp = new MicropostStamp();
            $micropostStamp->micropost_id = $micropost->id;
            $micropostStamp->stamp_id = $stampId;
            $micropostStamp->user_id = $userId;
            $micropostStamp->save();

            return back()->with('success', 'スタンプを付与しました。');
        } else {
            return back()->with('error', '既にスタンプが付与されています。');
        }
        */
        if ($existingStamp) {
            return back()->with('error', '既にスタンプが付与されています。');
        }
        // 1種類のスタンプのみ1つだけ押せるようにする
        // 既にスタンプが付与されている場合は、削除する
        MicropostStamp::where('micropost_id', $micropost->id)
                    ->where('user_id', $userId)
                    ->delete();

        // スタンプを付与
        $micropostStamp = new MicropostStamp();
        $micropostStamp->micropost_id = $micropost->id;
        $micropostStamp->stamp_id = $stampId;
        $micropostStamp->user_id = $userId;
        $micropostStamp->save();

        return back()->with('success', 'スタンプを付与しました。');
        
    }

    // スタンプを削除
    public function destroy(Micropost $micropost, Stamp $stamp)
    {
        $userId = Auth::id();

        // 自分のスタンプを取り消す
        MicropostStamp::where('micropost_id', $micropost->id)
                    ->where('user_id', $userId)
                    ->delete();

        return back()->with('success', 'スタンプを取り消しました。');
    }
}