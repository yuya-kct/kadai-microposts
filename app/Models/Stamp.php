<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stamp extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image_path'];

    public function microposts()
    {
        return $this->belongsToMany(Micropost::class, 'micropost_stamps')
                    ->withTimestamps()
                    ->withPivot('user_id');
    }
}