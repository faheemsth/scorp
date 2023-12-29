<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedFilter extends Model
{
    protected $fillable = [
        'filter_name',
        'module',
        'name',
        'brand',
        'subject',
        'assign_to',
        'status',
        'due_date',
        'stage',
        'created_at',
        'university',
        'email',
        'country',
        'city',
        'phone',
        'note',
        't_user',
        'count',
        'created_by',
    ];
}
