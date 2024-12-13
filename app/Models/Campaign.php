<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    // กำหนดฟิลด์ที่อนุญาตให้ทำ Mass Assignment
    protected $fillable = [
        'status',
        'categoriesID',
        'name',
        'description',
        'price',
        'stock',
        'details',
        'campaign_img',
    ];
    
}
