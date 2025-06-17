<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    use HasFactory;

    protected $fillable = ['content'];

    /**
     * この投稿を所有するユーザー。（ Userモデルとの関係を定義）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites_users()
    {
        return $this->belongsToMany(User::class, 'favorites', 'micropost_id', 'user_id')->withTimestamps();
    }

    // スタンプとのリレーションシップ
    public function stamps()
    {
        return $this->belongsToMany(Stamp::class, 'micropost_stamps')
                    ->withTimestamps()
                    ->withPivot('user_id');
    }

    /**
     * この投稿をお気に入りしているユーザー達
     */
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites', 'micropost_id', 'user_id')->withTimestamps();
    }

    /**
     * お気に入り数を取得
     */
    public function favorites_count()
    {
        return $this->favorites()->count();
    }
}
