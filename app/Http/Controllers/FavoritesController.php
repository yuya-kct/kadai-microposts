<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    /**
     * 投稿をお気に入りするアクション。
     *
     * @param  $id  投稿のid
     * @return \Illuminate\Http\Response
     */
    public function store(string $id)
    {
        // 認証済みユーザー（閲覧者）が、 idの投稿をお気に入りする
        \Auth::user()->favorite(intval($id));
        // 前のURLへリダイレクトさせる
        return back();
    }

    /**
     * 投稿をお気に入りから外すアクション。
     *
     * @param  $id  投稿のid
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        // 認証済みユーザー（閲覧者）が、 idの投稿をお気に入りから外す
        \Auth::user()->unfavorite(intval($id));
        // 前のURLへリダイレクトさせる
        return back();
    }
}
