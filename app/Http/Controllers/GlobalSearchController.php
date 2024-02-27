<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\DealApplication;
use App\Models\DealTask;
use App\Models\Lead;
use App\Models\LeadStage;
use App\Models\Source;
use App\Models\Stage;
use App\Models\Task;
use App\Models\Branch;
use App\Models\Region;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GlobalSearchController extends Controller
{
    //
    public function index(Request $request){
        $searchString = $request->search;
        $type = $request->global_search;
        $data = [];
        $data['users'] = User::pluck('name', 'id')->toArray();
        
       if($type == 'all'){
        $data['tasks'] = $this->getTasks($searchString);
        $data['universities'] = $this->getUniversities($searchString);
        $data['lead_stages'] = LeadStage::pluck('name', 'id')->toArray();
        $data['leads'] = $this->getLeads($searchString);
        $data['clients'] = $this->getContacts($searchString);
        $data['sources'] = Source::get()->pluck('name', 'id')->toArray();
        $data['deal_stages'] = Stage::pluck('name', 'id')->toArray();
        $data['deals'] = $this->getDeals($searchString);
        $data['universities_arr'] = University::get()->pluck('name', 'id')->toArray();
        $data['deal_stages'] = Stage::pluck('name', 'id')->toArray();
        $data['applications'] = $this->getApplications($searchString);
        $data['organizations'] = $this->getOrganizations($searchString);
        $data['brands'] = $this->getBrands($searchString);
        $data['regions'] = $this->getRegions($searchString);
        $data['branches'] = $this->getBranches($searchString);
        $data['employees'] = $this->getEmployees($searchString);
        
       }else if($type == 'tasks'){
           $data['tasks'] = $this->getTasks($searchString);
       }else if($type == 'universities'){
           $data['universities'] = $this->getUniversities($searchString);
       }else if($type == 'leads'){
          $data['lead_stages'] = LeadStage::pluck('name', 'id')->toArray();
          $data['leads'] = $this->getLeads($searchString);

       }else if($type == 'contacts'){
            $data['clients'] = $this->getContacts($searchString);
       }else if($type == 'admissions'){

        $data['sources'] = Source::get()->pluck('name', 'id')->toArray();
        $data['deal_stages'] = Stage::pluck('name', 'id')->toArray();
        $data['deals'] = $this->getDeals($searchString);

       }else if($type == 'applications'){
        
        $data['universities_arr'] = University::get()->pluck('name', 'id')->toArray();
        $data['deal_stages'] = Stage::pluck('name', 'id')->toArray();
        $data['applications'] = $this->getApplications($searchString);

       }else if($type == 'organizations'){
         $data['organizations'] = $this->getOrganizations($searchString);
       }

       return view('global_search.index', $data);
    }

    private function getTasks($search){

        $tasks = DealTask::select(['deal_tasks.*'])->join('users', 'users.id', '=', 'deal_tasks.assigned_to');

        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('level 1')) {
        } elseif(strtolower(\Auth::user()->type) == 'project manager' || \Auth::user()->can('level 2')){
            $all_created_emp = User::where(['created_by' => \Auth::user()->created_by, 'type' => 'employee'])->get()->pluck('id', 'id')->toArray();
            $all_created_emp[] = \Auth::user()->id;
            $all_created_emp[] = \Auth::user()->created_by;
            $tasks->whereIn('assigned_to', $all_created_emp);
        } elseif (\Auth::user()->can('create task')) {
            //fetch all created employees
            $all_created_emp = User::where(['created_by' => \Auth::user()->id, 'type' => 'employee'])->get()->pluck('id', 'id')->toArray();
            $all_created_emp[] = \Auth::user()->id;
            $tasks->whereIn('assigned_to', $all_created_emp);
        } else {
            $tasks->where('assigned_to', \Auth::user()->id);
            $tasks->orWhere('assigned_to', \Auth::user()->created_by);
        }

        $tasks->orWhere('deal_tasks.name', 'LIKE', "%$search%")
        ->orWhere('deal_tasks.assigned_type', 'LIKE', "%$search%")
        ->orWhere('users.name', 'LIKE', "%$search%");

        $tasks = $tasks->orderBy('created_at', 'DESC')->get();

        // $dealTasks = DealTask::select(['deal_tasks.*'])
        // ->join('users', 'deal_tasks.assigned_to', '=', 'users.id')
        // ->where('deal_tasks.name', 'LIKE', "%$search%")
        // ->orWhere('deal_tasks.assigned_type', 'LIKE', "%$search%")
        // ->orWhere('users.name', 'LIKE', "%$search%")
        // ->get();

        return $tasks;
    }

    private function getUniversities($search){
        $universities = University::where('name', 'LIKE', "%$search%")
        ->orWhere('country', 'LIKE', "%$search%")
        ->orWhere('city', 'LIKE', "%$search%")
        ->orWhere('phone', 'LIKE', "%$search%")
        ->orWhere('note', 'LIKE', "%$search%")
        ->get();

        return $universities;
    }

    private function getLeads($search){
        $usr = \Auth::user();

        $leads_query     = Lead::select('leads.*')->join('user_leads', 'user_leads.lead_id', '=', 'leads.id');

        //check if login user have to show all the deals
        if ($usr->can('view all leads')) {
           // $companies = User::get()->pluck('name', 'id');
        } else if (\auth::user()->type == 'company') {
            $users = $this->companyEmployees(\auth::user()->id);
            $users[$usr->id] = $usr->name;
           // $companies = $users;
            $lead_created_by = array_keys($users);
            $leads_query->whereIn('leads.created_by', $lead_created_by);
        } else if (strtolower(\auth::user()->type) == 'branch manager') {
            $leads_query->where('branch_id', \auth::user()->branch_id);
            //$companies = User::where('id', $usr->id)->get()->pluck('name', 'id');
            
        } else if(strtolower(\auth::user()->type) == 'marketing officer') {

            $users = $this->companyEmployees(\auth::user()->created_by);
            $users[$usr->id] = $usr->name;
            $users[$usr->created_by] = $usr->created_by;
           // $companies = $users;
            $lead_created_by = array_keys($users);

            $leads_query->whereIn('leads.created_by', $lead_created_by);
            
            //$companies = User::where('id', $usr->id)->get()->pluck('name', 'id');
        }else {
            $lead_created_by[] = \auth::user()->created_by;
            $lead_created_by[] = $usr->id;
            $leads_query->where('leads.user_id', \Auth::user()->id);
           // $companies = User::where('id', $usr->id)->get()->pluck('name', 'id');
        }

        $leads_query->join('users', 'leads.user_id', '=', 'users.id')
        ->join('lead_stages', 'lead_stages.id', '=', 'leads.stage_id')
        ->orwhere('leads.name', 'LIKE', "%$search%")
        ->orWhere('leads.email', 'LIKE', "%$search%")
        ->orWhere('leads.phone', 'LIKE', "%$search%")
        ->orWhere('lead_stages.name', 'LIKE', "%$search%")
        ->orWhere('users.name', 'LIKE', "%$search%");

       $leads = $leads_query->orderBy('leads.order')->orderBy('leads.id', 'DESC')->get();
        return $leads;
    }

    private function getContacts($search){
        $client_query = User::select(['users.*'])->join('client_deals', 'client_deals.client_id', 'users.id')->join('deals', 'deals.id', 'client_deals.deal_id');
        $user    = \Auth::user();

        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('level 1')) {

        }else if (\Auth::user()->type == 'company') {

            $users = $this->companyEmployees(\auth::id());
            $users[$user->id] = $user->name;
            $companies = $users;
            $client_query_created_by = array_keys($users);
            $client_query->whereIn('deals.created_by', $client_query_created_by);

        }else if (strtolower(\Auth::user()->type) == 'project manager' || \Auth::user()->can('level 2')) { 

            $users = $this->companyEmployees($user->created_by);
            $users[$user->id] = $user->name;
            $users[$user->created_by] = $user->created_by;
            $companies = $users;
            $client_query_created_by = array_keys($users);
            $client_query->whereIn('deals.created_by', $client_query_created_by);

        } else if(\Auth::user()->can('level 3')){

        } else if(strtolower(\auth::user()->type) == 'marketing officer' || \Auth::user()->can('level 4')) {
            $branch_admission_officer = User::where(['type' => 'Admission Officer', 'branch_id' => $user->branch_id])->pluck('id', 'id')->toArray();
            $branch_admission_officer[] = $user->id;
            $client_query->whereIn('deals.created_by', $branch_admission_officer);
        }else {
            $client_query->where('deals.branch_id', $user->branch_id);
        }

        $client_query->where('users.name', 'LIKE', "%$search%");
        $client_query->where('users.email', 'LIKE', "%$search%");
        $clients = $client_query->orderBy('created_at', 'DESC')->get();
        return $clients;
    }

    private function getDeals($search){
        $usr = \Auth::user();
        $deals_query = Deal::select('deals.*')->join('user_deals', 'user_deals.deal_id', '=', 'deals.id');

        if(strtolower(\Auth::user()->type) == 'super admin' || \Auth::user()->can('level 1')){

        }else if (\Auth::user()->type == 'company') {
            $users = $this->companyEmployees(\auth::id());
            $users[$usr->id] = $usr->name;
            $companies = $users;
            $deal_created_by = array_keys($users);

            $deals_query->whereIn('deals.created_by', $deal_created_by);
        } else if (strtolower(\Auth::user()->type) == 'project manager' || \Auth::user()->can('level 2')) {
            $users = $this->companyEmployees($usr->created_by);
            $users[$usr->id] = $usr->name;
            $users[$usr->created_by] = $usr->created_by;
            $companies = $users;
            $deal_created_by = array_keys($users);
            $deals_query->whereIn('deals.created_by', $deal_created_by);
        } else if(\Auth::user()->can('level 3')){

        }else if (strtolower(\auth::user()->type) == 'marketing officer' || \Auth::user()->can('level 4')) {
            $branch_admission_officer = User::where(['type' => 'Admission Officer', 'branch_id' => $usr->branch_id])->pluck('id', 'id')->toArray();
            $branch_admission_officer[] = $usr->id;
            $deals_query->whereIn('deals.created_by', $branch_admission_officer);
            $companies = User::where('id', $usr->id)->get()->pluck('name', 'id');
        } else {
            $deals_query->where('assigned_to', \Auth::user()->id);
        }

       $deals = $deals_query->orderBy('deals.id', 'DESC')->get();
        
       return $deals;
    }   

    private function getApplications($search){
        $usr = \Auth::user();

        $app_query = DealApplication::select(['deal_applications.*']);

        if ($usr->type == 'super admin' || \Auth::user()->can('level 1')) { 
            $app_query->join('deals', 'deals.id', 'deal_applications.deal_id');
        }else if ($usr->type == 'company') {
            $app_query->join('deals', 'deals.id', 'deal_applications.deal_id')->where('deals.created_by', $usr->id);
        }else if(\Auth::user()->can('level 2')){
        
        }else if(\Auth::user()->can('level 3')){
        
        }else if(\Auth::user()->can('level 4')) {

        }else {
            $app_query->join('deals', 'deals.id', 'deal_applications.deal_id')->where('deals.created_by', $usr->created_by);
        }

        $applications = $app_query->get();

        return $applications;
        
    }


    private function getOrganizations($search){
        $org_query = User::select(['users.*'])->join('organizations', 'organizations.user_id', '=', 'users.id')->where('users.type', 'organization');
        $org_query->orwhere('users.name', 'LIKE', "%$search%");
        $org_query->orwhere('organizations.phone', 'LIKE', "%$search%");
        $org_query->orwhere('organizations.billing_street', 'LIKE', "%$search%");
        $org_query->orwhere('organizations.billing_city', 'LIKE', "%$search%");
        $org_query->orwhere('organizations.billing_state', 'LIKE', "%$search%");
        $org_query->orwhere('organizations.billing_country', 'LIKE', "%$search%");
        $organizations = $org_query->get();
        return $organizations;
    }   

    private function getBrands($search){

        $brand_query = User::where('type', 'company');
        $brand_query->orwhere('name', 'LIKE', "%$search%");
        $brand_query->orwhere('email', 'LIKE', "%$search%");
        $brands = $brand_query->get();

        return $brands;
    }

    private function getBranches($search){

        $branch_query = Branch::where('name', 'LIKE', "%$search%");
        $branches = $branch_query->get();

        return $branches;
    }

    private function getRegions($search){

        $region_query = Region::where('name', 'LIKE', "%$search%");
        $regions = $region_query->get();

        return $regions;
    }

    private function getEmployees($search){
        $excludedTypes = ['super admin', 'company', 'team', 'client'];

        $usersQuery = User::whereNotIn('type', $excludedTypes);
        $usersQuery->where('name', 'LIKE', "%$search%");

        $employees = $usersQuery->get();

        return $employees;
    }

    private function companyEmployees($id)
    {
        $users = DB::table('users as u')
            ->select('u.id', 'u.name')
            ->join('roles as r', 'u.type', '=', 'r.name')
            ->join('role_has_permissions as rp', 'r.id', '=', 'rp.role_id')
            ->join('permissions as p', 'rp.permission_id', '=', 'p.id')
            ->where('u.created_by', '=', $id)
            ->where('p.name', '=', 'create lead')
            ->groupBy('u.id', 'u.name')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        return $users;
    }
}


