<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealApplication extends Model
{
    use HasFactory;

    protected $fillable = ['application_key','deal_id', 'university_id', 'course', 'stage_id', 'external_app_id', 'name', 'intake', 'created_by'];

    public function getUniversity($id)
    {
        return University::where('id', $id)->first();
    }
}
