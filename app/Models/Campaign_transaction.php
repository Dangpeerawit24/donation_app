<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign_transaction extends Model
{
    protected $fillable = ['value', 'campaignsid', 'transactionID', 'lineName', 'status', 'form', 'details', 'campaignsname', 'wish'];
}
