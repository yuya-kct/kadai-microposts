<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MicropostStamp extends Model
{
    use HasFactory;

    protected $fillable = ['micropost_id', 'stamp_id', 'user_id'];

    // PostgreSQL用にテーブル名を明示的に指定
    protected $table = 'micropost_stamps';
    
    // リレーション定義を追加
    public function micropost()
    {
        return $this->belongsTo(Micropost::class);
    }
    
    public function stamp()
    {
        return $this->belongsTo(Stamp::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}