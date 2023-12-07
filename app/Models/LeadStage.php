<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadStage extends Model
{
    protected $fillable = [
        'name',
        'pipeline_id',
        'created_by',
        'order',
    ];

    public function lead()
    {
        $start = 0;
        $num_results_on_page = 25;
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = ($page - 1) * $num_results_on_page;
        }else{
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
        }

        $lead_created_by = [];
        if(\Auth::user()->can('view all leads')){
            return Lead::select('leads.*')->where('leads.stage_id', '=', $this->id)->orderBy('leads.order')->orderBy('leads.id', 'DESC')->skip($start)->take($num_results_on_page)->get();
        }else if(\Auth::user()->type=='company'){
            $lead_created_by = User::select(['users.id', 'users.name'])->join('roles', 'roles.name', '=', 'users.type')
                    ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
                    ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    ->where(['users.created_by' => \auth::id(), 'permissions.name' => 'create lead'])
                    ->groupBy('users.id')
                    ->pluck('id')
                    ->toArray();
                    $lead_created_by[] = \Auth::user()->id;
                    return Lead::select('leads.*')->whereIn('leads.created_by', $lead_created_by)->where('leads.stage_id', '=', $this->id)->orderBy('leads.order')->orderBy('leads.id', 'DESC')->skip($start)->take($num_results_on_page)->get();
        }else{
            $lead_created_by[] = \Auth::user()->created_by;
            $lead_created_by[] = \Auth::user()->id;;
            return Lead::select('leads.*')->join('user_leads', 'user_leads.lead_id', '=', 'leads.id')->whereIn('user_leads.user_id', $lead_created_by)->where('leads.stage_id', '=', $this->id)->orderBy('leads.order')->orderBy('leads.id', 'DESC')->skip($start)->take($num_results_on_page)->get();
        }
    }

    public function lead_count(){
        if(\Auth::user()->can('view all leads')){
            return Lead::select('leads.*')->where('leads.stage_id', '=', $this->id)->count();
        }else if(\Auth::user()->type=='company'){
            $lead_created_by = User::select(['users.id', 'users.name'])->join('roles', 'roles.name', '=', 'users.type')
                    ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
                    ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    ->where(['users.created_by' => \auth::id(), 'permissions.name' => 'create lead'])
                    ->groupBy('users.id')
                    ->pluck('id')
                    ->toArray();
                    $lead_created_by[] = \Auth::user()->id;
                    return Lead::select('leads.*')->whereIn('leads.created_by', $lead_created_by)->where('leads.stage_id', '=', $this->id)->count();
        }else{
            $lead_created_by[] = \Auth::user()->created_by;
            $lead_created_by[] = \Auth::user()->id;;
            return Lead::select('leads.*')->join('user_leads', 'user_leads.lead_id', '=', 'leads.id')->whereIn('user_leads.user_id', $lead_created_by)->where('leads.stage_id', '=', $this->id)->count();
        }
    }
}
