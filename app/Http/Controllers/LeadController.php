<?php

namespace App\Http\Controllers;

use Exception;
use SplFileObject;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\User;
use App\Models\Label;
use App\Models\Stage;
use App\Models\Branch;
use App\Models\Course;
use App\Models\Source;
use App\Models\Utility;
use App\Models\DealCall;
use App\Models\DealFile;
use App\Models\LeadCall;
use App\Models\LeadFile;
use App\Models\LeadNote;
use App\Models\Pipeline;
use App\Models\UserDeal;
use App\Models\UserLead;
use App\Models\DealEmail;
use App\Models\LogActivity;
use App\Models\LeadEmail;
use App\Models\LeadStage;
use Illuminate\View\View;
use App\Models\ClientDeal;
use App\Models\LeadToDeal;
use App\Models\University;
use App\Mail\SendLeadEmail;
use App\Models\Organization;
use App\Models\StageHistory;
use Illuminate\Http\Request;
use App\Models\DealDiscussion;
use App\Models\LeadDiscussion;
use App\Models\ProductService;
use App\Models\DealApplication;
use App\Models\LeadActivityLog;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\CompanyPermission;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->can('manage lead')) {

            if (\Auth::user()->default_pipeline) {
                $pipeline = Pipeline::where('id', '=', \Auth::user()->default_pipeline)->first();

                if (!$pipeline) {
                    $pipeline = Pipeline::first();
                }
            } else {
                $pipeline = Pipeline::first();
            }

            $pipelines = Pipeline::get()->pluck('name', 'id');

            //$total_records = Lead::count();

            if (\Auth::user()->can('view all leads')) {
                $total_records = Lead::count();
            } elseif (\Auth::user()->type == 'company') {
                $lead_created_by = User::select(['users.id', 'users.name'])->join('roles', 'roles.name', '=', 'users.type')
                    ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
                    ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    ->where(['users.created_by' => \auth::id(), 'permissions.name' => 'create lead'])
                    ->groupBy('users.id')
                    ->pluck('id')
                    ->toArray();
                $lead_created_by[] = \Auth::user()->id;
                $total_records = Lead::whereIn('leads.created_by', $lead_created_by)->count();
            } else {
                $lead_created_by[] = \Auth::user()->created_by;
                $lead_created_by[] = \Auth::user()->id;;
                $total_records = Lead::whereIn('leads.created_by', $lead_created_by)->count();
            }

            $avatar = User::get()->pluck('avatar', 'id');
            $username = User::get()->pluck('name', 'id');

            return view('leads.index', compact('pipelines', 'pipeline', 'total_records','username','avatar'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    // Function will Return created employee
    private function companyEmployees($creatorId)
    {
        $users = DB::table('users as u')
            ->select('u.id', 'u.name')
            ->join('roles as r', 'u.type', '=', 'r.name')
            ->join('role_has_permissions as rp', 'r.id', '=', 'rp.role_id')
            ->join('permissions as p', 'rp.permission_id', '=', 'p.id')
            ->where('u.created_by', '=', $creatorId)
            ->where('p.name', '=', 'create lead')
            ->groupBy('u.id', 'u.name')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        return $users;
    }

    private function leadsFilter()
    {
        $filters = [];
        if (isset($_GET['name']) && !empty($_GET['name'])) {
            $filters['name'] = $_GET['name'];
        }


        if (isset($_GET['stages']) && !empty($_GET['stages'])) {
            $filters['stage_id'] = $_GET['stages'];
        }

        if (isset($_GET['users']) && !empty($_GET['users'])) {
            $filters['users'] = $_GET['users'];
        }

        if (isset($_GET['subject']) && !empty($_GET['subject'])) {
            $filters['subject'] = $_GET['subject'];
        }

        if (isset($_GET['created_by']) && !empty($_GET['created_by'])) {
            $filters['created_by'] = $_GET['created_by'];
        }

        if (isset($_GET['created_at']) && !empty($_GET['created_at'])) {
            $filters['created_at'] = $_GET['created_at'];
        }

        return $filters;
    }

    public function lead_list()
    {
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

        $filters = $this->leadsFilter();

        if ($usr->can('manage lead') || \Auth::user()->type == 'super admin') {

            if (\Auth::user()->type == 'super admin') {
                $pipeline = Pipeline::get();
            }else{
                if (\Auth::user()->default_pipeline) {
                    $pipeline = Pipeline::where('id', '=', \Auth::user()->default_pipeline)->first();

                    if (!$pipeline) {
                        $pipeline = Pipeline::first();
                    }
                } else {
                    $pipeline = Pipeline::first();
                }
            }

            $companies = FiltersBrands();
            $brand_ids = array_keys($companies);

            $leads_query = Lead::select('leads.*')->join('lead_stages', 'leads.stage_id', '=', 'lead_stages.id');
            $leads_query->whereIn('brand_id', $brand_ids);

            // Add the dynamic filters
            foreach ($filters as $column => $value) {
                if ($column === 'name') {
                    $leads_query->whereIn('leads.name', $value);
                } elseif ($column === 'stage_id') {
                    $leads_query->whereIn('stage_ids', $value);
                } elseif ($column === 'users') {
                    $leads_query->whereIn('leads.user_id', $value);
                } elseif ($column == 'created_at') {
                    $leads_query->whereDate('leads.created_at', 'LIKE', '%' . substr($value, 0, 10) . '%');
                }
            }

            //if list global search
            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true' && isset($_GET['search']) && !empty($_GET['search'])) {
                $g_search = $_GET['search'];
                $leads_query->Where('leads.name', 'like', '%' . $g_search . '%');
                $leads_query->orWhere('leads.email', 'like', '%' . $g_search . '%');
                $leads_query->orWhere('leads.phone', 'like', '%' . $g_search . '%');
            }

            $leads_query->whereNotIn('lead_stages.name', ['Unqualified', 'Junk Lead']);
            $total_records =  $leads_query->clone()->count();
            $leads = $leads_query->clone()->groupBy('leads.id')->orderBy('leads.created_at', 'desc')->skip($start)->take($num_results_on_page)->get();
            $users = allUsers();
            $stages = LeadStage::get();
            $pipelines = Pipeline::get()->pluck('name', 'id');
            $organizations = User::where('type', 'organization')->pluck('name', 'id');
            $brands = $companies;
            $sourcess = Source::get()->pluck('name', 'id');
            $branches = Branch::get()->pluck('name', 'id')->ToArray();
            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
                $html = view('leads.leads_list_ajax', compact('leads', 'users', 'total_records'))->render();

                return json_encode([
                    'status' => 'success',
                    'html' => $html
                ]);
            }

            $total_leads_by_status_records = Lead::select([
                'lead_stages.type',
                DB::raw('count(leads.id) as total_leads')
            ])
            ->join('lead_stages', 'leads.stage_id', '=', 'lead_stages.id')
            ->groupBy('lead_stages.type')
            ->get();
            $total_leads_by_status = [];
            foreach($total_leads_by_status_records as $status){
                $total_leads_by_status[$status->type] = $status->total_leads;
            }
            return view('leads.list', compact('pipelines','branches', 'pipeline', 'leads', 'users', 'stages', 'total_records', 'companies', 'organizations', 'sourcess', 'brands', 'total_leads_by_status'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (\Auth::user()->can('create lead') || \Auth::user()->type == 'super admin') {
            $branches = Branch::get()->pluck('name', 'id');
            $users = allUsers();
            $companies = FiltersBrands();


            //leads stages
            $stages = LeadStage::get()->pluck('name', 'id');

            //if not company
            $organizations = User::where('type', 'organization')->get()->pluck('name', 'id');
            $sources = Source::get()->pluck('name', 'id');
            $countries = countries();

            return view('leads.create', compact('users','companies' ,'stages', 'branches', 'organizations', 'sources', 'countries'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function getCompanyEmployees(){
        $id = $_GET['id'];

        $employees =  User::where('brand_id', $id)->pluck('name', 'id')->toArray();
        $branches = Branch::whereRaw('FIND_IN_SET(?, brands)', [$id])->pluck('name', 'id')->toArray();


        $html = ' <select class="form form-control lead_assgigned_user select2" id="choices-multiple4" name="lead_assgigned_user" required> <option value="">Select User</option> ';
        foreach ($employees as $key => $user) {
            $html .= '<option value="' . $key . '">' . $user . '</option> ';
        }
        $html .= '</select>';

        $html1 = ' <select class="form form-control lead_branch select2" id="choices-multiple4" name="lead_branch" required> <option value="">Select Branch</option> ';
        foreach ($branches as $key => $branch) {
            $html1 .= '<option value="' . $key . '">' . $branch . '</option> ';
        }
        $html1 .= '</select>';

        return json_encode([
            'status' => 'success',
            'employees' => $html,
            'branches' => $html1,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $usr = \Auth::user();
        if ($usr->can('create lead') ||  \Auth::user()->type == 'super admin') {
            $validator = \Validator::make(
                $request->all(),
                [
                    //'lead_prefix' => 'required',
                    'lead_first_name' => 'required',
                    'lead_last_name' => 'required',
                    'lead_stage' => 'required',
                    //'lead_assgigned_user' => 'required',
                    //'lead_branch' => 'required',
                    //'lead_organization' => 'required',
                    //'lead_source' => 'required',
                    'lead_phone' => 'required',
                    'lead_email' => 'required|unique:leads,email',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return json_encode([
                    'status' => 'error',
                    'message' => $messages->first()
                ]);
            }



            // Default Field Value
            if ($usr->default_pipeline) {
                $pipeline = Pipeline::where('id', '=', $usr->default_pipeline)->first();
                if (!$pipeline) {
                    $pipeline = Pipeline::first();
                }
            } elseif (\Auth::user()->type == 'super admin') {
                $pipeline = Pipeline::first();
            } else {
                $pipeline = Pipeline::first();
            }

            //$stage = LeadStage::where('pipeline_id', '=', $pipeline->id)->first();
            $stage = LeadStage::where('pipeline_id', '=', $pipeline->id)->orderBy('order', 'asc')->first();
            // End Default Field Value

            if (empty($stage)) {
                return redirect()->back()->with('error', __('Please Create Stage for This Pipeline.'));
            } else {
                $lead              = new Lead();
                $lead->title       = $request->lead_prefix;
                $lead->name        = $request->lead_first_name . ' ' . $request->lead_last_name;
                $lead->email       = $request->lead_email;
                $lead->phone       = $request->lead_phone;
                $lead->mobile_phone = $request->lead_mobile_phone;
                $lead->branch_id      = $request->lead_branch;
                $lead->brand_id      = $request->brand_id;
                $lead->organization_id = gettype($request->lead_organization) == 'string' ? 0 : $request->lead_organization;
                $lead->organization_link = $request->lead_organization_link;
                $lead->sources = $request->lead_sources;
                $lead->referrer_email = $request->referrer_email;
                $lead->street = $request->lead_street;
                $lead->city = $request->lead_city;
                $lead->state = $request->lead_state;
                $lead->postal_code = $request->lead_postal_code;
                $lead->country = $request->lead_country;
                $lead->keynotes = $request->lead_description;
                $lead->tags = $request->lead_tags_list;
                $lead->stage_id    = $request->lead_stage;
                $lead->subject     = $request->lead_first_name . ' ' . $request->lead_last_name;
                $lead->user_id     = $request->lead_assgigned_user;
                $lead->pipeline_id = $pipeline->id;
                $lead->created_by  = \Auth::user()->id;
                $lead->date        = date('Y-m-d');
                $lead->drive_link = isset($request->drive_link) ? $request->drive_link : '';
                $lead->save();


                $users = User::get()->pluck('name', 'id')->toArray();
                $new_record_html = view('leads.lead_new_record', compact('lead', 'users'))->render();


                UserLead::create(
                    [
                        'user_id' => $usr->id,
                        'lead_id' => $lead->id,
                    ]
                );


                //Add Stage History
                $data_for_stage_history = [
                    'stage_id' => $request->lead_stage,
                    'type_id' => $lead->id,
                    'type' => 'lead'
                ];
                addLeadHistory($data_for_stage_history);


                //Log
                $data = [
                    'type' => 'info',
                    'note' => json_encode([
                                    'title' => 'Lead Created',
                                    'message' => 'Lead created successfully'
                                ]),
                    'module_id' => $lead->id,
                    'module_type' => 'lead',
                ];
                addLogActivity($data);

                if (isset($request->lead_assgigned_user) && !empty($request->lead_assgigned_user)) {
                    $usrEmail = User::find($request->lead_assgigned_user);

                    // Send Email
                    $setings = Utility::settings();
                    if ($setings['lead_assigned'] == 1) {

                        $leadAssignArr = [
                            'lead_name' => $lead->name,
                            'lead_email' => $lead->email,
                            'lead_subject' => $lead->subject,
                            'lead_pipeline' => $pipeline->name,
                            'lead_stage' => $stage->name,

                        ];

                        $resp = Utility::sendEmailTemplate('lead_assigned', [$usrEmail->id => $usrEmail->email], $leadAssignArr);
                        //$resp['is_success'] = true;

                        return json_encode([
                            'status' => 'success',
                            'html' => $new_record_html,
                            'lead_id' => $lead->id,
                            'message' =>   __('Lead successfully created!') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : '')
                        ]);

                        // return redirect()->back()->with('success', __('Lead successfully created!') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                    }
                }

                //Slack Notification
                $setting  = Utility::settings(\Auth::user()->creatorId());
                if (isset($setting['lead_notification']) && $setting['lead_notification'] == 1) {
                    $msg = __("New Lead created by") . ' ' . \Auth::user()->name . '.';
                    Utility::send_slack_msg($msg);
                }

                //Telegram Notification
                $setting  = Utility::settings(\Auth::user()->creatorId());
                if (isset($setting['telegram_lead_notification']) && $setting['telegram_lead_notification'] == 1) {
                    $msg = __("New Lead created by") . ' ' . \Auth::user()->name . '.';
                    Utility::send_telegram_msg($msg);
                }





                return json_encode([
                    'status' => 'success',
                    'html' => $new_record_html,
                    'lead_id' => $lead->id,
                    'message' =>  __('Lead successfully created!')
                ]);

                // return redirect()->back()->with('success', __('Lead successfully created!'));
            }
        } else {
            return json_encode([
                'status' => 'error',
                'message' => __('Permission Denied.')
            ]);
            //return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Lead $lead
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        if ($lead->is_active) {
            $calenderTasks = [];
            $deal          = Deal::where('id', '=', $lead->is_converted)->first();
            $stageCnt      = LeadStage::where('pipeline_id', '=', $lead->pipeline_id)->get();

            $i             = 0;
            foreach ($stageCnt as $stage) {
                $i++;
                if ($stage->id == $lead->stage_id) {
                    break;
                }
            }
            $precentage = number_format(($i * 100) / count($stageCnt));

            $lead_stages = $stageCnt;
            return view('leads.show', compact('lead', 'calenderTasks', 'deal', 'precentage', 'lead_stages'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Lead $lead
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Lead $lead)
    {
        if (\Auth::user()->can('edit lead') || \Auth::user()->type == 'super admin') {


            if (\Auth::user()->type == 'super admin') {
                $pipelines = Pipeline::get()->pluck('name', 'id');
                $pipelines->prepend(__('Select Pipeline'), '');
                $sources        = Source::get()->pluck('name', 'id');
                $products       = ProductService::get()->pluck('name', 'id');
                $users          = User::where('type', '!=', 'client')->where('type', '!=', 'company')->where('id', '!=', \Auth::user()->id)->get()->pluck('name', 'id');
                $lead->sources  = explode(',', $lead->sources);
                $lead->products = explode(',', $lead->products);
                $stages = LeadStage::get()->pluck('name', 'id');
                $branches = Branch::get()->pluck('name', 'id');
                $organizations = User::where('type', 'organization')->get()->pluck('name', 'id');
                $sources = Source::get()->pluck('name', 'id');
                $countries = $this->countries_list();

                $companies = FiltersBrands();
                return view('leads.edit', compact('companies','lead', 'pipelines', 'sources', 'products', 'users', 'stages', 'branches', 'organizations', 'sources', 'countries'));
            } else if (\Auth::user()->can('edit lead') || $lead->created_by == \Auth::user()->creatorId()) {

                // $pipelines = Pipeline::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                // $pipelines->prepend(__('Select Pipeline'), '');
                // $sources        = Source::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                // $products       = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                // $users          = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->where('type', '!=', 'company')->where('id', '!=', \Auth::user()->id)->get()->pluck('name', 'id');
                // $lead->sources  = explode(',', $lead->sources);
                // $lead->products = explode(',', $lead->products);

                $pipelines = Pipeline::get()->pluck('name', 'id');
                $pipelines->prepend(__('Select Pipeline'), '');
                $sources        = Source::get()->pluck('name', 'id');
                $products       = ProductService::get()->pluck('name', 'id');
                $users = allUsers();


                $lead->sources  = explode(',', $lead->sources);
                $lead->products = explode(',', $lead->products);

                //leads stages
                $stages = LeadStage::get()->pluck('name', 'id');

                //if not company
                // $branch_creator_id = '';
                // if (\Auth::user()->type == 'company') {
                //     $branch_creator_id = \Auth::user()->id;
                // } else {
                //     $branch_creator_id = User::where('id', \Auth::user()->created_by)->first()->id;
                // }
                $branches = Branch::pluck('name', 'id')->toArray();
                $organizations = User::where('type', 'organization')->get()->pluck('name', 'id');
                $sources = Source::get()->pluck('name', 'id');
                $countries = countries();
                $companies = FiltersBrands();

                return view('leads.edit', compact('lead', 'users', 'stages', 'branches', 'organizations', 'sources', 'countries', 'companies'));
                // return view('leads.edit', compact('lead', 'pipelines', 'sources', 'products', 'users'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }


    public function countries_list()
    {
        $url = "https://restcountries.com/v3.1/all";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if ($response !== false) {
            $countries = json_decode($response, true);

            if ($countries !== null) {
                $countries_arr = array_map(function ($country) {
                    return $country['name']['common'];
                }, $countries);
            } else {
                return "Error decoding JSON.";
            }
        } else {
            return "Error fetching data from API.";
        }

        return $countries_arr;
    }



    // public function countries_list()
    // {
    //     $url = "https://restcountries.com/v3.1/all";  // URL of the API endpoint

    //     $response = file_get_contents($url);  // Fetch the data

    //     if ($response !== false) {
    //         $countries = json_decode($response, true);  // Parse JSON response
    //         $countries_arr = [];

    //         // Check if the decoding was successful
    //         if ($countries !== null) {
    //             // Iterate through the countries
    //             foreach ($countries as $country) {
    //                 $countries_arr[] = $country['name']['common'];

    //                 //echo $country['name']['common'] . "<br>"; // Output the country name
    //             }
    //         } else {
    //             echo "Error decoding JSON.";
    //         }
    //     } else {
    //         echo "Error fetching data from API.";
    //     }
    //     return $countries_arr;
    // }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Lead $lead
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $lead              = Lead::where('id', $id)->first();
        $from=LeadStage::find($lead->stage_id)->name;
        if (\Auth::user()->can('edit lead') || \Auth::user()->type == 'super admin') {
            if ($lead->created_by == \Auth::user()->creatorId() || \Auth::user()->can('edit lead') || \Auth::user()->type == 'super admin') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        //'lead_prefix' => 'required',
                        'lead_first_name' => 'required',
                        'lead_last_name' => 'required',
                        'lead_stage' => 'required',
                        //'lead_assgigned_user' => 'required',
                        //'lead_branch' => 'required',
                        //'lead_organization' => 'required',
                        //'lead_source' => 'required',
                        'lead_phone' => 'required',
                        'lead_email' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return json_encode([
                        'status' => 'error',
                        'message' => $messages->first()
                    ]);
                }





                $lead->title       = $request->lead_prefix;
                $lead->name        = $request->lead_first_name . ' ' . $request->lead_last_name;
                // $lead->email       = $request->lead_email;
                $lead->phone       = $request->lead_phone;
                $lead->mobile_phone = $request->lead_mobile_phone;

                if(isset($request->branch_id)){
                    $lead->branch_id      = $request->lead_branch;
                }

                if(isset($request->brand_id)){
                    $lead->brand_id      = $request->brand_id;
                }

                if(isset($request->lead_assgigned_user)){
                    $lead->user_id     = $request->lead_assgigned_user;
                }

                $lead->organization_id =gettype($request->lead_organization) == 'string' ? 0 : $request->lead_organization;;
                $lead->organization_link = $request->lead_organization_link;
                $lead->sources = $request->lead_sources;
                $lead->referrer_email = $request->referrer_email;
                $lead->street = $request->lead_street;
                $lead->city = $request->lead_city;
                $lead->state = $request->lead_state;
                $lead->postal_code = $request->lead_postal_code;
                $lead->country = $request->lead_country;
                $lead->keynotes = $request->lead_description;
                $lead->tags = $request->lead_tags_list;
                $lead->stage_id    = $request->lead_stage;
                $lead->subject     =  $request->lead_first_name . ' ' . $request->lead_last_name;
                $lead->date        = date('Y-m-d');
                $lead->drive_link = isset($request->drive_link) ? $request->drive_link : '';
                $lead->save();

                $to=LeadStage::find($request->lead_stage)->name;
                //Log
                $data = [
                    'type' => 'info',
                    'note' => json_encode([
                        'title' => 'Lead Updated',
                        'message' => ($from != $to) ? 'Lead updated from ' . $from . ' to ' . $to . ' successfully' : 'Lead updated successfully'
                    ]),
                    'module_id' => $lead->id,
                    'module_type' => 'lead',
                ];
                addLogActivity($data);


                return json_encode([
                    'status' => 'success',
                    'lead_id' => $lead->id,
                    'message' =>  __('Lead successfully updated!')
                ]);

                //return redirect()->back()->with('success', __('Lead successfully updated!'));
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' =>  __('Permission Denied.')
                ]);
            }
        } else {
            return json_encode([
                'status' => 'error',
                'message' =>  __('Permission Denied.')
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Lead $lead
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {
        if (\Auth::user()->can('delete lead') || \Auth::user()->type == 'super admin') {
            if ($lead->created_by == \Auth::user()->creatorId() || \Auth::user()->type == 'super admin') {
                LeadDiscussion::where('lead_id', '=', $lead->id)->delete();
                LeadFile::where('lead_id', '=', $lead->id)->delete();
                UserLead::where('lead_id', '=', $lead->id)->delete();
                LeadActivityLog::where('lead_id', '=', $lead->id)->delete();

                //Log
                $data = [
                    'type' => 'info',
                    'note' => json_encode([
                                    'title' => 'Lead Deleted',
                                    'message' => 'Lead deleted successfully'
                                ]),
                    'module_id' => $lead->id,
                    'module_type' => 'lead',
                ];
                addLogActivity($data);


                $lead->delete();



                return redirect()->back()->with('success', __('Lead successfully deleted!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function getDiscussions()
    {
        $usr = \Auth::user();
        if ($usr->can('manage lead')) {
            $discussions = LeadDiscussion::select('lead_discussions.id', 'lead_discussions.comment', 'lead_discussions.created_at', 'users.name', 'users.avatar')->join('users', 'lead_discussions.created_by', 'users.id')->where(['lead_id' => $_POST['lead_id']])->get()->toArray();

            $diss = [];
            foreach ($discussions as $discussion) {
                $diss[] = [
                    'comment' => $discussion['comment'],
                    'name' => $discussion['name'],
                    'avatar' => $discussion['avatar'],
                    'created_at' => \Carbon\Carbon::parse($discussion['created_at'])->diffForHumans()
                ];
            }


            $returnHTML = view('leads.getDiscussions')->with('discussions', $diss)->render();
            return json_encode([
                'status' => true,
                'content' => $returnHTML
            ]);
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function saveDiscussions()
    {
        $usr = \Auth::user();
        if ($usr->can('manage lead')) {

            $discussion = new leadDiscussion();
            $discussion->lead_id = $_POST['lead_id'];
            $discussion->comment = $_POST['discussion'];
            $discussion->created_by = $usr->id;

            $discussion->save();

            $diss[] = [
                'comment' => $_POST['discussion'],
                'name' => $usr->name,
                'avatar' => $usr->avatar,
                'created_at' => \Carbon\Carbon::parse(date('Y-m-d H:i:s'))->diffForHumans()
            ];
            $returnHTML = view('leads.getDiscussions')->with('discussions', $diss)->render();


            //Log
            $data = [
                'type' => 'info',
                'note' => json_encode([
                                'title' => 'Discussion created',
                                'message' => 'Discussion created successfully'
                            ]),
                'module_id' => $_POST['lead_id'],
                'module_type' => 'lead',
            ];
            addLogActivity($data);

            return json_encode([
                'status' => true,
                'content' => $returnHTML
            ]);
        } else {

            return json_encode([
                'status' => false,
                'content' => __('Permission Denied.')
            ]);

            //return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    private function excelSheetDataSaved($request, $file, $pipeline, $stage)
    {
        $usr = \Auth::user();
        $column_arr = [];
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $key = 0;

        foreach ($worksheet->getRowIterator() as $line) {


            if ($key == 0) {
                foreach ($line->getCellIterator() as $column_key => $column) {
                    $column = preg_replace('/[^\x20-\x7E]/', '', $column);
                    $column_arr[$column_key] = $_POST['columns'][$column];
                }
                $key++;
                continue;
            }


            $lead  = new Lead();
            $test = [];
            foreach ($line->getCellIterator() as $column_key => $column) {

                $column = preg_replace('/[^\x20-\x7E]/', '', $column);
                if (!empty($column_arr[$column_key])) {
                    $test[$column_arr[$column_key]] = $column;
                    $lead->{$column_arr[$column_key]} = $column;
                }
            }

            $lead_exist = Lead::where('email', $lead->email)->first();
            if ($lead_exist) {
                continue;
            }
            //if no email found
            if (!in_array('email', $column_arr)) {
                $lead->email = '';
            }

            if (!in_array('subject', $column_arr)) {
                $lead->subject = '';
            }

            $lead->user_id     = $request->assigned_to;
            $lead->pipeline_id = $pipeline->id;
            if (!isset($stage->id)) {
                return redirect()->back()->with('error', 'Please create lead stage first');
            }

            $lead->stage_id    = $stage->id;
            $lead->created_by  = $usr->id;
            $lead->date        = date('Y-m-d');
            if (!empty($lead->name) || !empty($lead->email) || !empty($lead->phone) || !empty($lead->subject) || !empty($lead->notes)) {
                $lead->save();
                UserLead::create(
                    [
                        'user_id' => $usr->id,
                        'lead_id' => $lead->id,
                    ]
                );

                $usrEmail = User::find($request->assigned_to);

                // Send Email
                $setings = Utility::settings();
                if ($setings['lead_assigned'] == 1) {

                    $usrEmail = User::find($request->assigned_to);
                    $leadAssignArr = [
                        'lead_name' => $lead->name,
                        'lead_email' => $lead->email,
                        'lead_subject' => $lead->subject,
                        'lead_pipeline' => $pipeline->name,
                        'lead_stage' => $stage->name,

                    ];

                    $resp = Utility::sendEmailTemplate('lead_assigned', [$usrEmail->id => $usrEmail->email], $leadAssignArr);

                    //return redirect()->back()->with('success', __('Lead successfully created!') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                }

                //Slack Notification
                $setting  = Utility::settings($usr->id);
                if (isset($setting['lead_notification']) && $setting['lead_notification'] == 1) {
                    $msg = __("New Lead created by") . ' ' . $usr->name . '.';
                    Utility::send_slack_msg($msg);
                }

                //Telegram Notification
                $setting  = Utility::settings($usr->id);
                if (isset($setting['telegram_lead_notification']) && $setting['telegram_lead_notification'] == 1) {
                    $msg = __("New Lead created by") . ' ' . $usr->name . '.';
                    Utility::send_telegram_msg($msg);
                }
            }
        }

        return true;
    }


    private function csvSheetDataSaved($request, $file, $pipeline, $stage)
    {
        $usr = \Auth::user();
        $column_arr = [];
        $handle = fopen($file->getPathname(), 'r');
        $key = 0;
        while ($line = fgets($handle)) {
            $line = explode(",", $line);
            if ($key == 0) {
                foreach ($line as $column_key => $column) {
                    $column = preg_replace('/[^\x20-\x7E]/', '', $column);
                    $column_arr[$column_key] = $_POST['columns'][$column];
                }
                $key++;
                continue;
            }


            $lead  = new Lead();
            $test = [];
            foreach ($line as $column_key => $column) {

                $column = preg_replace('/[^\x20-\x7E]/', '', $column);
                if (!empty($column_arr[$column_key])) {
                    $test[$column_arr[$column_key]] = $column;
                    $lead->{$column_arr[$column_key]} = $column;
                }
            }


            $lead_exist = Lead::where('email', $lead->email)->first();
            if ($lead_exist) {
                continue;
            }
            //if no email found
            if (!in_array('email', $column_arr)) {
                $lead->email = '';
            }

            if (!in_array('subject', $column_arr)) {
                $lead->subject = 'Default Subject';
            }

            $lead->user_id     = $request->assigned_to;
            $lead->pipeline_id = $pipeline->id;
            if (!isset($stage->id)) {
                return redirect()->back()->with('error', 'Please create lead stage first');
            }

            $lead->stage_id    = $stage->id;
            $lead->created_by  = $usr->id;
            $lead->date        = date('Y-m-d');
            if (!empty($lead->name) || !empty($lead->email) || !empty($lead->phone) || !empty($lead->subject) || !empty($lead->notes)) {
                $lead->save();

                UserLead::create(
                    [
                        'user_id' => $usr->id,
                        'lead_id' => $lead->id,
                    ]
                );

                $usrEmail = User::find($request->assigned_to);

                // Send Email
                $setings = Utility::settings();
                if ($setings['lead_assigned'] == 1) {

                    $usrEmail = User::find($request->assigned_to);
                    $leadAssignArr = [
                        'lead_name' => $lead->name,
                        'lead_email' => $lead->email,
                        'lead_subject' => $lead->subject,
                        'lead_pipeline' => $pipeline->name,
                        'lead_stage' => $stage->name,

                    ];

                    $resp = Utility::sendEmailTemplate('lead_assigned', [$usrEmail->id => $usrEmail->email], $leadAssignArr);

                    //return redirect()->back()->with('success', __('Lead successfully created!') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                }

                //Slack Notification
                $setting  = Utility::settings($usr->id);
                if (isset($setting['lead_notification']) && $setting['lead_notification'] == 1) {
                    $msg = __("New Lead created by") . ' ' . $usr->name . '.';
                    Utility::send_slack_msg($msg);
                }

                //Telegram Notification
                $setting  = Utility::settings($usr->id);
                if (isset($setting['telegram_lead_notification']) && $setting['telegram_lead_notification'] == 1) {
                    $msg = __("New Lead created by") . ' ' . $usr->name . '.';
                    Utility::send_telegram_msg($msg);
                }
            }
            //$lead->save();
        }

        return true;
        //return redirect()->back()->with('success', __('Lead successfully created!'));
    }




    public function importCsv(Request $request)
    {
        $usr = \Auth::user();

        if ($usr->can('edit lead')) {
            $file = $request->file('leads_file');

            $column_arr = [];

            // Default Field Value
            if ($usr->default_pipeline) {
                $pipeline = Pipeline::where('id', '=', $usr->default_pipeline)->first();
                if (!$pipeline) {
                    $pipeline = Pipeline::first();
                }
            } else {
                $pipeline = Pipeline::first();
            }

            $stage = LeadStage::where('pipeline_id', '=', $pipeline->id)->orderBy('order', 'asc')->first();

            $file = $request->file('leads_file');
            $extension = $file->getClientOriginalExtension();
            if ($extension == 'csv') {
                $response = $this->csvSheetDataSaved($request, $file, $pipeline, $stage);
            } else {
                $response =  $this->excelSheetDataSaved($request, $file, $pipeline, $stage);
            }


            // $handle = fopen($file->getPathname(), 'r');
            // $key = 0;
            // while ($line = fgets($handle)) {
            //     if ($key == 0) {
            //         foreach ($line as $column_key => $column) {
            //             $column = preg_replace('/[^\x20-\x7E]/', '', $column);
            //             $column_arr[$column_key] = $_POST['columns'][$column];
            //         }
            //         $key++;
            //         continue;
            //     }


            //     $lead  = new Lead();
            //     $test = [];
            //     foreach ($line as $column_key => $column) {

            //         $column = preg_replace('/[^\x20-\x7E]/', '', $column);
            //         if (!empty($column_arr[$column_key])) {
            //             $test[$column_arr[$column_key]] = $column;
            //             $lead->{$column_arr[$column_key]} = $column;
            //         }
            //     }


            //     $lead_exist = Lead::where('email', $lead->email)->first();
            //     if ($lead_exist) {
            //         continue;
            //     }
            //     //if no email found
            //     if (!in_array('email', $column_arr)) {
            //         $lead->email = '';
            //     }

            //     if (!in_array('subject', $column_arr)) {
            //         $lead->subject = '';
            //     }

            //     $lead->user_id     = $request->assigned_to;
            //     $lead->pipeline_id = $pipeline->id;
            //     if (!isset($stage->id)) {
            //         return redirect()->back()->with('error', 'Please create lead stage first');
            //     }

            //     $lead->stage_id    = $stage->id;
            //     $lead->created_by  = $usr->id;
            //     $lead->date        = date('Y-m-d');
            //     if (!empty($lead->name) || !empty($lead->email) || !empty($lead->phone) || !empty($lead->subject) || !empty($lead->notes)) {
            //         $lead->save();

            //         UserLead::create(
            //             [
            //                 'user_id' => $usr->id,
            //                 'lead_id' => $lead->id,
            //             ]
            //         );

            //         $usrEmail = User::find($request->assigned_to);

            //         // Send Email
            //         $setings = Utility::settings();
            //         if ($setings['lead_assigned'] == 1) {

            //             $usrEmail = User::find($request->assigned_to);
            //             $leadAssignArr = [
            //                 'lead_name' => $lead->name,
            //                 'lead_email' => $lead->email,
            //                 'lead_subject' => $lead->subject,
            //                 'lead_pipeline' => $pipeline->name,
            //                 'lead_stage' => $stage->name,

            //             ];

            //             $resp = Utility::sendEmailTemplate('lead_assigned', [$usrEmail->id => $usrEmail->email], $leadAssignArr);

            //             //return redirect()->back()->with('success', __('Lead successfully created!') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            //         }

            //         //Slack Notification
            //         $setting  = Utility::settings($usr->id);
            //         if (isset($setting['lead_notification']) && $setting['lead_notification'] == 1) {
            //             $msg = __("New Lead created by") . ' ' . $usr->name . '.';
            //             Utility::send_slack_msg($msg);
            //         }

            //         //Telegram Notification
            //         $setting  = Utility::settings($usr->id);
            //         if (isset($setting['telegram_lead_notification']) && $setting['telegram_lead_notification'] == 1) {
            //             $msg = __("New Lead created by") . ' ' . $usr->name . '.';
            //             Utility::send_telegram_msg($msg);
            //         }
            //     }
            //     //$lead->save();
            // }

            if ($response)
                return redirect()->back()->with('success', __('Lead successfully created!'));
            else
                return redirect()->back()->with('error', __('Went something wrong.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public static function convert_from_latin1_to_utf8_recursively($dat)
    {
        if (is_string($dat)) {
            return utf8_encode($dat);
        } elseif (is_array($dat)) {
            $ret = [];
            foreach ($dat as $i => $d) $ret[$i] = self::convert_from_latin1_to_utf8_recursively($d);

            return $ret;
        } elseif (is_object($dat)) {
            foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);

            return $dat;
        } else {
            return $dat;
        }
    }

    private function readExcelHeader($file)
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $first_row = [];
        foreach ($worksheet->getRowIterator() as $row) {
            $cleaned_row = [];
            foreach ($row->getCellIterator() as $cell) {
                $cellValue = $cell->getValue();
                $clean_string = preg_replace('/[^\x20-\x7E]/', '', $cellValue);
                $cleaned_row[] = $clean_string;
            }
            $first_row = $cleaned_row;
            break;
        }
        return $first_row;
    }


    public function readCsvHeader($file)
    {
        $handle = fopen($file->getPathname(), 'r');
        $first_row = [];

        while ($line = fgets($handle)) {

            $fields = explode(",", $line);
            foreach ($fields as $field) {
                $clean_string = preg_replace('/[^\x20-\x7E]/', '', $field);
                $first_row[] = $clean_string;
            }
            break;
        }

        fclose($handle);

        return $first_row;
    }


    public function fetchColumns(Request $request)
    {

        if ($request->hasFile('leads_file')) {

            $file = $request->file('leads_file');
            $extension = $file->getClientOriginalExtension();
            if ($extension == 'csv') {
                $first_row = $this->readCsvHeader($file);
            } else {
                $first_row =  $this->readExcelHeader($file);
            }

            $users = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->where('type', '!=', 'company')->where('id', '!=', \Auth::user()->id)->get()->pluck('name', 'id');

            $pipelines = Pipeline::get()->pluck('name', 'id');

            // Render the getDiscussions partial view and store the HTML in $returnHTML
            $returnHTML = view('leads.fetchColumns')->with(['first_row' => $first_row, 'users' => $users, 'pipelines' => $pipelines])->render();


            return response()->json(['status' => 'success', 'data' => $returnHTML]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No CSV file uploaded']);
        }
    }

    public function filterHeader()
    {
        echo "<pre>";
        print_r($_POST);
        die();
    }




    public function json(Request $request)
    {
        $lead_stages = new LeadStage();
        if ($request->pipeline_id && !empty($request->pipeline_id)) {
            $lead_stages = $lead_stages->where('pipeline_id', '=', $request->pipeline_id);
            $lead_stages = $lead_stages->get()->pluck('name', 'id');
        } else {
            $lead_stages = [];
        }

        return response()->json($lead_stages);
    }

    public function fileUpload($id, Request $request)
    {

        if (\Auth::user()->can('edit lead')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                //                $request->validate(['file' => 'required|mimes:png,jpeg,jpg,pdf,doc,txt,application/octet-stream,audio/mpeg,mpga,mp3,wav|max:20480000']);
                $file_name = $request->file->getClientOriginalName();
                $file_path = $request->lead_id . "_" . md5(time()) . "_" . $request->file->getClientOriginalName();
                $request->file->storeAs('lead_files', $file_path);
                $file                 = LeadFile::create(
                    [
                        'lead_id' => $request->lead_id,
                        'file_name' => $file_name,
                        'file_path' => $file_path,
                    ]
                );
                $return               = [];
                $return['is_success'] = true;
                $return['download']   = route(
                    'leads.file.download',
                    [
                        $lead->id,
                        $file->id,
                    ]
                );
                $return['delete']     = route(
                    'leads.file.delete',
                    [
                        $lead->id,
                        $file->id,
                    ]
                );
                LeadActivityLog::create(
                    [
                        'user_id' => \Auth::user()->id,
                        'lead_id' => $lead->id,
                        'log_type' => 'Upload File',
                        'remark' => json_encode(['file_name' => $file_name]),
                    ]
                );

                return response()->json($return);
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function fileDownload($id, $file_id)
    {
        if (\Auth::user()->can('edit lead')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                $file = LeadFile::find($file_id);
                if ($file) {
                    $file_path = storage_path('lead_files/' . $file->file_path);
                    $filename  = $file->file_name;

                    return \Response::download(
                        $file_path,
                        $filename,
                        [
                            'Content-Length: ' . filesize($file_path),
                        ]
                    );
                } else {
                    return redirect()->back()->with('error', __('File is not exist.'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function fileDelete($id, $file_id)
    {
        if (\Auth::user()->can('edit lead')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                $file = LeadFile::find($file_id);
                if ($file) {
                    $path = storage_path('lead_files/' . $file->file_path);
                    if (file_exists($path)) {
                        \File::delete($path);
                    }
                    $file->delete();

                    return response()->json(['is_success' => true], 200);
                } else {
                    return response()->json(
                        [
                            'is_success' => false,
                            'error' => __('File is not exist.'),
                        ],
                        200
                    );
                }
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function noteStore($id, Request $request)
    {
        if (\Auth::user()->can('edit lead')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                $lead->notes = $request->notes;
                $lead->save();

                $data = [
                    'type' => 'info',
                    'note' => json_encode([
                                    'title' => 'Notes created',
                                    'message' => 'Notes created successfully'
                                ]),
                    'module_id' => $id,
                    'module_type' => 'lead',
                ];
                addLogActivity($data);

                return response()->json(
                    [
                        'is_success' => true,
                        'success' => __('Note successfully saved!'),
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function labels($id)
    {
        if (\Auth::user()->can('edit lead')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                //$labels   = Label::where('pipeline_id', '=', $lead->pipeline_id)->where('created_by', \Auth::user()->creatorId())->get();
                $labels   = Label::where('pipeline_id', '=', $lead->pipeline_id)->get();
                $selected = $lead->labels();
                if ($selected) {
                    $selected = $selected->pluck('name', 'id')->toArray();
                } else {
                    $selected = [];
                }

                return view('leads.labels', compact('lead', 'labels', 'selected'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function labelStore($id, Request $request)
    {
        if (\Auth::user()->can('edit lead')) {
            $leads = Lead::find($id);
            if ($leads->created_by == \Auth::user()->creatorId()) {
                if ($request->labels) {
                    $leads->labels = implode(',', $request->labels);
                } else {
                    $leads->labels = $request->labels;
                }
                $leads->save();

                return redirect()->back()->with('success', __('Labels successfully updated!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function userEdit($id)
    {
        if (\Auth::user()->can('edit lead')) {
            $lead = Lead::find($id);

            if ($lead->created_by == \Auth::user()->creatorId()) {
                $users = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->where('type', '!=', 'company')->whereNOTIn(
                    'id',
                    function ($q) use ($lead) {
                        $q->select('user_id')->from('user_leads')->where('lead_id', '=', $lead->id);
                    }
                )->get();


                $users = $users->pluck('name', 'id');

                return view('leads.users', compact('lead', 'users'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function userUpdate($id, Request $request)
    {
        if (\Auth::user()->can('edit lead')) {
            $usr  = \Auth::user();
            $lead = Lead::find($id);

            if ($lead->created_by == $usr->creatorId()) {
                if (!empty($request->users)) {
                    $users   = array_filter($request->users);
                    $leadArr = [
                        'lead_id' => $lead->id,
                        'name' => $lead->name,
                        'updated_by' => $usr->id,
                    ];

                    foreach ($users as $user) {
                        UserLead::create(
                            [
                                'lead_id' => $lead->id,
                                'user_id' => $user,
                            ]
                        );
                    }
                }

                if (!empty($users) && !empty($request->users)) {
                    return redirect()->back()->with('success', __('Users successfully updated!'));
                } else {
                    return redirect()->back()->with('error', __('Please Select Valid User!'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function userDestroy($id, $user_id)
    {
        if (\Auth::user()->can('edit lead')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                UserLead::where('lead_id', '=', $lead->id)->where('user_id', '=', $user_id)->delete();

                return redirect()->back()->with('success', __('User successfully deleted!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function getCourses()
    {

        if (\Auth::user()->can('edit lead')) {
            $id = $_POST['lead_id'];

            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                $courses = Course::where(['university_id' => $_POST['university_id']])->whereNOTIn('id',  explode(',', $lead->courses))->get();

                $returnHTML = view('leads.getCourses')->with('courses', $courses)->render();


                return json_encode([
                    'status' => true,
                    'content' => $returnHTML
                ]);
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function courseEdit($id)
    {
        if (\Auth::user()->can('edit lead')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                $universities = University::get()->pluck('name', 'id');
                $courses = Course::whereNOTIn('id',  explode(',', $lead->courses))->get()->pluck('name', 'id');
                $universities->prepend('Select University', '');
                return view('leads.courses', compact('lead', 'courses', 'universities'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function courseUpdate($id, Request $request)
    {

        if (\Auth::user()->can('edit lead')) {
            $usr        = \Auth::user();
            $lead       = Lead::find($id);
            $lead_users = $lead->users->pluck('id')->toArray();

            if ($lead->created_by == \Auth::user()->creatorId()) {
                if (!empty($request->courses)) {
                    $courses       = array_filter($request->courses);
                    $old_courses   = explode(',', $lead->courses);
                    $lead->courses = implode(',', array_merge($old_courses, $courses));
                    $lead->save();

                    $coursesobj = Course::whereIN('id', $courses)->get()->pluck('name', 'id')->toArray();

                    LeadActivityLog::create(
                        [
                            'user_id' => $usr->id,
                            'lead_id' => $lead->id,
                            'log_type' => 'Add Course',
                            'remark' => json_encode(['title' => implode(",", $coursesobj)]),
                        ]
                    );

                    $productArr = [
                        'lead_id' => $lead->id,
                        'name' => $lead->name,
                        'updated_by' => $usr->id,
                    ];
                }

                if (!empty($courses) && !empty($request->courses)) {
                    return redirect()->back()->with('success', __('Courses successfully updated!'))->with('status', 'courses');
                } else {
                    return redirect()->back()->with('error', __('Please Select Valid Course!'))->with('status', 'general');
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'courses');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'courses');
        }
    }

    public function courseDestroy($id, $course_id)
    {
        if (\Auth::user()->can('edit lead')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                $courses = explode(',', $lead->courses);
                foreach ($courses as $key => $course) {
                    if ($course_id == $course) {
                        unset($courses[$key]);
                    }
                }
                $lead->courses = implode(',', $courses);
                $lead->save();

                return redirect()->back()->with('success', __('Courses successfully deleted!'))->with('status', 'courses');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'courses');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'courses');
        }
    }

    public function productEdit($id)
    {
        if (\Auth::user()->can('edit lead')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                $products = ProductService::where('created_by', '=', \Auth::user()->creatorId())->whereNOTIn('id', explode(',', $lead->products))->get()->pluck('name', 'id');

                return view('leads.products', compact('lead', 'products'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function productUpdate($id, Request $request)
    {
        if (\Auth::user()->can('edit lead')) {
            $usr        = \Auth::user();
            $lead       = Lead::find($id);
            $lead_users = $lead->users->pluck('id')->toArray();

            if ($lead->created_by == \Auth::user()->creatorId()) {
                if (!empty($request->products)) {
                    $products       = array_filter($request->products);
                    $old_products   = explode(',', $lead->products);
                    $lead->products = implode(',', array_merge($old_products, $products));
                    $lead->save();

                    $objProduct = ProductService::whereIN('id', $products)->get()->pluck('name', 'id')->toArray();

                    LeadActivityLog::create(
                        [
                            'user_id' => $usr->id,
                            'lead_id' => $lead->id,
                            'log_type' => 'Add Product',
                            'remark' => json_encode(['title' => implode(",", $objProduct)]),
                        ]
                    );

                    $productArr = [
                        'lead_id' => $lead->id,
                        'name' => $lead->name,
                        'updated_by' => $usr->id,
                    ];
                }

                if (!empty($products) && !empty($request->products)) {
                    return redirect()->back()->with('success', __('Products successfully updated!'))->with('status', 'products');
                } else {
                    return redirect()->back()->with('error', __('Please Select Valid Product!'))->with('status', 'general');
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'products');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'products');
        }
    }

    public function productDestroy($id, $product_id)
    {
        if (\Auth::user()->can('edit lead')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                $products = explode(',', $lead->products);
                foreach ($products as $key => $product) {
                    if ($product_id == $product) {
                        unset($products[$key]);
                    }
                }
                $lead->products = implode(',', $products);
                $lead->save();

                return redirect()->back()->with('success', __('Products successfully deleted!'))->with('status', 'products');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'products');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'products');
        }
    }

    public function sourceEdit($id)
    {
        if (\Auth::user()->can('edit lead')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                //$sources = Source::where('created_by', '=', \Auth::user()->creatorId())->get();
                $sources = Source::get();
                $selected = $lead->sources();
                if ($selected) {
                    $selected = $selected->pluck('name', 'id')->toArray();
                }
                return view('leads.sources', compact('lead', 'sources', 'selected'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function sourceUpdate($id, Request $request)
    {
        if (\Auth::user()->can('edit lead')) {
            $usr        = \Auth::user();
            $lead       = Lead::find($id);
            $lead_users = $lead->users->pluck('id')->toArray();

            if ($lead->created_by == \Auth::user()->creatorId()) {
                if (!empty($request->sources) && count($request->sources) > 0) {
                    $lead->sources = implode(',', $request->sources);
                } else {
                    $lead->sources = "";
                }

                $lead->save();

                LeadActivityLog::create(
                    [
                        'user_id' => $usr->id,
                        'lead_id' => $lead->id,
                        'log_type' => 'Update Sources',
                        'remark' => json_encode(['title' => 'Update Sources']),
                    ]
                );

                $leadArr = [
                    'lead_id' => $lead->id,
                    'name' => $lead->name,
                    'updated_by' => $usr->id,
                ];

                return redirect()->back()->with('success', __('Sources successfully updated!'))->with('status', 'sources');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'sources');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'sources');
        }
    }

    public function sourceDestroy($id, $source_id)
    {
        if (\Auth::user()->can('edit lead')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                $sources = explode(',', $lead->sources);
                foreach ($sources as $key => $source) {
                    if ($source_id == $source) {
                        unset($sources[$key]);
                    }
                }
                $lead->sources = implode(',', $sources);
                $lead->save();

                return redirect()->back()->with('success', __('Sources successfully deleted!'))->with('status', 'sources');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'sources');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'sources');
        }
    }

    public function discussionCreate($id)
    {
        $lead = Lead::find($id);
        if ($lead->created_by == \Auth::user()->creatorId()) {
            return view('leads.discussions', compact('lead'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function driveCreate($id)
    {
        $lead = Lead::find($id);
        return view('leads.drive', compact('lead'));
    }


    public function driveStore(Request $request)
    {
        Lead::where('id', $request->id)->update(['drive_link' => $request->input('drive_link')]);

        $data = [
            'type' => 'info',
            'note' => json_encode([
                            'title' => 'Drive link added',
                            'message' => 'Drive link added successfully'
                        ]),
            'module_id' => $request->id,
            'module_type' => 'lead',
        ];
        addLogActivity($data);

        return redirect()->back()->with('success', __('Drive Link added successfully'));
    }

    public function driveDelete(Request $request)
    {
        Lead::where('id', $request->id)->update(['drive_link' => '']);
        return redirect()->route('leads.list')->with('success', __('Drive Link deleted successfully'));
    }


    public function notesCreate($id)
    {
        $lead = Lead::find($id);
        return view('leads.notes', compact('lead'));
    }


    public function notesStore(Request $request)
    {


        $validator = \Validator::make(
            $request->all(),
            [
                // 'title' => 'required',
                'description' => 'required'
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return json_encode([
                'status' => 'error',
                'message' =>  $messages->first()
            ]);
        }
        $id = $request->id;

        if($request->note_id != null && $request->note_id != ''){
            $note = LeadNote::where('id', $request->note_id)->first();
            // $note->title = $request->input('title');
            $note->description = $request->input('description');
            $note->update();

            $data = [
                'type' => 'info',
                'note' => json_encode([
                                'title' => 'Lead Notes Updated',
                                'message' => 'Lead notes updated successfully'
                            ]),
                'module_id' => $request->id,
                'module_type' => 'lead',
            ];
            addLogActivity($data);


            $notes = LeadNote::where('lead_id', $id)->orderBy('created_at', 'DESC')->get();
            $html = view('leads.getNotes', compact('notes'))->render();

            return json_encode([
                'status' => 'success',
                'html' => $html,
                'message' =>  __('Notes updated successfully')
            ]);
        }
        $note = new LeadNote;
        // $note->title = $request->input('title');
        $note->description = $request->input('description');
        $note->created_by = \Auth::user()->id;
        $note->lead_id = $id;
        $note->save();


        $data = [
            'type' => 'info',
            'note' => json_encode([
                            'title' => 'Notes created',
                            'message' => 'Noted created successfully'
                        ]),
            'module_id' => $id,
            'module_type' => 'lead',
        ];
        addLogActivity($data);


        $notes = LeadNote::where('lead_id', $id)->orderBy('created_at', 'DESC')->get();
        $html = view('leads.getNotes', compact('notes'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' =>  __('Notes added successfully')
        ]);

        //return redirect()->back()->with('success', __('Notes added successfully'));
    }

    public function notesEdit($id)
    {
        $note = LeadNote::where('id', $id)->first();
        return view('leads.notes_edit', compact('note'));
    }

    public function notesUpdate(Request $request, $id)
    {


        $validator = \Validator::make(
            $request->all(),
            [
                // 'title' => 'required',
                'description' => 'required'
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return json_encode([
                'status' => 'error',
                'message' =>  $messages->first()
            ]);
        }

        $note = LeadNote::where('id', $request->note_id)->first();
        $note->title = $request->input('title');
        $note->description = $request->input('description');
        $note->update();

        $data = [
            'type' => 'info',
            'note' => json_encode([
                            'title' => 'Lead Notes Updated',
                            'message' => 'Lead notes updated successfully'
                        ]),
            'module_id' => $request->id,
            'module_type' => 'lead',
        ];
        addLogActivity($data);


        $notes = LeadNote::where('lead_id', $id)->orderBy('created_at', 'DESC')->get();
        $html = view('leads.getNotes', compact('notes'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' =>  __('Notes updated successfully')
        ]);
    }


    public function notesDelete(Request $request, $id)
    {

        $note = LeadNote::where('id', $id)->first();
        $note->delete();

        $notes = LeadNote::where('lead_id', $request->lead_id)->orderBy('created_at', 'DESC')->get();
        $html = view('leads.getNotes', compact('notes'))->render();


        $data = [
            'type' => 'info',
            'note' => json_encode([
                            'title' => 'Lead Notes Deleted',
                            'message' => 'Lead notes deleted successfully'
                        ]),
            'module_id' => $request->lead_id,
            'module_type' => 'lead',
        ];
        addLogActivity($data);


        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' =>  __('Notes deleted successfully')
        ]);

        //return redirect()->route('leads.list')->with('success', __('Notes deleted successfully'));
    }





    public function discussionStore($id, Request $request)
    {
        $usr        = \Auth::user();
        $lead       = Lead::find($id);
        $lead_users = $lead->users->pluck('id')->toArray();

        if ($lead->created_by == $usr->creatorId()) {
            $discussion             = new LeadDiscussion();
            $discussion->comment    = $request->comment;
            $discussion->lead_id    = $lead->id;
            $discussion->created_by = $usr->id;
            $discussion->save();

            $leadArr = [
                'lead_id' => $lead->id,
                'name' => $lead->name,
                'updated_by' => $usr->id,
            ];

            $discussions = LeadDiscussion::select('lead_discussions.id', 'lead_discussions.comment', 'lead_discussions.created_at', 'users.name', 'users.avatar')->join('users', 'lead_discussions.created_by', 'users.id')->where(['lead_id' => $id])->orderBy('lead_discussions.created_by', 'DESC')->get()->toArray();
            $html = view('leads.getDiscussions', compact('discussions'))->render();

            return json_encode([
                'status' => 'success',
                'html' => $html,
                'total_discussions' => count($discussions),
                'message' => __('Message successfully added!')
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' =>  __('Permission Denied.')
            ]);
        }
    }

    public function order(Request $request)
    {
        if (\Auth::user()->can('move lead')) {
            $usr        = \Auth::user();
            $post       = $request->all();
            $lead       = Lead::find($post['lead_id']);
            $lead_users = $lead->users->pluck('email', 'id')->toArray();

            if ($lead->stage_id != $post['stage_id']) {
                $newStage = LeadStage::find($post['stage_id']);

                LeadActivityLog::create(
                    [
                        'user_id' => \Auth::user()->id,
                        'lead_id' => $lead->id,
                        'log_type' => 'Move',
                        'remark' => json_encode(
                            [
                                'title' => $lead->name,
                                'old_status' => $lead->stage->name,
                                'new_status' => $newStage->name,
                            ]
                        ),
                    ]
                );

                $leadArr = [
                    'lead_id' => $lead->id,
                    'name' => $lead->name,
                    'updated_by' => $usr->id,
                    'old_status' => $lead->stage->name,
                    'new_status' => $newStage->name,
                ];

                $lArr = [
                    'lead_name' => $lead->name,
                    'lead_email' => $lead->email,
                    'lead_pipeline' => $lead->pipeline->name,
                    'lead_stage' => $lead->stage->name,
                    'lead_old_stage' => $lead->stage->name,
                    'lead_new_stage' => $newStage->name,
                ];

                // Send Email
                Utility::sendEmailTemplate('Move Lead', $lead_users, $lArr);
            }

            foreach ($post['order'] as $key => $item) {
                $lead           = Lead::find($item);
                $lead->order    = $key;
                $lead->stage_id = $post['stage_id'];
                $lead->save();
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function showConvertToDeal($id)
    {

        $lead         = Lead::findOrFail($id);
        $exist_client = User::where('type', '=', 'client')->where('email', '=', $lead->email)->where('created_by', '=', \Auth::user()->creatorId())->first();
        $clients      = User::where('type', '=', 'client')->where('created_by', '=', \Auth::user()->creatorId())->get();
        $months = months();
        $currentYear = date('Y');
        $years = [];
        for ($i = 0; $i < 5; $i++) {
            $nextYear = $currentYear + $i;
            $years[$nextYear] = $nextYear;
        }

        return view('leads.convert', compact('lead','months','years', 'exist_client', 'clients'));
    }

    public function convertToDeal($id, Request $request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'client_passport' => 'required',
                'intake_month' => 'required',
                'intake_year' => 'required'
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $lead = Lead::findOrFail($id);

        $usr  = \Auth::user();
        $client = User::where('passport_number',$request->client_passport)->first();

        if($client){

        }else{

            $validator = \Validator::make(
                $request->all(),
                [
                    'client_name' => 'required',
                    'client_email' => 'required|email|unique:users,email',
                    // 'client_password' => 'required',
                    'client_passport' => 'required|unique:users,passport_number'
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $role   = Role::findByName('client');
            $client = User::create(
                [
                    'name' => $request->client_name,
                    'email' => $request->client_email,
                    'password' => \Hash::make('123456789'),
                    'brand_id' => $lead->brand_id,
                    'branch_id' => $lead->branch_id,
                    'type' => 'client',
                    'lang' => 'en',
                    'created_by' => $usr->creatorId(),
                ]
            );

            $client->passport_number =  $request->client_passport;
            $client->save();

            $client->assignRole($role);

            $cArr = [
                'email' => $request->client_email,
                'password' => $request->client_password,
            ];

        }
        // if ($request->client_check == 'exist') {
        //     $validator = \Validator::make(
        //         $request->all(),
        //         [
        //             'clients' => 'required',
        //         ]
        //     );

        //     if ($validator->fails()) {
        //         $messages = $validator->getMessageBag();

        //         return redirect()->back()->with('error', $messages->first());
        //     }

        //     $client = User::where('type', '=', 'client')->where('email', '=', $request->clients)->where('created_by', '=', $usr->creatorId())->first();

        //     if (empty($client)) {
        //         return redirect()->back()->with('error', 'Client is not available now.');
        //     }
        // } else {
        //     // $validator = \Validator::make(
        //     //     $request->all(),
        //     //     [
        //     //         'client_name' => 'required',
        //     //         'client_email' => 'required|email|unique:users,email',
        //     //         'client_password' => 'required',
        //     //         'client_passport' => 'required|unique:users,passport_number'
        //     //     ]
        //     // );

        //     // if ($validator->fails()) {
        //     //     $messages = $validator->getMessageBag();

        //     //     return redirect()->back()->with('error', $messages->first());
        //     // }

        //     // $role   = Role::findByName('client');
        //     // $client = User::create(
        //     //     [
        //     //         'name' => $request->client_name,
        //     //         'email' => $request->client_email,
        //     //         'password' => \Hash::make($request->client_password),
        //     //         'brand_id' => $lead->brand_id,
        //     //         'branch_id' => $lead->branch_id,
        //     //         'type' => 'client',
        //     //         'lang' => 'en',
        //     //         'created_by' => $usr->creatorId(),
        //     //     ]
        //     // );

        //     // $client->passport_number =  $request->client_passport;
        //     // $client->save();

        //     // $client->assignRole($role);

        //     // $cArr = [
        //     //     'email' => $request->client_email,
        //     //     'password' => $request->client_password,
        //     // ];

        //     // Send Email to client if they are new created.
        //     //Utility::sendEmailTemplate('New User', [$client->id => $client->email], $cArr);
        // }

        // Create Deal
        $stage = Stage::where('pipeline_id', $lead->pipeline_id)
            ->orderBy('order', 'asc')
            ->first();
        if (empty($stage)) {
            return redirect()->back()->with('error', __('Please Create Stage for This Pipeline.'));
        }

        $deal              = new Deal();
        $deal->name        = $request->name;
        $deal->price       = 0;
        $deal->pipeline_id = $lead->pipeline_id;
        $deal->stage_id    = $stage->id;
        $deal->sources     = in_array('sources', $request->is_transfer) ? $lead->sources : '';
        $deal->products    = in_array('products', $request->is_transfer) ? $lead->products : '';
        $deal->notes       = in_array('notes', $request->is_transfer) ? $lead->notes : '';
        $deal->labels      = $lead->labels;
        $deal->status      = 'Active';
        $deal->created_by  = $lead->created_by;
        $deal->branch_id = $lead->branch_id;
        $deal->drive_link = $lead->drive_link;
        $deal->university_id = $request->university_id;
        $deal->assigned_to = $lead->user_id;
        $deal->intake_month = $request->intake_month;
        $deal->intake_year = $request->intake_year;
        $deal->brand_id = $lead->brand_id;
        $deal->organization_id = gettype($lead->organization_id) == 'string' ? 0 : $lead->organization_id;
        $deal->organization_link = $lead->organization_link;
        $deal->save();
        // end create deal

        // Make entry in ClientDeal Table
        ClientDeal::create(
            [
                'deal_id' => $deal->id,
                'client_id' => $client->id,
            ]
        );
        // end

        $dealArr = [
            'deal_id' => $deal->id,
            'name' => $deal->name,
            'updated_by' => $usr->id,
        ];
        // Send Notification

        // Send Mail
        $pipeline = Pipeline::find($lead->pipeline_id);
        $dArr     = [
            'deal_name' => $deal->name,
            'deal_pipeline' => $pipeline->name,
            'deal_stage' => $stage->name,
            'deal_status' => $deal->status,
            'deal_price' => $usr->priceFormat($deal->price),
        ];
        Utility::sendEmailTemplate('Assign Deal', [$client->id => $client->email], $dArr);

        // Make Entry in UserDeal Table
        $leadUsers = UserLead::where('lead_id', '=', $lead->id)->get();
        foreach ($leadUsers as $leadUser) {
            UserDeal::create(
                [
                    'user_id' => $leadUser->user_id,
                    'deal_id' => $deal->id,
                ]
            );
        }
        // end

        //Transfer Lead Discussion to Deal
        if (in_array('discussion', $request->is_transfer)) {
            $discussions = LeadDiscussion::where('lead_id', '=', $lead->id)->where('created_by', '=', $usr->creatorId())->get();
            if (!empty($discussions)) {
                foreach ($discussions as $discussion) {
                    DealDiscussion::create(
                        [
                            'deal_id' => $deal->id,
                            'comment' => $discussion->comment,
                            'created_by' => $discussion->created_by,
                        ]
                    );
                }
            }
        }
        // end Transfer Discussion

        // Transfer Lead Files to Deal
        if (in_array('files', $request->is_transfer)) {
            $files = LeadFile::where('lead_id', '=', $lead->id)->get();
            if (!empty($files)) {
                foreach ($files as $file) {
                    $location     = base_path() . '/storage/lead_files/' . $file->file_path;
                    $new_location = base_path() . '/storage/deal_files/' . $file->file_path;
                    $copied       = copy($location, $new_location);

                    if ($copied) {
                        DealFile::create(
                            [
                                'deal_id' => $deal->id,
                                'file_name' => $file->file_name,
                                'file_path' => $file->file_path,
                            ]
                        );
                    }
                }
            }
        }
        // end Transfer Files

        // Transfer Lead Calls to Deal
        if (in_array('calls', $request->is_transfer)) {
            $calls = LeadCall::where('lead_id', '=', $lead->id)->get();
            if (!empty($calls)) {
                foreach ($calls as $call) {
                    DealCall::create(
                        [
                            'deal_id' => $deal->id,
                            'subject' => $call->subject,
                            'call_type' => $call->call_type,
                            'duration' => $call->duration,
                            'user_id' => $call->user_id,
                            'description' => $call->description,
                            'call_result' => $call->call_result,
                        ]
                    );
                }
            }
        }
        //end

        // Transfer Lead Emails to Deal
        if (in_array('emails', $request->is_transfer)) {
            $emails = LeadEmail::where('lead_id', '=', $lead->id)->get();
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    DealEmail::create(
                        [
                            'deal_id' => $deal->id,
                            'to' => $email->to,
                            'subject' => $email->subject,
                            'description' => $email->description,
                        ]
                    );
                }
            }
        }

        // Update is_converted field as deal_id
        $lead->is_converted = $deal->id;
        $lead->save();

        //Lead Converting Log
        $data = [
            'type' => 'info',
            'note' => json_encode([
                            'title' => 'Lead Converted',
                            'message' => 'Lead converted successfully.'
                        ]),
            'module_id' => $lead->id,
            'module_type' => 'lead',
        ];
        addLogActivity($data);

        //Deal Creating Log
        $data = [
            'type' => 'info',
            'note' => json_encode([
                            'title' => 'Deal Created',
                            'message' => 'Deal created successfully.'
                        ]),
            'module_id' => $deal->id,
            'module_type' => 'deal',
        ];
        addLogActivity($data);

        //Add Stage History
        $data_for_stage_history = [
            'stage_id' => $stage->id,
            'type_id' => $deal->id,
            'type' => 'deal'
        ];
        addLeadHistory($data_for_stage_history);



        //Slack Notification
        $setting  = Utility::settings(\Auth::user()->creatorId());
        $leadUsers = Lead::where('id', '=', $lead->id)->first();
        if (isset($setting['leadtodeal_notification']) && $setting['leadtodeal_notification'] == 1) {
            $msg = __("Deal converted through lead") . '' . $leadUsers->name . '.';
            Utility::send_slack_msg($msg);
        }

        //Telegram Notification
        $setting  = Utility::settings(\Auth::user()->creatorId());
        $leadUsers = Lead::where('id', '=', $lead->id)->first();
        if (isset($setting['telegram_leadtodeal_notification']) && $setting['telegram_leadtodeal_notification'] == 1) {
            $msg = __("Deal converted through lead") . '' . $leadUsers->name . '.';
            Utility::send_telegram_msg($msg);
        }


        return redirect()->back()->with('success', __('Lead successfully converted'));
    }

    // Lead Calls
    public function callCreate($id)
    {
        if (\Auth::user()->can('create lead call')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                $users = UserLead::where('lead_id', '=', $lead->id)->get();

                return view('leads.calls', compact('lead', 'users'));
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function callStore($id, Request $request)
    {
        if (\Auth::user()->can('create lead call')) {
            $usr  = \Auth::user();
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'subject' => 'required',
                        'call_type' => 'required',
                        'user_id' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $leadCall = LeadCall::create(
                    [
                        'lead_id' => $lead->id,
                        'subject' => $request->subject,
                        'call_type' => $request->call_type,
                        'duration' => $request->duration,
                        'user_id' => $request->user_id,
                        'description' => $request->description,
                        'call_result' => $request->call_result,
                    ]
                );

                LeadActivityLog::create(
                    [
                        'user_id' => $usr->id,
                        'lead_id' => $lead->id,
                        'log_type' => 'create lead call',
                        'remark' => json_encode(['title' => 'Create new Lead Call']),
                    ]
                );

                $leadArr = [
                    'lead_id' => $lead->id,
                    'name' => $lead->name,
                    'updated_by' => $usr->id,
                ];

                return redirect()->back()->with('success', __('Call successfully created!'))->with('status', 'calls');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'calls');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'calls');
        }
    }

    public function callEdit($id, $call_id)
    {
        if (\Auth::user()->can('edit lead call')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                $call  = LeadCall::find($call_id);
                $users = UserLead::where('lead_id', '=', $lead->id)->get();

                return view('leads.calls', compact('call', 'lead', 'users'));
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function callUpdate($id, $call_id, Request $request)
    {
        if (\Auth::user()->can('edit lead call')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'subject' => 'required',
                        'call_type' => 'required',
                        'user_id' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $call = LeadCall::find($call_id);

                $call->update(
                    [
                        'subject' => $request->subject,
                        'call_type' => $request->call_type,
                        'duration' => $request->duration,
                        'user_id' => $request->user_id,
                        'description' => $request->description,
                        'call_result' => $request->call_result,
                    ]
                );

                return redirect()->back()->with('success', __('Call successfully updated!'))->with('status', 'calls');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'calls');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'tasks');
        }
    }

    public function callDestroy($id, $call_id)
    {
        if (\Auth::user()->can('delete lead call')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                $task = LeadCall::find($call_id);
                $task->delete();

                return redirect()->back()->with('success', __('Call successfully deleted!'))->with('status', 'calls');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'calls');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'calls');
        }
    }

    // Lead email
    public function emailCreate($id)
    {
        if (\Auth::user()->can('create lead email')) {
            $lead = Lead::find($id);
            if ($lead->created_by == \Auth::user()->creatorId()) {
                return view('leads.emails', compact('lead'));
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function emailStore($id, Request $request)
    {

        if (\Auth::user()->can('create lead email')) {
            $lead = Lead::find($id);

            if ($lead->created_by == \Auth::user()->creatorId()) {
                $settings  = Utility::settings();
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'to' => 'required|email',
                        'subject' => 'required',
                        'description' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $leadEmail = LeadEmail::create(
                    [
                        'lead_id' => $lead->id,
                        'to' => $request->to,
                        'subject' => $request->subject,
                        'description' => $request->description,
                    ]
                );

                $leadEmail =
                    [
                        'lead_name' => $lead->name,
                        'to' => $request->to,
                        'subject' => $request->subject,
                        'description' => $request->description,
                    ];


                try {
                    Mail::to($request->to)->send(new SendLeadEmail($leadEmail, $settings));
                } catch (\Exception $e) {

                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                }
                //

                LeadActivityLog::create(
                    [
                        'user_id' => \Auth::user()->id,
                        'lead_id' => $lead->id,
                        'log_type' => 'create lead email',
                        'remark' => json_encode(['title' => 'Create new Deal Email']),
                    ]
                );

                return redirect()->back()->with('success', __('Email successfully created!') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''))->with('status', 'emails');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'emails');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'emails');
        }
    }

    public function driveLink(Request $request)
    {
        $id = $request->id;
        $link = $request->link;

        Lead::where('id', $id)
            ->update(['drive_link' => $link]);

        return true;
    }



    public function getLeadDetails()
    {

        $lead_id = $_GET['lead_id'];

        $lead = Lead::where('id', $lead_id)->first();

        if ($lead->is_active) {
            $calenderTasks = [];
            $deal =  Deal::where('id', '=', $lead->is_converted)->first();

            $stageCnt = LeadStage::where('pipeline_id', '=', $lead->pipeline_id)->get();

             $i = 0;
            foreach ($stageCnt as $stage) {
                $i++;
                if ($stage->id == $lead->stage_id) {
                    break;
                }
            }
            $precentage = number_format(($i * 100) / count($stageCnt));
            $lead_stages = $stageCnt;


            $tasks = \App\Models\DealTask::where(['related_to' => $lead->id, 'related_type' => 'lead'])->orderBy('status')->get();
            $branches = Branch::get()->pluck('name', 'id');
            $users = allUsers();
            $log_activities = getLogActivity($lead->id, 'lead');

            //Getting lead stages history
            $stage_histories = StageHistory::where('type', 'lead')->where('type_id', $lead->id)->pluck('stage_id')->toArray();

            $html = view('leads.leadDetail', compact('lead', 'deal', 'stageCnt', 'lead_stages', 'precentage', 'tasks', 'branches', 'users', 'log_activities', 'stage_histories'))->render();

            return json_encode([
                'status' => 'success',
                'html' => $html
            ]);
        }
    }

    public function updateLeadStage()
    {
        $lead_id = $_GET['lead_id'];
        $stage_id = $_GET['stage_id'];

        $from_stage = Lead::where('id', $lead_id)->first()->stage_id;
        $to_stage = $stage_id;
        $stages = LeadStage::pluck('name', 'id')->toArray();

        Lead::where('id', $lead_id)->update(['stage_id' => $stage_id]);


        //Add Stage History
        $data_for_stage_history = [
            'stage_id' => $stage_id,
            'type_id' => $lead_id,
            'type' => 'lead'
        ];
        addLeadHistory($data_for_stage_history);
// dd(LogActivity::find($lead_id));

        //Log
        $data = [
            'type' => 'info',
            'note' => json_encode([
                            'title' => 'Stage Updated',
                            'message' => 'Lead stage has been updated successfully from '.$stages[$from_stage].' to '.$stages[$to_stage].'.'
                        ]),
            'module_id' => $lead_id,
            'module_type' => 'lead',
        ];
        addLogActivity($data);

        return json_encode([
            'status' => 'success'
        ]);
    }


    public function updateLeadData(Request $request)
    {

        $id = $request->id;
        $name = $request->name;
        $value = $request->value;

        Lead::where('id', $id)->update([
            "$name" => $value
        ]);

        $lead = Lead::find($id);
        $data['lead'] = $lead;
        $data['name'] = $name;

        if ($name == 'organization_id') {
            $organizations = User::where('type', 'organization')->get()->pluck('name', 'id')->toArray();
            $data['organizations'] = $organizations;
        } else if ($name == 'sources') {
            $sources = Source::get()->pluck('name', 'id')->toArray();
            $data['sources'] = $sources;
        }


        $html = view('leads.lead_field_fetch', $data)->render();


        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' => 'Lead ' . $name . ' updated successfully'
        ]);
    }

    public function FetchAddress(Request $request)
    {
        $lead_id = $request->id;

        $lead = Lead::where("id", $lead_id)->first();
        $countries = $this->countries_list();
        $html = view('leads.address_edit', compact('lead', 'countries'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function saveAddress(Request $request)
    {
        $id = $request->id;
        $street = $request->street;
        $city = $request->city;
        $state = $request->state;
        $postal_code = $request->postal_code;
        $country = $request->country;

        Lead::where('id', $id)->update([
            'street' => $street,
            'city' => $city,
            'state' => $state,
            'postal_code' => $postal_code,
            'country' => $country,

        ]);

        $lead = Lead::find($id);
        $html = view('leads.address_fetch', compact('lead'))->render();

        return json_encode([
            'status' => 'success',
            'message' => 'Lead address updated successfully.',
            'html' => $html
        ]);
    }


    public function fetchLeadField(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $lead = Lead::select([$name])->where('id', $id)->first();

        $data['lead'] = $lead;
        $data['name'] = $name;

        if ($name == 'organization_id') {
            $organizations = User::where('type', 'organization')->get()->pluck('name', 'id')->toArray();
            $data['organizations'] = $organizations;
        } else if ($name == 'sources') {
            $sources = Source::get()->pluck('name', 'id')->toArray();
            $data['sources'] = $sources;
        }

        $html = view('leads.lead_field_edit', $data)->render();

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }


    public function showUpdatedConvertToDeal($id)
    {

        $lead         = Lead::findOrFail($id);
        $exist_client = User::where('type', '=', 'client')->where('email', '=', $lead->email)->where('created_by', '=', \Auth::user()->creatorId())->first();
        $clients      = User::where('type', '=', 'client')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id')->toArray();


        $employees = User::where(['created_by' => \Auth::user()->creatorId()])->where('type', '!=', 'client')->get()->pluck('name', 'id')->toArray();
        $teams = User::where('type', 'team')->get()->pluck('name', 'id')->toArray();
        $companies = User::where('type', 'company')->get()->pluck('name', 'id')->toArray();
        $categories = LeadStage::get()->pluck('name', 'id')->toArray();;
        $organizations = User::where('type', 'organization')->get()->pluck('name', 'id')->toArray();
        $deals = Deal::get()->pluck('name', 'id')->toArray();
        $universities = University::get()->pluck('name', 'id')->toArray();
        return view('leads.updated_convert', compact('lead', 'exist_client', 'clients', 'employees', 'categories', 'organizations', 'deals', 'teams', 'universities'));
    }

    public function UpdatedConvertToDeal($id, Request $request)
    {
        $lead = Lead::findOrFail($id);

        $usr  = \Auth::user();

        if ($request->client_check == 'exist') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'clients' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $client = User::where('type', '=', 'client')->where('email', '=', $request->clients)->where('created_by', '=', $usr->creatorId())->first();

            if (empty($client)) {
                return redirect()->back()->with('error', 'Client is not available now.');
            }
        } else {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'passport_number' => 'required|unique:users,passport_number',
                    'assigned_to' => 'required',
                    'task_name' => 'required',
                    'date_due' => 'required',
                    'lead_stage_id' => 'required'
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return json_encode([
                    'status' => 'success',
                    'message' => $messages->first()
                ]);
            }

            //check application exist or not
            $university = University::findOrFail($request->input('university_id'));
            $app_key = $request->input('passport_number') . '-' . str_replace(' ', '-', $university->name);

            $app_exist = \App\Models\DealApplication::where('application_key', $app_key)->first();
            if ($app_exist) {
                return json_encode([
                    'status' => 'error',
                    'message' => __('Lead application already exist for the selected university.')
                ]);
            }

            //check user with the existing email
            $check_user = User::where('email', $lead->email)->first();
            if ($check_user) {
                return json_encode([
                    'status' => 'error',
                    'message' => __('Contact already exist for the lead')
                ]);
            }


            $role   = Role::findByName('client');
            $client = User::create(
                [
                    'name' => $request->name,
                    'passport_number' => $request->passport_number,
                    'email' => $lead->email,
                    'password' => \Hash::make('123456789'),
                    'type' => 'client',
                    'lang' => 'en',
                    'created_by' => $usr->creatorId(),
                ]
            );
            $client->assignRole($role);

            // Send Email to client if they are new created.
            //Utility::sendEmailTemplate('New User', [$client->id => $client->email], $cArr);
        }

        // Create Deal
        $stage = Stage::where('pipeline_id', $lead->pipeline_id)
            ->orderBy('order', 'asc')
            ->first();
        if (empty($stage)) {
            return json_encode([
                'status' => 'success',
                'message' => __('Please Create Stage for This Pipeline.')
            ]);
        }

        $deal              = new Deal();
        $deal->name        = $request->name;
        $deal->price       = 0;
        $deal->pipeline_id = $lead->pipeline_id;
        $deal->stage_id    = $request->lead_stage_id;
        $deal->sources     = $lead->sources;
        $deal->products    = $lead->products;
        $deal->notes       = $lead->notes;
        $deal->labels      = $lead->labels;
        $deal->branch_id = $lead->branch_id;
        $deal->university_id = $request->university_id;
        $deal->assigned_to = $request->assigned_to;
        $deal->organization_id = $lead->organization_id;
        $deal->organization_link = $lead->organization_link;
        $deal->status      = 'Active';
        $deal->created_by  = $lead->created_by;
        $deal->save();
        // end create deal


        // $application = new DealApplication();
        // $application->application_key =  $app_key;
        // $application->university_id = $request->input('university_id');
        // $application->deal_id = $deal->id;
        // $application->course = '';
        // $application->status = 'pending';
        // $application->save();



        // Make entry in ClientDeal Table
        ClientDeal::create(
            [
                'deal_id' => $deal->id,
                'client_id' => $client->id,
            ]
        );
        // end


        // Send Mail
        $pipeline = Pipeline::find($lead->pipeline_id);
        $dArr     = [
            'deal_name' => $deal->name,
            'deal_pipeline' => $pipeline->name,
            'deal_stage' => $stage->name,
            'deal_status' => $deal->status,
            'deal_price' => $usr->priceFormat($deal->price),
        ];
        Utility::sendEmailTemplate('Assign Deal', [$client->id => $client->email], $dArr);

        // Make Entry in UserDeal Table
        $leadUsers = UserLead::where('lead_id', '=', $lead->id)->get();
        foreach ($leadUsers as $leadUser) {
            UserDeal::create(
                [
                    'user_id' => $leadUser->user_id,
                    'deal_id' => $deal->id,
                ]
            );
        }
        // end


        $deal_stage = Stage::first();

        //Creating Task
        $task = new \App\Models\DealTask();
        $task->deal_id = $deal->id;
        $task->name = $request->task_name;
        $task->priority = !empty($request->input('task_priority')) ? $request->input('task_priority') : 1;
        $task->status = 0;
        $task->branch_id = $lead->branch_id;
        $task->organization_id = $request->organization_id;
        $task->assigned_to = $request->assigned_to;
        $task->assigned_type = 'individual';
        $task->related_type = 'deal';
        $task->related_to = $deal->id;
        $task->deal_stage_id = $deal_stage->id;
        $task->due_date = $request->date_due;
        $task->start_date = isset($request->start_date) && !empty($request->start_date) ? $request->start_date : date('Y-m-d');
        $task->remainder_date = isset($request->remainder_date) && !empty($request->remainder_date) ? $request->remainder_date : date('Y-m-d');
        $task->visibility = 'public';
        $task->save();

        $data = [
            'type' => 'info',
            'note' => json_encode([
                            'title' => 'Task created',
                            'message' => 'Task converted successfully.'
                        ]),
            'module_id' => $task->id,
            'module_type' => 'task',
        ];
        addLogActivity($data);






        //Transfer Lead Discussion to Deal
        //if (in_array('discussion', $request->is_transfer)) {
        $discussions = LeadDiscussion::where('lead_id', '=', $lead->id)->where('created_by', '=', $usr->creatorId())->get();
        if (!empty($discussions)) {
            foreach ($discussions as $discussion) {
                DealDiscussion::create(
                    [
                        'deal_id' => $deal->id,
                        'comment' => $discussion->comment,
                        'created_by' => $discussion->created_by,
                    ]
                );
            }
        }
        // }
        // end Transfer Discussion

        // Transfer Lead Files to Deal
        // if (in_array('files', $request->is_transfer)) {
        $files = LeadFile::where('lead_id', '=', $lead->id)->get();
        if (!empty($files)) {
            foreach ($files as $file) {
                $location     = base_path() . '/storage/lead_files/' . $file->file_path;
                $new_location = base_path() . '/storage/deal_files/' . $file->file_path;
                $copied       = copy($location, $new_location);

                if ($copied) {
                    DealFile::create(
                        [
                            'deal_id' => $deal->id,
                            'file_name' => $file->file_name,
                            'file_path' => $file->file_path,
                        ]
                    );
                }
            }
        }
        //}
        // end Transfer Files

        // Transfer Lead Calls to Deal
        //if (in_array('calls', $request->is_transfer)) {
        $calls = LeadCall::where('lead_id', '=', $lead->id)->get();
        if (!empty($calls)) {
            foreach ($calls as $call) {
                DealCall::create(
                    [
                        'deal_id' => $deal->id,
                        'subject' => $call->subject,
                        'call_type' => $call->call_type,
                        'duration' => $call->duration,
                        'user_id' => $call->user_id,
                        'description' => $call->description,
                        'call_result' => $call->call_result,
                    ]
                );
            }
        }
        //}
        //end

        // Transfer Lead Emails to Deal
        //if (in_array('emails', $request->is_transfer)) {
        $emails = LeadEmail::where('lead_id', '=', $lead->id)->get();
        if (!empty($emails)) {
            foreach ($emails as $email) {
                DealEmail::create(
                    [
                        'deal_id' => $deal->id,
                        'to' => $email->to,
                        'subject' => $email->subject,
                        'description' => $email->description,
                    ]
                );
            }
        }
        // }

        // Update is_converted field as deal_id
        $lead->is_converted = $deal->id;
        $lead->save();

        //Slack Notification
        $setting  = Utility::settings(\Auth::user()->creatorId());
        $leadUsers = Lead::where('id', '=', $lead->id)->first();
        if (isset($setting['leadtodeal_notification']) && $setting['leadtodeal_notification'] == 1) {
            $msg = __("Deal converted through lead") . '' . $leadUsers->name . '.';
            Utility::send_slack_msg($msg);
        }

        //Telegram Notification
        $setting  = Utility::settings(\Auth::user()->creatorId());
        $leadUsers = Lead::where('id', '=', $lead->id)->first();
        if (isset($setting['telegram_leadtodeal_notification']) && $setting['telegram_leadtodeal_notification'] == 1) {
            $msg = __("Deal converted through lead") . '' . $leadUsers->name . '.';
            Utility::send_telegram_msg($msg);
        }

        $data = [
            'type' => 'info',
            'note' => json_encode([
                            'title' => 'Lead Converted',
                            'message' => 'Lead converted successfully.'
                        ]),
            'module_id' => $lead->id,
            'module_type' => 'lead',
        ];
        addLogActivity($data);

        return json_encode([
            'status' => 'success',
            'message' => __('Lead successfully converted')
        ]);
    }

    public function deleteBulkLeads(Request $request){

        if($request->ids != null){
            Lead::whereIn('id', explode(',', $request->ids))->delete();
            return redirect()->route('leads.list')->with('success', 'Leads deleted successfully');
        }else{
            return redirect()->route('leads.list')->with('error', 'Atleast select 1 lead.');
        }

    }

    public function updateBulkLead(Request $request){

        $ids = explode(',',$request->lead_ids);

        if(isset($request->lead_first_name)){

            Lead::whereIn('id',$ids)->update(['name' => $request->lead_first_name.' '. $request->lead_last_name]);
            return redirect()->route('leads.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->lead_stage)){

            Lead::whereIn('id',$ids)->update(['stage_id' => $request->lead_stage]);
            return redirect()->route('leads.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->lead_assgigned_user)){

            Lead::whereIn('id',$ids)->update(['user_id' => $request->lead_assgigned_user]);
            return redirect()->route('leads.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->lead_branch)){

            Lead::whereIn('id',$ids)->update(['branch_id' => $request->lead_branch]);
            return redirect()->route('leads.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->lead_organization)){

            Lead::whereIn('id',$ids)->update(['organization_id' => $request->lead_organization]);
            return redirect()->route('leads.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->lead_source)){

            Lead::whereIn('id',$ids)->update(['sources' => $request->lead_source]);
            return redirect()->route('leads.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->lead_email)){

            Lead::whereIn('id',$ids)->update(['email' => $request->lead_email]);
            return redirect()->route('leads.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->referrer_email)){

            Lead::whereIn('id',$ids)->update(['referrer_email' => $request->referrer_email]);
            return redirect()->route('leads.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->lead_phone)){

            Lead::whereIn('id',$ids)->update(['phone' => $request->lead_phone]);
            return redirect()->route('leads.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->lead_mobile_phone)){

            Lead::whereIn('id',$ids)->update(['mobile_phone' => $request->lead_mobile_phone]);
            return redirect()->route('leads.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->lead_description)){

            Lead::whereIn('id',$ids)->update(['keynotes' => $request->lead_description]);
            return redirect()->route('leads.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->lead_tags_list)){

            Lead::whereIn('id',$ids)->update(['tags' => $request->lead_tags_list]);
            return redirect()->route('leads.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->lead_street)){

            Lead::whereIn('id',$ids)->update([
                                            'street' => $request->lead_street,
                                            'city' => $request->lead_city,
                                            'state' => $request->lead_state,
                                            'postal_code' => $request->lead_postal_code,
                                            'country' => $request->lead_country
                                        ]);
            return redirect()->route('leads.list')->with('success', 'Leads updated successfully');

        }
    }
}
