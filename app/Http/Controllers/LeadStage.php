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
        $num_results_on_page = env("RESULTS_ON_PAGE");
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $num_of_result_per_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = ($page - 1) * $num_results_on_page;
        } else {
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
        }


        $companies = FiltersBrands();
        $brand_ids = array_keys($companies);

        $leads_query = Lead::select('leads.*')->join('lead_stages', 'leads.stage_id', '=', 'lead_stages.id')->join('users', 'users.id', '=', 'leads.brand_id')->join('branches', 'branches.id', '=', 'leads.branch_id');
        $leads_query->where('leads.stage_id', $this->id);
        if(\Auth::user()->type == 'super admin'  || \Auth::user()->type == 'Admin Team' || \Auth::user()->can('level 1')){

        }else if(\Auth::user()->type == 'company'){
            $leads_query->where('leads.brand_id', \Auth::user()->id);
        }else if(\Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager' || \Auth::user()->can('level 2')){
            $leads_query->whereIn('leads.brand_id', $brand_ids);
        }else if(\Auth::user()->type == 'Region Manager' || \Auth::user()->can('level 3') && !empty(\Auth::user()->region_id)){
            $leads_query->where('leads.region_id', \Auth::user()->region_id);
        }else if(\Auth::user()->type == 'Branch Manager' || \Auth::user()->type == 'Admissions Officer' || \Auth::user()->type == 'Admissions Manager' || \Auth::user()->type == 'Marketing Officer' || \Auth::user()->can('level 4') && !empty(\Auth::user()->branch_id)){
            $leads_query->where('leads.branch_id', \Auth::user()->branch_id);
        }else{
            $leads_query->where('leads.user_id', \Auth::user()->id);
        }

        //if list global search
        if ( isset($_GET['search']) && !empty($_GET['search'])) {
            $g_search = $_GET['search'];
            $leads_query->Where('leads.name', 'like', '%' . $g_search . '%');
            $leads_query->orWhere('users.name', 'like', '%' . $g_search . '%');
            $leads_query->orWhere('branches.name', 'like', '%' . $g_search . '%');
            $leads_query->orWhere('leads.email', 'like', '%' . $g_search . '%');
            $leads_query->orWhere('leads.phone', 'like', '%' . $g_search . '%');
        }

        $leads_query->whereNotIn('lead_stages.name', ['Unqualified', 'Junk Lead']);
        $leads_query->where('leads.is_converted', 0);
        $leads = $leads_query->clone()->groupBy('leads.id')->orderBy('leads.created_at', 'desc')->skip($start)->take($num_results_on_page)->get();
        return $leads;
    }

    public function lead_count(){
        $start = 0;
        $num_results_on_page = env("RESULTS_ON_PAGE");
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $num_of_result_per_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = ($page - 1) * $num_results_on_page;
        } else {
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
        }


        $companies = FiltersBrands();
        $brand_ids = array_keys($companies);

        $leads_query = Lead::select('leads.*')->join('lead_stages', 'leads.stage_id', '=', 'lead_stages.id')->join('users', 'users.id', '=', 'leads.brand_id')->join('branches', 'branches.id', '=', 'leads.branch_id');
        $leads_query->where('leads.stage_id', $this->id);
        if(\Auth::user()->type == 'super admin'  || \Auth::user()->type == 'Admin Team' || \Auth::user()->can('level 1')){

        }else if(\Auth::user()->type == 'company'){
            $leads_query->where('leads.brand_id', \Auth::user()->id);
        }else if(\Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager' || \Auth::user()->can('level 2')){
            $leads_query->whereIn('leads.brand_id', $brand_ids);
        }else if(\Auth::user()->type == 'Region Manager' || \Auth::user()->can('level 3') && !empty(\Auth::user()->region_id)){
            $leads_query->where('leads.region_id', \Auth::user()->region_id);
        }else if(\Auth::user()->type == 'Branch Manager' || \Auth::user()->type == 'Admissions Officer' || \Auth::user()->type == 'Admissions Manager' || \Auth::user()->type == 'Marketing Officer' || \Auth::user()->can('level 4') && !empty(\Auth::user()->branch_id)){
            $leads_query->where('leads.branch_id', \Auth::user()->branch_id);
        }else{
            $leads_query->where('leads.user_id', \Auth::user()->id);
        }

        //if list global search
        if ( isset($_GET['search']) && !empty($_GET['search'])) {
            $g_search = $_GET['search'];
            $leads_query->Where('leads.name', 'like', '%' . $g_search . '%');
            $leads_query->orWhere('users.name', 'like', '%' . $g_search . '%');
            $leads_query->orWhere('branches.name', 'like', '%' . $g_search . '%');
            $leads_query->orWhere('leads.email', 'like', '%' . $g_search . '%');
            $leads_query->orWhere('leads.phone', 'like', '%' . $g_search . '%');
        }

        $leads_query->whereNotIn('lead_stages.name', ['Unqualified', 'Junk Lead']);
        $leads_query->where('leads.is_converted', 0);
        $leads = $leads_query->clone()->groupBy('leads.id')->count();
        return $leads;
    }
}
