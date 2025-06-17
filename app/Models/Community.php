<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'is_private',
        'invite_code',
        'owner_id',
        'max_members'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($community) {
            $community->invite_code = Str::random(8);
        });
    }

    // リレーション
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'community_members')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps()
                    ->withCasts(['joined_at' => 'datetime']);
    }

    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }

    // ヘルパーメソッド
    public function isMember($userId)
    {
        // nullチェックを追加
        if (!$userId) {
            return false;
        }
        return $this->members()->where('user_id', $userId)->exists();
    }

    public function isOwner($userId)
    {
        return $this->owner_id === $userId;
    }

    public function isAdmin($userId)
    {
        return $this->members()
                    ->where('user_id', $userId)
                    ->wherePivot('role', 'admin')
                    ->exists();
    }

    public function canManage($userId)
    {
        return $this->isOwner($userId) || $this->isAdmin($userId);
    }

    public function membersCount()
    {
        return $this->members()->count();
    }

    public function isFull()
    {
        return $this->membersCount() >= $this->max_members;
    }
}