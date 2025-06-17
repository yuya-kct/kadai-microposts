<?php
// app/Http/Controllers/CommunityController.php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index()
    {
        $myCommunities = Auth::user()->communities()->latest()->get();
        return view('communities.index', compact('myCommunities'));
    }

    public function show(Community $community)
    {
        // メンバーでない場合はアクセス拒否
        if (!$community->isMember(Auth::id())) {
            abort(403, 'このコミュニティにアクセスする権限がありません。');
        }

        $microposts = $community->microposts()
                                ->with(['user', 'favorites', 'stamps'])
                                ->latest()
                                ->paginate(20);

        return view('communities.show', compact('community', 'microposts'));
    }

    public function create()
    {
        return view('communities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'max_members' => 'required|integer|min:2|max:1000',
        ]);

        $community = Community::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => Auth::id(),
            'max_members' => $request->max_members,
        ]);

        // オーナーをメンバーとして追加
        $community->members()->attach(Auth::id(), [
            'role' => 'owner',
            'joined_at' => now()
        ]);

        return redirect()->route('communities.show', $community)
                        ->with('success', 'コミュニティを作成しました！');
    }

    public function join(Request $request)
    {
        $request->validate([
            'invite_code' => 'required|string|exists:communities,invite_code'
        ]);

        $community = Community::where('invite_code', $request->invite_code)->first();

        if ($community->isMember(Auth::id())) {
            return back()->with('error', '既にこのコミュニティのメンバーです。');
        }

        if ($community->isFull()) {
            return back()->with('error', 'このコミュニティは満員です。');
        }

        $community->members()->attach(Auth::id(), [
            'role' => 'member',
            'joined_at' => now()
        ]);

        return redirect()->route('communities.show', $community)
                        ->with('success', 'コミュニティに参加しました！');
    }

    public function leave(Community $community)
    {
        if (!$community->isMember(Auth::id())) {
            return back()->with('error', 'このコミュニティのメンバーではありません。');
        }

        if ($community->isOwner(Auth::id())) {
            return back()->with('error', 'オーナーはコミュニティを退出できません。');
        }

        $community->members()->detach(Auth::id());

        return redirect()->route('communities.index')
                        ->with('success', 'コミュニティを退出しました。');
    }
}