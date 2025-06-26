<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stamp extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image_path'];

    // PostgreSQL用にテーブル名を明示的に指定
    protected $table = 'stamps';

    public function microposts()
    {
        return $this->belongsToMany(Micropost::class, 'micropost_stamps')
                    ->withTimestamps()
                    ->withPivot('user_id');
    }
}