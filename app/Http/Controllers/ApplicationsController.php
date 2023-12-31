<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Stage;
use App\Models\ClientDeal;
use App\Models\University;
use App\Models\StageHistory;
use Illuminate\Http\Request;
use App\Models\DealApplication;
use App\Models\Pipeline;
use App\Models\ActivityLog;
use App\Models\Utility;


class ApplicationsController extends Controller
{
    //

    private function ApplicationFilters()
    {
        $filters = [];
        if (isset($_GET['applications']) && !empty($_GET['applications'])) {
            $filters['name'] = $_GET['applications'];
        }


        if (isset($_GET['stages']) && !empty($_GET['stages'])) {
            $filters['stage_id'] = $_GET['stages'];
        }

        if (isset($_GET['created_by']) && !empty($_GET['created_by'])) {
            $filters['created_by'] = $_GET['created_by'];
        }

        if (isset($_GET['universities']) && !empty($_GET['universities'])) {
            $filters['university_id'] = $_GET['universities'];
        }

        return $filters;
    }

    public function index(){
        $usr = \Auth::user();

         //////////////pagination calculation
         $start = 0;
         $num_results_on_page = 50;
         if (isset($_GET['page'])) {
             $page = $_GET['page'];
             $num_of_result_per_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
             $start = ($page - 1) * $num_results_on_page;
         } else {
             $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
         }

         /////////////////end pagination calculation

         if ($usr->can('view application') || $usr->type == 'super admin' || $usr->type == 'company') {

            $app_query = DealApplication::select(['deal_applications.*']);
            
            if(\Auth::user()->type == 'super admin'){
            $app_query->join('deals', 'deals.id', 'deal_applications.deal_id');
            }else{
                 $companies = FiltersBrands();
            $brand_ids = array_keys($companies);
            $app_query->join('deals', 'deals.id', 'deal_applications.deal_id')->whereIn('deal_applications.brand_id', $brand_ids);
            }
           
            $total_records = $app_query->count();
            //$filters
            $app_for_filer = $app_query->get();


            $filters = $this->ApplicationFilters();
            foreach ($filters as $column => $value) {
                if ($column === 'name') {
                    $app_query->whereIn('deal_applications.name', $value);
                } elseif ($column === 'stage_id') {
                    $app_query->whereIn('deal_applications.stage_id', $value);
                } elseif ($column == 'university_id') {
                    $app_query->whereIn('deal_applications.university_id', $value);
                }elseif ($column == 'created_by') {
                    $app_query->whereIn('deal_applications.created_by', $value);
                }
            }

            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true' && isset($_GET['search']) && !empty($_GET['search'])) {
                $g_search = $_GET['search'];
                $app_query->Where('deal_applications.name', 'like', '%' . $g_search . '%');
                $app_query->orWhere('deal_applications.application_key', 'like', '%' . $g_search . '%');
                $app_query->orWhere('deal_applications.course', 'like', '%' . $g_search . '%');
            }

            $applications = $app_query->get();
         
            $universities = University::get()->pluck('name', 'id')->toArray();
            $stages = Stage::get()->pluck('name', 'id')->toArray();
            $brands = User::where('type', 'company')->get();

            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
                $html = view('applications.applications_list_ajax',  compact('applications', 'total_records', 'universities', 'stages', 'app_for_filer', 'brands'))->render();

                return json_encode([
                    'status' => 'success',
                    'html' => $html
                ]);
            }


            return view('applications.index', compact('applications', 'total_records', 'universities', 'stages', 'app_for_filer', 'brands'));

         }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
         }
    }

    public function getDealApplication(){
        $id = $_GET['id'];
        $applications = DealApplication::where('deal_id', $id)->pluck('application_key', 'id');

        $html = '<option value=""> Select Application</option>';

        foreach($applications as $key => $app){
            $html .= '<option value="'.$key.'">'.$app.'</option>';
        }

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function updateApplicationStage(){
        $application_id = $_GET['application_id'];
        $stage_id = $_GET['stage_id'];
        DealApplication::where('id', $application_id)->update(['stage_id' => $stage_id]);

        //Add Stage History
        $data_for_stage_history = [
            'stage_id' => $stage_id,
            'type_id' => $application_id,
            'type' => 'application'
        ];
        addLeadHistory($data_for_stage_history);


        //Log
        $data = [
            'type' => 'info',
            'note' => json_encode([
                            'title' => 'Stage Updated',
                            'message' => 'Application stage updated successfully.'
                        ]),
            'module_id' => $application_id,
            'module_type' => 'application',
        ];
        addLogActivity($data);

        return json_encode([
            'status' => 'success'
        ]);
    }

    public function deleteBulkApplications(Request $request){
        if($request->ids != null){
            DealApplication::whereIn('id', explode(',', $request->ids))->delete();
            return redirect()->route('applications.index')->with('success', 'Application deleted successfully');
        }else{
            return redirect()->route('applications.index')->with('error', 'Atleast select 1 application.');
        }
    }

    public function application()
    {
        $usr=\Auth::user();
        $pipeline = Pipeline::get();
        if ($usr->can('manage deal') || $usr->type == 'super admin') {
            if ($usr->default_pipeline) {
                $pipeline = Pipeline::where('id', '=', $usr->default_pipeline)->first();
                if (!$pipeline) {
                    $pipeline = Pipeline::first();
                }
            } else {
                $pipeline = Pipeline::first();
            }
            $pipelines = Pipeline::get()->pluck('name', 'id');
            if ($usr->type == 'client') {
                $id_deals = $usr->clientDeals->pluck('id');
            } else {
                $id_deals = $usr->deals->pluck('id');
            }
            $deals       = DealApplication::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->get();
            $curr_month  = DealApplication::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereMonth('created_at', '=', date('m'))->get();
            $curr_week   = DealApplication::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereBetween(
                'created_at',
                [
                    \Carbon\Carbon::now()->startOfWeek(),
                    \Carbon\Carbon::now()->endOfWeek(),
                ]
            )->get();
            $last_30days = DealApplication::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(30))->get();
            // Deal Summary
            $cnt_deal                = [];
            $cnt_deal['total']       = DealApplication::getDealSummary($deals);
            $cnt_deal['this_month']  = DealApplication::getDealSummary($curr_month);
            $cnt_deal['this_week']   = DealApplication::getDealSummary($curr_week);
            $cnt_deal['last_30days'] = DealApplication::getDealSummary($last_30days);
            $total_records = DealApplication::count();
            if ($usr->can('view all deals') || \Auth::user()->type == 'super admin') {
                $total_records =  DealApplication::select('deal_applications.*')->count();
            } else if (\auth::user()->type == "company") {
                $users = User::select(['users.id'])->join('roles', 'roles.name', '=', 'users.type')
                    ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
                    ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    ->where(['users.created_by' => \auth::id(), 'permissions.name' => 'create deal'])
                    ->groupBy('users.id')
                    ->pluck('id')
                    ->toArray();
                $deal_created_by = $users;
                $deal_created_by[] = $usr->id;
                $total_records = DealApplication::select('deal_applications.*')->whereIn('created_by', $deal_created_by)->count();
            } else {
                $deal_created_by[] = \auth::user()->created_by;
                $deal_created_by[] = $usr->id;
                $deal1_query = DealApplication::select('deal_applications.*');
                $total_records = $deal1_query->whereIn('created_by', $deal_created_by)->count();
            }
            return view('applications.list', compact('pipelines', 'pipeline', 'cnt_deal', 'total_records'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function order(Request $request)
    {
        $usr = \Auth::user();

        if ($usr->can('move application')) {
            $post       = $request->all();
            $deal       = DealApplication::where('id',$post['app'])->where('deal_id',$post['deal_id'])->first();
            $clients    = ClientDeal::select('client_id')->where('deal_id', '=', $deal->deal_id)->get()->pluck('client_id')->toArray();
            $deal_users = $deal->users->pluck('id')->toArray();
            $usrs       = User::whereIN('id', array_merge($deal_users, $clients))->get()->pluck('email', 'id')->toArray();

            if ($deal->stage_id != $post['stage_id']) {

                $newStage = Stage::find($post['stage_id']);
                $from=Stage::find($deal->stage_id)->name;
                //Log

                $data = [
                    'type' => 'info',
                    'note' => json_encode([
                        'title' => 'Lead Updated',
                        'message' => ($from != $newStage->name) ? 'Lead updated from ' . $from . ' to ' . $newStage->name . ' successfully' : 'Lead updated successfully'
                    ]),
                    'module_id' => $deal->deal_id,
                    'module_type' => 'Application',
                ];
                addLogActivity($data);

                ActivityLog::create(
                    [
                        'user_id' => $usr->id,
                        'deal_id' => $deal->deal_id,
                        'log_type' => 'Move',
                        'remark' => json_encode(
                            [
                                'title' => $deal->name,
                                'old_status' => $deal->stage->name,
                                'new_status' => $newStage->name,
                            ]
                        ),
                    ]
                );
                $dealArr = [
                    'deal_id' => $deal->deal_id,
                    'name' => $deal->name,
                    'updated_by' => $usr->id,
                    'old_status' => $deal->stage->name,
                    'new_status' => $newStage->name,
                ];

                $dArr = [
                    'deal_name' => $deal->name,
                    'deal_pipeline' => $deal->email,
                    'deal_stage' => $deal->stage->name,
                    'deal_status' => $deal->status,
                    'deal_price' => $usr->priceFormat($deal->price),
                    'deal_old_stage' => $deal->stage->name,
                    'deal_new_stage' => $newStage->name,
                ];

                // Send Email
                Utility::sendEmailTemplate('Move Deal', $usrs, $dArr);
            }
            foreach ($post['order'] as $key => $item) {
                $deal           = DealApplication::where('id',$post['app'])->where('deal_id',$item)->first();
                $deal->order    = $key;
                $deal->stage_id = $post['stage_id'];
                $deal->save();
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }
}
