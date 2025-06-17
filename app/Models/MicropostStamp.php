<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MicropostStamp extends Model
{
    use HasFactory;

    protected $fillable = ['micropost_id', 'stamp_id', 'user_id'];
}