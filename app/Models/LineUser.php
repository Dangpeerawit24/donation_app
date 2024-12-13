<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'display_name',
        'picture_url',
        'status_message',
    ];
}
