<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name','created_by'
    ];

    public function branch_manager()
    {
        return $this->hasMany('App\Models\User', 'id');
    }

    public function region()
    {
        return $this->hasMany('App\Models\Region', 'id');
    }
}
?>
