<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'branch_id',
        'region_id',
        'brand_id',
        'description',
        'created_by',
    ];
}
