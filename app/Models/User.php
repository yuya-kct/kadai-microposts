<?php

namespace App\Models;

//メール認証時にはコメントアウトを消す
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Micropost;

class User extends Authenticatable //implements MustverifyEmail <-メール認証時にコメントアウトを消す
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * このユーザーが所有する投稿。（ Micropostモデルとの関係を定義）
     */
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }

    /**
     * このユーザーに関係するモデルの件数をロードする。
     */
    public function loadRelationshipCounts()
    {
        $this->loadCount(['microposts', 'followings', 'followers', 'favorites']);
    }

    /**
     * このユーザーがフォロー中のユーザー。（Userモデルとの関係を定義）
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    /**
     * このユーザーをフォロー中のユーザー。（Userモデルとの関係を定義）
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }

    /**
     * $userIdで指定されたユーザーをフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function follow(int $userId)
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        if ($exist || $its_me) {
            return false;
        } else {
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    /**
     * $userIdで指定されたユーザーをアンフォローする。
     * 
     * @param  int $usreId
     * @return bool
     */
    public function unfollow(int $userId)
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        if ($exist && !$its_me) {
            $this->followings()->detach($userId);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 指定された$userIdのユーザーをこのユーザーがフォロー中であるか調べる。フォロー中ならtrueを返す。
     * 
     * @param  int $userId
     * @return bool
     */
    public function is_following(int $userId)
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }

    /**
     * このユーザーとフォロー中ユーザーの投稿に絞り込む。
     */
    public function feed_microposts()
    {
        // このユーザーがフォロー中のユーザーのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザーのidもその配列に追加
        $userIds[] = $this->id;

        // それらのユーザーが所有する投稿に絞り込む
        $query = Micropost::whereIn('user_id', $userIds)
                        ->whereNull('community_id');
        // デバッグ: SQLクエリを確認
        \Log::info('Feed microposts SQL:', ['sql' => $query->toSql()]);
    
        return $query;
    }

    /**
     * このユーザーがお気に入り中のmicropost。（Micropostモデルとの関係を定義）
     */
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();
    }
    
    /**
     * $micropostIdで指定された投稿をお気に入りする。
     *
     * @param  int  $micropostId
     * @return bool
     */
    public function favorite(int $micropostId)
    {
        $exist = $this->is_favorites($micropostId);
        $micropost = Micropost::find($micropostId);
        $its_me = $micropost->user_id == $this->id;
        
        if ($exist || $its_me) {
            return false;
        } else {
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
    
    /**
     * $micropostIdで指定された投稿をお気に入りから外す。
     * 
     * @param  int $micropostId
     * @return bool
     */
    public function unfavorite(int $micropostId)
    {
        $exist = $this->is_favorites($micropostId);
        $its_me = $micropost->user_id == $this->id;
        
        if ($exist && !$its_me) {
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 指定された$micropostIdの投稿をこのユーザーがお気に入り中であるか調べる。お気に入り中ならtrueを返す。
     * 
     * @param  int $micropostId
     * @return bool
     */
    public function is_favorites(int $micropostId)
    {
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_members')
                ->withPivot('role', 'joined_at')
                ->withTimestamps();
    }

    public function ownedCommunities()
    {
        return $this->hasMany(Community::class, 'owner_id');
    }
}
