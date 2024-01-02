<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\User;
use App\Models\Label;
use App\Models\Stage;
use App\Models\Branch;
use App\Models\Course;
use App\Models\Source;
use App\Models\Utility;
use App\Models\DealCall;
use App\Models\DealFile;
use App\Models\DealNote;
use App\Models\DealTask;
use App\Models\Notification;
use App\Models\Pipeline;
use App\Models\UserDeal;
use App\Models\DealEmail;
use App\Models\ClientDeal;
use App\Models\University;
use App\Mail\SendDealEmail;
use App\Models\ActivityLog;
use App\Models\CustomField;
use App\Models\StageHistory;
use Illuminate\Http\Request;
use App\Models\DealDiscussion;
use App\Models\ProductService;
use App\Models\TaskDiscussion;
use App\Models\DealApplication;
use App\Models\ClientPermission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\CompanyPermission;
use App\Models\Region;

class DealController extends Controller
{
    /**
     * Display a listing of the redeal.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {

        $usr      = \Auth::user();

        //$pipeline = Pipeline::where('id', '=', $usr->default_pipeline)->first();
        $pipeline = Pipeline::get();



        if ($usr->can('manage deal') || $usr->type == 'super admin') {
            if ($usr->default_pipeline) {
                //$pipeline = Pipeline::where('id', '=', $usr->default_pipeline)->first();
                $pipeline = Pipeline::where('id', '=', $usr->default_pipeline)->first();

                if (!$pipeline) {
                    $pipeline = Pipeline::first();
                }
            } else {
                $pipeline = Pipeline::first();
            }

            //$pipelines = Pipeline::get()->pluck('name', 'id');
            $pipelines = Pipeline::get()->pluck('name', 'id');

            if ($usr->type == 'client') {
                $id_deals = $usr->clientDeals->pluck('id');
            } else {
                $id_deals = $usr->deals->pluck('id');
            }


            $deals       = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->get();
            $curr_month  = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereMonth('created_at', '=', date('m'))->get();
            $curr_week   = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereBetween(
                'created_at',
                [
                    \Carbon\Carbon::now()->startOfWeek(),
                    \Carbon\Carbon::now()->endOfWeek(),
                ]
            )->get();
            $last_30days = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(30))->get();



            // Deal Summary
            $cnt_deal                = [];
            $cnt_deal['total']       = Deal::getDealSummary($deals);
            $cnt_deal['this_month']  = Deal::getDealSummary($curr_month);
            $cnt_deal['this_week']   = Deal::getDealSummary($curr_week);
            $cnt_deal['last_30days'] = Deal::getDealSummary($last_30days);

            $total_records = Deal::count();


            if ($usr->can('view all deals') || \Auth::user()->type == 'super admin') {
                $total_records =  Deal::select('deals.*')->count();
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

                $total_records = Deal::select('deals.*')->whereIn('created_by', $deal_created_by)->count();
            } else {
                $deal_created_by[] = \auth::user()->created_by;
                $deal_created_by[] = $usr->id;

                $deal1_query = Deal::select('deals.*');
                $total_records = $deal1_query->whereIn('created_by', $deal_created_by)->count();
            }


            return view('deals.index', compact('pipelines', 'pipeline', 'cnt_deal', 'total_records'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function getDiscussions()
    {
        $usr = \Auth::user();
        if ($usr->can('manage deal')) {
            //deal_discussions.id, deal_discussions.comment, deal_discussions.created_at, user.name, user.avatar
            $discussions = DealDiscussion::select('deal_discussions.id', 'deal_discussions.comment', 'deal_discussions.created_at', 'users.name', 'users.avatar')->join('users', 'deal_discussions.created_by', 'users.id')->where(['deal_id' => $_POST['deal_id']])->get()->toArray();


            $diss = [];
            foreach ($discussions as $discussion) {
                $diss[] = [
                    'comment' => $discussion['comment'],
                    'name' => $discussion['name'],
                    'avatar' => $discussion['avatar'],
                    'created_at' => \Carbon\Carbon::parse($discussion['created_at'])->diffForHumans()
                ];
            }
            $returnHTML = view('deals.getDiscussions')->with('discussions', $diss)->render();


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
        if ($usr->can('manage deal')) {

            $discussion = new DealDiscussion();
            $discussion->deal_id = $_POST['deal_id'];
            $discussion->comment = $_POST['discussion'];
            $discussion->created_by = $usr->id;

            $discussion->save();

            $diss[] = [
                'comment' => $_POST['discussion'],
                'name' => $usr->name,
                'avatar' => $usr->avatar,
                'created_at' => \Carbon\Carbon::parse(date('Y-m-d H:i:s'))->diffForHumans()
            ];
            $returnHTML = view('deals.getDiscussions')->with('discussions', $diss)->render();

            return json_encode([
                'status' => true,
                'content' => $returnHTML
            ]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    private function dealFilters()
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

        if (isset($_GET['created_at']) && !empty($_GET['created_at'])) {
            $filters['created_at'] = $_GET['created_at'];
        }

        if (isset($_GET['price']) && !empty($_GET['price'])) {
            $price = $_GET['price'];

            if (preg_match('/^(<=|>=|<|>)/', $price, $matches)) {
                $comparePrice = $matches[1]; // Get the comparison operator
                $filters['price'] = (float) substr($price, strlen($comparePrice)); // Get the price value
            } else {
                $comparePrice = '=';
                $filters['price'] = '=' . $price; // Default to '=' if no comparison operator is provided
            }
        }

        return $filters;
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
    public function deal_list()
    {
        $usr = \Auth::user();
        $cnt_deal = [];
        $comparePrice = '';

        $start = 0;
        $num_results_on_page = 25;
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $num_of_result_per_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = ($page - 1) * $num_results_on_page;
        } else {
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
        }

        $filters = $this->dealFilters();

        if ($usr->can('manage deal') || \Auth::user()->type == 'super admin' || \Auth::user()->type == 'company') {

            //whole query
            $deals_query = Deal::select('deals.*')->join('user_deals', 'user_deals.deal_id', '=', 'deals.id');
            foreach ($filters as $column => $value) {
                if ($column === 'name') {
                    $deals_query->whereIn('name', $value);
                } elseif ($column === 'stage_id') {
                    $deals_query->whereIn('stage_id', $value);
                } elseif ($column == 'users') {
                    $deals_query->whereIn('created_by', $value);
                } elseif ($column == 'created_at') {
                    $deals_query->whereDate('deals.created_at', 'LIKE', '%' . substr($value, 0, 10) . '%');
                }
            }

            $companies = FiltersBrands();
            $brand_ids = array_keys($companies);
            $deals_query->whereIn('brand_id', $brand_ids);
            $deals_query->orWhere('assigned_to', \Auth::user()->id);

            //if list global search
            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true' && isset($_GET['search']) && !empty($_GET['search'])) {
                $g_search = $_GET['search'];
                $deals_query->Where('deals.name', 'like', '%' . $g_search . '%');
                //$deals_query->orWhere('deals.email', 'like', '%' . $g_search . '%');
                $deals_query->orWhere('deals.phone', 'like', '%' . $g_search . '%');
            }

            $total_records = $deals_query->count();

            $deals_query->orderBy('deals.id', 'DESC')->skip($start)->take($num_results_on_page);
            $deals = $deals_query->get();

            $stages = Stage::get()->pluck('name', 'id')->toArray();
            $organizations = User::where('type', 'organization')->get()->pluck('name', 'id')->toArray();
            $brands = $companies;
            $users = allUsers();
            $sources = Source::get()->pluck('name', 'id')->toArray();
            $months = months();
            $currentYear = date('Y');
            $years = [];
            for ($i = 0; $i < 5; $i++) {
                $nextYear = $currentYear + $i;
                $years[$nextYear] = $nextYear;
            }
            $universities = University::get()->pluck('name', 'id')->toArray();

            $clients = array();
            $branches = array();
            $branches = Branch::get()->pluck('name', 'id')->toArray();
            if (\Auth::user()->type == 'super admin') {
                $clients      = User::where('type', 'client')->get()->pluck('name', 'id');
            } else {
                $clients      = User::where('created_by', '=', \Auth::user()->ownerId())->where('type', 'client')->get()->pluck('name', 'id');
            }

            $organizations = User::where('type', 'organization')->get()->pluck('name', 'id')->toArray();
            $pipelines = Pipeline::get()->pluck('name', 'id')->toArray();
            $stages = Stage::get()->pluck('name', 'id')->toArray();

            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
                $html = view('deals.deals_list_ajax', compact('deals','pipelines','users','stages','branches','universities', 'organizations', 'stages', 'users', 'total_records', 'sources'))->render();

                return json_encode([
                    'status' => 'success',
                    'html' => $html
                ]);
            }

            // dd($universities);

            return view('deals.list', compact('deals','universities','pipelines','users','branches','months','years','clients', 'organizations', 'stages', 'users', 'total_records', 'sources', 'brands'));
        }


        if ($usr->can('manage deal') || \Auth::user()->type == 'super admin') {
            if (\Auth::user()->type == 'super admin') {
                $deals_query = Deal::select('deals.*')->join('user_deals', 'user_deals.deal_id', '=', 'deals.id');

                foreach ($filters as $column => $value) {
                    if ($column === 'name') {
                        $deals_query->whereIn('name', $value);
                    } elseif ($column === 'deals.stage_id') {
                        $deals_query->whereIn('stage_id', $value);
                    } elseif ($column === 'price') {
                        $value = str_replace($comparePrice, '', $value);
                        $deals_query->where('price', $comparePrice, $value);
                    } elseif ($column == 'users') {
                        $deals_query->whereIn('created_by', $value);
                    } elseif ($column == 'created_at') {
                        $deals_query->whereDate('created_at', 'LIKE', '%' . substr($value, 0, 10) . '%');
                    }
                }
                $pipeline = Pipeline::get();
                $pipelines = Pipeline::get()->pluck('name', 'id');
                $id_deals = $usr->deals->pluck('id');
                $deals = $deals_query->where('deals.pipeline_id', '=', $pipeline[0]->id)->orderBy('deals.order')->orderBy('deals.id', 'DESC')->skip($start)->take($num_results_on_page)->get();

                // $deals = Deal::select('deals.*')->orderBy('deals.created_ats', 'desc')->skip($start)->take($num_results_on_page)->get();
            } else {

                if ($usr->default_pipeline) {
                    //$pipeline = Pipeline::where('id', '=', $usr->default_pipeline)->first();
                    $pipeline = Pipeline::where('id', '=', $usr->default_pipeline)->first();
                    if (!$pipeline) {
                        $pipeline = Pipeline::first();
                    }
                } else {
                    $pipeline = Pipeline::first();
                }

                // $pipelines = Pipeline::get()->pluck('name', 'id');
                $pipelines = Pipeline::get()->pluck('name', 'id');

                if ($usr->type == 'client') {
                    $id_deals = $usr->clientDeals->pluck('id');
                } else {
                    $id_deals = $usr->deals->pluck('id');
                }

                //check filters
                $filters = [];
                if (isset($_GET['name']) && !empty($_GET['name'])) {
                    $filters['name'] = $_GET['name'];
                }


                if (isset($_GET['price']) && !empty($_GET['price'])) {
                    $price = $_GET['price'];

                    if (preg_match('/^(<=|>=|<|>)/', $price, $matches)) {
                        $comparePrice = $matches[1]; // Get the comparison operator
                        $filters['price'] = (float) substr($price, strlen($comparePrice)); // Get the price value
                    } else {
                        $comparePrice = '=';
                        $filters['price'] = '=' . $price; // Default to '=' if no comparison operator is provided
                    }
                }


                if (isset($_GET['stages']) && !empty($_GET['stages'])) {
                    $filters['deals.stage_id'] = $_GET['stages'];
                }

                $deal1_query = Deal::whereIn('id', $id_deals);
                $deals_query = Deal::select('deals.*')->join('user_deals', 'user_deals.deal_id', '=', 'deals.id')->where('deals.pipeline_id', '=', $pipeline->id);
                $curr_month_query = Deal::whereIn('id', $id_deals)->where($filters)->where('pipeline_id', '=', $pipeline->id)->whereMonth('created_at', '=', date('m'));
                $curr_week_query   = Deal::whereIn('id', $id_deals)->where($filters)->where('pipeline_id', '=', $pipeline->id)->whereBetween(
                    'created_at',
                    [
                        \Carbon\Carbon::now()->startOfWeek(),
                        \Carbon\Carbon::now()->endOfWeek(),
                    ]
                );
                $last_30days_query = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(30));

                // Add the dynamic filters
                foreach ($filters as $column => $value) {
                    if ($column === 'name') {
                        $deal1_query->whereIn('name', $value);
                        $deals_query->whereIn('name', $value);
                        $curr_month_query->whereIn('name', $value);
                        $curr_week_query->whereIn('name', $value);
                        $last_30days_query->whereIn('name', $value);
                    } elseif ($column === 'deals.stage_id') {
                        $deal1_query->whereIn('stage_id', $value);
                        $deals_query->whereIn('stage_id', $value);
                        $curr_month_query->whereIn('stage_id', $value);
                        $curr_week_query->whereIn('stage_id', $value);
                        $last_30days_query->whereIn('stage_id', $value);
                    } elseif ($column === 'price') {
                        $value = str_replace($comparePrice, '', $value);
                        $deal1_query->where('price', $comparePrice, $value);
                        $deals_query->where('price', $comparePrice, $value);
                        $curr_month_query->where('price', $comparePrice, $value);
                        $curr_week_query->where('price', $comparePrice, $value);
                        $last_30days_query->where('price', $comparePrice, $value);
                    } elseif ($column === 'users') {
                        $value = str_replace($comparePrice, '', $value);
                        $deal1_query->where('created_by',  $value);
                        $deals_query->where('created_by',  $value);
                        $curr_month_query->where('created_by',  $value);
                        $curr_week_query->where('created_by',  $value);
                        $last_30days_query->where('created_by',  $value);
                    } elseif ($column == 'created_at') {
                        $deals_query->whereDate('deals.created_at', 'LIKE', '%' . substr($value, 0, 10) . '%');
                    }
                }
                $deals = $deal1_query->get();
                $curr_month = $curr_month_query->get();
                $curr_week = $curr_week_query->get();
                $last_30days = $last_30days_query->get();

                // Deal Summary
                $cnt_deal['total']       = Deal::getDealSummary($deals);
                $cnt_deal['this_month']  = Deal::getDealSummary($curr_month);
                $cnt_deal['this_week']   = Deal::getDealSummary($curr_week);
                $cnt_deal['last_30days'] = Deal::getDealSummary($last_30days);

                // Deals
                // if ($usr->type == 'client') {
                //     $deals_query->where('client_deals.client_id', '=', $usr->id);
                //     $deals_query->orderBy('deals.created_at', 'desc');
                // } else {

                //     $deals_query->where('user_deals.user_ids', '=', $usr->id);
                //     $deals_query->orderBy('deals.order');
                // }





                if ($usr->can('view all deals')) {
                    $companies = User::get()->pluck('name', 'id');
                } else if (\auth::user()->type == "company") {
                    $users = User::select(['users.id', 'users.name'])->join('roles', 'roles.name', '=', 'users.type')
                        ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
                        ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                        ->where(['users.created_by' => \auth::id(), 'permissions.name' => 'create deal'])
                        ->groupBy('users.id')
                        ->pluck('name', 'id')
                        ->toArray();

                    $users[$usr->id] = $usr->name;
                    $companies = $users;
                    $deal_created_by = array_keys($users);
                    $deals_query->whereIn('user_deals.user_id', $deal_created_by);
                } else {
                    $deal_created_by[] = \auth::user()->created_by;
                    $deal_created_by[] = $usr->id;
                    $deals_query->whereIn('user_deals.user_id', $deal_created_by);
                    $companies = User::where('id', $usr->id)->get()->pluck('name', 'id');
                }
                $total_records = count($deals_query->groupBy('deals.id')->get());

                $deals = $deals_query->groupBy('deals.id')->orderBy('deals.order')->orderBy('deals.id', 'DESC')->skip($start)->take($num_results_on_page)->get();
            } //end check role

            $users = User::get()->pluck('name', 'id');
            $stages = Stage::get();
            return view('deals.list', compact('pipelines', 'pipeline', 'deals', 'cnt_deal', 'users', 'stages', 'total_records', 'companies'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new redeal.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->can('create deal') || \Auth::user()->type == 'super admin') {
            if (\Auth::user()->type == 'super admin') {
                $branches = Branch::get()->pluck('name', 'id')->toArray();
                $clients      = User::where('type', 'client')->get()->pluck('name', 'id');
            } else {
                $branches = Branch::where('created_at',  \Auth::user()->id)->get()->pluck('name', 'id')->toArray();
                $clients      = User::where('created_by', '=', \Auth::user()->ownerId())->where('type', 'client')->get()->pluck('name', 'id');
            }

            $customFields = CustomField::where('module', '=', 'deal')->get();


            $months = [
                'JAN' => 'January',
                'FEB' => 'February',
                'MAR' => 'March',
                'APR' => 'April',
                'MAY' => 'May',
                'JUN' => 'June',
                'JUL' => 'July',
                'AUG' => 'August',
                'SEP' => 'September',
                'OCT' => 'October',
                'NOV' => 'November',
                'DEC' => 'December'
            ];

            $currentYear = date('Y');
            $years = [];
            for ($i = 0; $i < 5; $i++) {
                $nextYear = $currentYear + $i;
                $years[$nextYear] = $nextYear;
            }

            $companies = User::where('type', 'company')->get()->pluck('name', 'id')->toArray();
            $universities = University::get()->pluck('name', 'id')->toArray();
            $organizations = User::where('type', 'organization')->get()->pluck('name', 'id')->toArray();
            $pipelines = Pipeline::get()->pluck('name', 'id')->toArray();
            $stages = Stage::get()->pluck('name', 'id')->toArray();
            $users = User::where('type', 'employee')->get()->pluck('name', 'id');

            return view('deals.create', compact('clients', 'customFields', 'months', 'years', 'companies', 'universities', 'branches', 'pipelines', 'stages', 'users', 'organizations'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created redeal in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usr = \Auth::user();
        if ($usr->can('create deal') || \Auth::user()->type == 'super admin') {
            $countDeal = Deal::where('created_by', '=', $usr->ownerId())->count();
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'intake_month' => 'required',
                    'intake_year' => 'required',
                    'contact' => 'required',
                    'assigned_to' => 'required',
                    'category' => 'required',
                    'university_id' => 'required',
                    'organization_id' => 'required',
                    'branch_id' => 'required',
                    'pipeline_id' => 'required',
                    'stage_id' => 'required'
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return json_encode([
                    'status' => 'success',
                    'message' => $messages->first()
                ]);
            }

            $deal        = new Deal();
            $deal->name  = $request->name;
            $deal->assigned_to = $request->input('assigned_to');
            $deal->category = $request->input('category');
            $deal->university_id = $request->input('university_id');
            $deal->organization_id = $request->input('organization_id');
            $deal->branch_id = $request->input('branch_id');
            $deal->intake_month = $request->input('intake_month');
            $deal->intake_year = $request->input('intake_year');
            $deal->price = 0;
            $deal->pipeline_id = $request->input('pipeline_id');
            $deal->stage_id    = $request->input('stage_id');
            $deal->description = $request->input('deal_description');
            $deal->status      = 'Active';
            $deal->created_by  = $usr->ownerId();
            $deal->save();

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
            'stage_id' => $request->input('stage_id'),
            'type_id' => $deal->id,
            'type' => 'deal'
        ];
        addLeadHistory($data_for_stage_history);

            //send email
            $clients = User::whereIN('id', array_filter($request->input('contact')))->get()->pluck('email', 'id')->toArray();
            foreach (array_keys($clients) as $client) {
                ClientDeal::create(
                    [
                        'deal_id' => $deal->id,
                        'client_id' => $client,
                    ]
                );
            }

            if ($usr->type == 'company') {
                $usrDeals = [
                    $usr->id,

                ];
            } else {
                $usrDeals = [
                    $usr->id,
                    $usr->ownerId()
                ];
            }

            foreach ($usrDeals as $usrDeal) {
                UserDeal::create(
                    [
                        'user_id' => $usrDeal,
                        'deal_id' => $deal->id,
                    ]
                );
            }

            $pipeline = Pipeline::where('id', $request->input('pipeline_id'))->first();
            $stage = Stage::findOrFail($request->input('stage_id'));


            // CustomField::saveData($deal, $request->customField);

            // Send Email
            $setings = Utility::settings();

            if ($setings['deal_assigned'] == 1) {
                $clients = User::whereIN('id', array_filter($request->input('contact')))->get()->pluck('email', 'id')->toArray();
                $dealAssignArr = [
                    'deal_name' => $deal->name,
                    'deal_pipeline' => $pipeline->name,
                    'deal_stage' => $stage->name,
                    'deal_status' => $deal->status,
                    'deal_price' => $usr->priceFormat($deal->price),
                ];
                $resp = Utility::sendEmailTemplate('deal_assigned',  $clients, $dealAssignArr);

                $data = [
                    'type' => 'info',
                    'note' => json_encode([
                                    'title' => 'Deal Created',
                                    'message' => 'Deal created successfully.'
                                ]),
                    'module_id' => $deal->id,
                    'module_type' => 'lead',
                ];
                addLogActivity($data);


                return json_encode([
                    'status' => 'success',
                    'deal' => $deal,
                    'message' => __('Deal successfully created!')  . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : '')
                ]);
            }

            //Slack Notification
            $setting  = Utility::settings(\Auth::user()->creatorId());
            if (isset($setting['deal_notification']) && $setting['deal_notification'] == 1) {
                $msg = __("New Deal created by") . ' ' . \Auth::user()->name . '.';
                Utility::send_slack_msg($msg);
            }

            //Telegram Notification
            $setting  = Utility::settings(\Auth::user()->creatorId());
            if (isset($setting['telegram_deal_notification']) && $setting['telegram_deal_notification'] == 1) {
                $msg = __("New Deal created by") . ' ' . \Auth::user()->name . '.';
                Utility::send_telegram_msg($msg);
            }

            return json_encode([
                'status' => 'success',
                'deal' => $deal,
                'message' => __('Deal successfully created!')
            ]);
        } else {

            return json_encode([
                'status' => 'error',
                'message' => __('Permission Denied.')
            ]);
        }
    }

    /**
     * Display the specified redeal.
     *
     * @param \App\Deal $deal
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Deal $deal)
    {
        if ($deal->is_active) {
            $calenderTasks = [];
            if (\Auth::user()->can('view task')) {
                foreach ($deal->tasks as $task) {
                    $calenderTasks[] = [
                        'title' => $task->name,
                        'start' => $task->date,
                        'url' => route(
                            'deals.tasks.show',
                            [
                                $deal->id,
                                $task->id,
                            ]
                        ),
                        'className' => ($task->status) ? 'bg-success border-success' : 'bg-warning border-warning',
                    ];
                }
            }
            $permission        = [];
            $customFields      = CustomField::where('module', '=', 'deal')->get();
            $deal->customField = CustomField::getData($deal, 'deal')->toArray();
            $applications = DealApplication::where('deal_id', $deal->id)->get();

            return view('deals.show', compact('deal', 'customFields', 'calenderTasks', 'permission', 'applications'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for editing the specified redeal.
     *
     * @param \App\Deal $deal
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Deal $deal)
    {

        // if (\Auth::user()->can('edit deal') || \Auth::user()->type == 'super admin') {
        if (\Auth::user()->can('edit deal')) {

            if (\Auth::user()->can('edit deal') || \Auth::user()->type == 'super admin') {
                $pipelines         = Pipeline::get()->pluck('name', 'id')->toArray();
                $sources           = Source::get()->pluck('name', 'id')->toArray();


                if (\Auth::user()->type == 'super admin') {
                    $branches = Branch::get()->pluck('name', 'id')->toArray();
                    $clients      = User::where('type', 'client')->get()->pluck('name', 'id');
                } else {
                    $branches = Branch::where('created_by', \Auth::user()->ownerId())->get()->pluck('name', 'id')->toArray();
                    $clients      = User::where('created_by', '=', \Auth::user()->ownerId())->where('type', 'client')->get()->pluck('name', 'id');
                }

                $companies = User::where('type', 'company')->get()->pluck('name', 'id')->toArray();
                $universities = University::get()->pluck('name', 'id')->toArray();
                $organizations = User::where('type', 'organization')->get()->pluck('name', 'id')->toArray();
                $pipelines = Pipeline::get()->pluck('name', 'id')->toArray();
                $stages = Stage::get()->pluck('name', 'id')->toArray();
                $users = User::where('type', 'employee')->get()->pluck('name', 'id');


                $months = months();
                $currentYear = date('Y');
                $years = [];
                for ($i = 0; $i < 5; $i++) {
                    $nextYear = $currentYear + $i;
                    $years[$nextYear] = $nextYear;
                }


                $contacts = ClientDeal::where('deal_id', $deal->id)->get()->pluck('client_id', 'id')->toArray();

                return view('deals.edit', compact('deal', 'clients', 'contacts', 'months', 'years', 'companies', 'universities', 'branches', 'pipelines', 'stages', 'users', 'organizations'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified redeal in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Deal $deal
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $deal = Deal::findOrFail($id);

        if (\Auth::user()->can('edit deal') || \Auth::user()->type == 'super admin') {
            if (\Auth::user()->can('edit deal') || $deal->created_by == \Auth::user()->ownerId() || \Auth::user()->type == 'super admin') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                        'intake_month' => 'required',
                        'intake_year' => 'required',
                        //'contact' => 'required',
                        //'assigned_to' => 'required',
                        'category' => 'required',
                        'university_id' => 'required',
                       // 'organization_id' => 'required',
                       // 'branch_id' => 'required',
                        'pipeline_id' => 'required',
                        'stage_id' => 'required'
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return json_encode([
                        'status' => 'success',
                        'message' => $messages->first()
                    ]);
                }

                $usr = \Auth::user();
                $deal->name  = $request->name;
                if(isset($request->assigned_to)){
                 $deal->assigned_to = $request->input('assigned_to');
                }
                $deal->category = $request->input('category');
                $deal->university_id = $request->input('university_id');
                $deal->organization_id = $request->input('organization_id');
                if(isset($request->branch_id)){
                 $deal->branch_id = $request->input('branch_id');
                }
                $deal->intake_month = $request->input('intake_month');
                $deal->intake_year = $request->input('intake_year');
                $deal->price = 0;
                $deal->pipeline_id = $request->input('pipeline_id');
                $deal->stage_id    = $request->input('stage_id');
                $deal->description = $request->input('deal_description');
                $deal->status      = 'Active';
                $deal->created_by  = $usr->ownerId();
                $deal->save();


                //send email
                // $clients = User::whereIN('id', array_filter($request->input('contact')))->get()->pluck('email', 'id')->toArray();

                // foreach (array_keys($clients) as $client) {
                //     ClientDeal::where('deal_id', $deal->id)->update(
                //         [
                //             'client_id' => $client
                //         ]
                //     );
                // }


                $data = [
                    'type' => 'info',
                    'note' => json_encode([
                                    'title' => 'Deal Updated',
                                    'message' => 'Deal updated successfully.'
                                ]),
                    'module_id' => $deal->id,
                    'module_type' => 'lead',
                ];
                addLogActivity($data);

                return json_encode([
                    'status' => 'success',
                    'deal' => $deal,
                    'message' => __('Deal successfully updated!')
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => __('Permission Denied.')
                ]);
            }
        } else {
            return json_encode([
                'status' => 'error',
                'message' => __('Permission Denied.')
            ]);
        }
    }

    /**
     * Remove the specified redeal from storage.
     *
     * @param \App\Deal $deal
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deal $deal)
    {
        if (\Auth::user()->can('delete deal') ||  \Auth::user()->type == 'super admin') {
            if ($deal->created_by == \Auth::user()->ownerId() ||  \Auth::user()->type == 'super admin') {
                DealDiscussion::where('deal_id', '=', $deal->id)->delete();
                DealFile::where('deal_id', '=', $deal->id)->delete();
                ClientDeal::where('deal_id', '=', $deal->id)->delete();
                UserDeal::where('deal_id', '=', $deal->id)->delete();
                DealTask::where('deal_id', '=', $deal->id)->delete();
                ActivityLog::where('deal_id', '=', $deal->id)->delete();
                //                ClientPermission::where('deal_id', '=', $deal->id)->delete();
                \App\Models\LogActivity::where('module_id', $deal->id)->delete();

                $deal->delete();

                if (\Auth::user()->type == 'super admin') {
                    return redirect()->route('deals.list')->with('success', __('Deal successfully deleted!'));
                }
                return redirect()->route('deals.list')->with('success', __('Deal successfully deleted!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function order(Request $request)
    {
        $usr = \Auth::user();

        if ($usr->can('move deal')) {
            $post       = $request->all();
            $deal       = Deal::find($post['deal_id']);
            $clients    = ClientDeal::select('client_id')->where('deal_id', '=', $deal->id)->get()->pluck('client_id')->toArray();
            $deal_users = $deal->users->pluck('id')->toArray();
            $usrs       = User::whereIN('id', array_merge($deal_users, $clients))->get()->pluck('email', 'id')->toArray();

            if ($deal->stage_id != $post['stage_id']) {
                $newStage = Stage::find($post['stage_id']);
                ActivityLog::create(
                    [
                        'user_id' => $usr->id,
                        'deal_id' => $deal->id,
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
                    'deal_id' => $deal->id,
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
                $deal           = Deal::find($item);
                $deal->order    = $key;
                $deal->stage_id = $post['stage_id'];
                $deal->save();
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function labels($id)
    {
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $labels   = Label::where('pipeline_id', '=', $deal->pipeline_id)->where('created_by', \Auth::user()->creatorId())->get();
                $selected = $deal->labels();
                if ($selected) {
                    $selected = $selected->pluck('name', 'id')->toArray();
                } else {
                    $selected = [];
                }

                return view('deals.labels', compact('deal', 'labels', 'selected'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function labelStore($id, Request $request)
    {
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                if ($request->labels) {
                    $deal->labels = implode(',', $request->labels);
                } else {
                    $deal->labels = $request->labels;
                }
                $deal->save();

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
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $users = User::where('created_by', '=', \Auth::user()->ownerId())->where('type', '!=', 'client')->whereNOTIn(
                    'id',
                    function ($q) use ($deal) {
                        $q->select('user_id')->from('user_deals')->where('deal_id', '=', $deal->id);
                    }
                )->get();

                foreach ($users as $key => $user) {
                    if (!$user->can('manage deal')) {
                        $users->forget($key);
                    }
                }
                $users = $users->pluck('name', 'id');

                $users->prepend(__('Select Users'), '');

                return view('deals.users', compact('deal', 'users'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function userUpdate($id, Request $request)
    {
        $usr = \Auth::user();
        if ($usr->can('edit deal')) {
            $deal = Deal::find($id);
            $resp = '';

            if ($deal->created_by == $usr->ownerId()) {
                if (!empty($request->users)) {
                    $users = User::whereIN('id', array_filter($request->users))->get()->pluck('email', 'id')->toArray();

                    $dealArr = [
                        'deal_id' => $deal->id,
                        'name' => $deal->name,
                        'updated_by' => $usr->id,
                    ];

                    $dArr = [
                        'deal_name' => $deal->name,
                        'deal_pipeline' => $deal->pipeline->name,
                        'deal_stage' => $deal->stage->name,
                        'deal_status' => $deal->status,
                        'deal_price' => $usr->priceFormat($deal->price),
                    ];

                    foreach (array_keys($users) as $user) {
                        UserDeal::create(
                            [
                                'deal_id' => $deal->id,
                                'user_id' => $user,
                            ]
                        );
                    }

                    // Send Email
                    $resp = Utility::sendEmailTemplate('Assign Deal', $users, $dArr);
                }

                if (!empty($users) && !empty($request->users)) {
                    return redirect()->back()->with('success', __('Users successfully updated!') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
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
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                UserDeal::where('deal_id', '=', $deal->id)->where('user_id', '=', $user_id)->delete();

                return redirect()->back()->with('success', __('User successfully deleted!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function clientEdit($id)
    {
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $clients = User::where('created_by', '=', \Auth::user()->ownerId())->where('type', 'client')->whereNOTIn(
                    'id',
                    function ($q) use ($deal) {
                        $q->select('client_id')->from('client_deals')->where('deal_id', '=', $deal->id);
                    }
                )->get()->pluck('name', 'id');

                return view('deals.clients', compact('deal', 'clients'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function clientUpdate($id, Request $request)
    {
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                if (!empty($request->clients)) {
                    $clients = array_filter($request->clients);
                    foreach ($clients as $client) {
                        ClientDeal::create(
                            [
                                'deal_id' => $deal->id,
                                'client_id' => $client,
                            ]
                        );
                    }
                }

                if (!empty($clients) && !empty($request->clients)) {
                    return redirect()->back()->with('success', __('Clients successfully updated!'))->with('status', 'clients');
                } else {
                    return redirect()->back()->with('error', __('Please Select Valid Clients!'))->with('status', 'clients');
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'clients');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'clients');
        }
    }

    public function clientDestroy($id, $client_id)
    {
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                ClientDeal::where('deal_id', '=', $deal->id)->where('client_id', '=', $client_id)->delete();

                return redirect()->back()->with('success', __('Client successfully deleted!'))->with('status', 'clients');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'clients');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'clients');
        }
    }


    public function getCourses()
    {

        if (\Auth::user()->can('edit lead')) {
            $id = $_POST['deal_id'];

            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->creatorId()) {
                $courses = Course::where(['university_id' => $_POST['university_id']])->whereNOTIn('id',  explode(',', $deal->courses))->get();

                $returnHTML = view('deals.getCourses')->with('courses', $courses)->render();


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

        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $universities = University::get()->pluck('name', 'id');
                $courses = Course::whereNOTIn('id',  explode(',', $deal->courses))->get()->pluck('name', 'id');
                $universities->prepend('Select University', '');
                return view('deals.courses', compact('deal', 'courses', 'universities'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function courseUpdate($id, Request $request)
    {

        if (\Auth::user()->can('edit deal')) {
            $usr        = \Auth::user();
            $deal       = Deal::find($id);

            if ($deal->created_by == \Auth::user()->creatorId()) {
                if (!empty($request->courses)) {
                    $courses       = array_filter($request->courses);
                    $old_courses   = explode(',', $deal->courses);
                    $deal->courses = implode(',', array_merge($old_courses, $courses));
                    $deal->save();
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
        if (\Auth::user()->can('delete deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->creatorId()) {
                $courses = explode(',', $deal->courses);
                foreach ($courses as $key => $course) {
                    if ($course_id == $course) {
                        unset($courses[$key]);
                    }
                }
                $deal->courses = implode(',', $courses);
                $deal->save();

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
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $products = ProductService::where('created_by', '=', \Auth::user()->ownerId())->whereNOTIn('id', explode(',', $deal->products))->get()->pluck('name', 'id');

                return view('deals.products', compact('deal', 'products'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function productUpdate($id, Request $request)
    {
        $usr = \Auth::user();
        if ($usr->can('edit deal')) {
            $deal       = Deal::find($id);
            $clients    = ClientDeal::select('client_id')->where('deal_id', '=', $id)->get()->pluck('client_id')->toArray();
            $deal_users = $deal->users->pluck('id')->toArray();

            if ($deal->created_by == $usr->ownerId()) {
                if (!empty($request->products)) {
                    $products       = array_filter($request->products);
                    $old_products   = explode(',', $deal->products);
                    $deal->products = implode(',', array_merge($old_products, $products));
                    $deal->save();

                    $objProduct = ProductService::whereIN('id', $products)->get()->pluck('name', 'id')->toArray();
                    ActivityLog::create(
                        [
                            'user_id' => $usr->id,
                            'deal_id' => $deal->id,
                            'log_type' => 'Add Product',
                            'remark' => json_encode(['title' => implode(",", $objProduct)]),
                        ]
                    );

                    $productArr = [
                        'deal_id' => $deal->id,
                        'name' => $deal->name,
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
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $products = explode(',', $deal->products);
                foreach ($products as $key => $product) {
                    if ($product_id == $product) {
                        unset($products[$key]);
                    }
                }
                $deal->products = implode(',', $products);
                $deal->save();

                return redirect()->back()->with('success', __('Products successfully deleted!'))->with('status', 'products');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'products');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'products');
        }
    }

    public function fileUpload($id, Request $request)
    {
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $request->validate(['file' => 'required']);
                $file_name = $request->file->getClientOriginalName();
                $file_path = $request->deal_id . "_" . md5(time()) . "_" . $request->file->getClientOriginalName();
                $request->file->storeAs('deal_files', $file_path);

                $file                 = DealFile::create(
                    [
                        'deal_id' => $request->deal_id,
                        'file_name' => $file_name,
                        'file_path' => $file_path,
                    ]
                );
                $return               = [];
                $return['is_success'] = true;
                $return['download']   = route(
                    'deals.file.download',
                    [
                        $deal->id,
                        $file->id,
                    ]
                );
                $return['delete']     = route(
                    'deals.file.delete',
                    [
                        $deal->id,
                        $file->id,
                    ]
                );

                ActivityLog::create(
                    [
                        'user_id' => \Auth::user()->id,
                        'deal_id' => $deal->id,
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
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $file = DealFile::find($file_id);
                if ($file) {
                    $file_path = storage_path('deal_files/' . $file->file_path);
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
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $file = DealFile::find($file_id);
                if ($file) {
                    $path = storage_path('deal_files/' . $file->file_path);
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
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $deal->notes = $request->notes;
                $deal->save();

                $data = [
                    'type' => 'info',
                    'note' => json_encode([
                                    'title' => 'Deal Notes Created',
                                    'message' => 'Deal notes created successfully'
                                ]),
                    'module_id' => $deal->id,
                    'module_type' => 'deal',
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

    public function taskCreate($id)
    {
        if (\Auth::user()->can('create task')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $priorities = DealTask::$priorities;
                $status     = DealTask::$status;

                return view('deals.tasks', compact('deal', 'priorities', 'status'));
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

    public function taskStore($id, Request $request)
    {
        $usr = \Auth::user();
        if ($usr->can('create task')) {
            $deal       = Deal::find($id);
            $clients    = ClientDeal::select('client_id')->where('deal_id', '=', $id)->get()->pluck('client_id')->toArray();
            $deal_users = $deal->users->pluck('id')->toArray();
            $usrs       = User::whereIN('id', array_merge($deal_users, $clients))->get()->pluck('email', 'id')->toArray();

            if ($deal->created_by == $usr->ownerId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                        'date' => 'required',
                        'time' => 'required',
                        'priority' => 'required',
                        'status' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }



                $dealTask = DealTask::create(
                    [
                        'deal_id' => $deal->id,
                        'name' => $request->name,
                        'date' => $request->date,
                        'time' => date('H:i:s', strtotime($request->date . ' ' . $request->time)),
                        'priority' => $request->priority,
                        'status' => $request->status,
                    ]
                );

                ActivityLog::create(
                    [
                        'user_id' => $usr->id,
                        'deal_id' => $deal->id,
                        'log_type' => 'Create Task',
                        'remark' => json_encode(['title' => $dealTask->name]),
                    ]
                );

                $taskArr = [
                    'deal_id' => $deal->id,
                    'name' => $deal->name,
                    'updated_by' => $usr->id,
                ];

                $tArr = [
                    'deal_name' => $deal->name,
                    'deal_pipeline' => $deal->pipeline->name,
                    'deal_stage' => $deal->stage->name,
                    'deal_status' => $deal->status,
                    'deal_price' => $usr->priceFormat($deal->price),
                    'task_name' => $dealTask->name,
                    'task_priority' => DealTask::$priorities[$dealTask->priority],
                    'task_status' => DealTask::$status[$dealTask->status],
                ];

                // Send Email
                Utility::sendEmailTemplate('Create Task', $usrs, $tArr);


                $data = [
                    'type' => 'info',
                    'note' => json_encode([
                                    'title' => 'Task Created',
                                    'message' => 'Task created successfully'
                                ]),
                    'module_id' => $deal->id,
                    'module_type' => 'deal',
                ];
                addLogActivity($data);



                return redirect()->back()->with('success', __('Task successfully created!'))->with('status', 'tasks');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'tasks');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'tasks');
        }
    }

    public function taskShow($id, $task_id)
    {
        if (\Auth::user()->can('view task')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $task = DealTask::find($task_id);

                return view('deals.tasksShow', compact('task', 'deal'));
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

    public function taskEdit($id, $task_id)
    {
        if (\Auth::user()->can('edit task')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $priorities = DealTask::$priorities;
                $status     = DealTask::$status;
                $task       = DealTask::find($task_id);

                return view('deals.tasks', compact('task', 'deal', 'priorities', 'status'));
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

    public function taskUpdate($id, $task_id, Request $request)
    {
        if (\Auth::user()->can('edit task')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                        'date' => 'required',
                        'time' => 'required',
                        'priority' => 'required',
                        'status' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $task = DealTask::find($task_id);

                $task->update(
                    [
                        'name' => $request->name,
                        'date' => $request->date,
                        'time' => date('H:i:s', strtotime($request->date . ' ' . $request->time)),
                        'priority' => $request->priority,
                        'status' => $request->status,
                    ]
                );

                return redirect()->back()->with('success', __('Task successfully updated!'))->with('status', 'tasks');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'tasks');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'tasks');
        }
    }

    public function taskUpdateStatus($id, $task_id, Request $request)
    {
        if (\Auth::user()->can('edit task')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'status' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return response()->json(
                        [
                            'is_success' => false,
                            'error' => $messages->first(),
                        ],
                        401
                    );
                }

                $task = DealTask::find($task_id);
                if ($request->status) {
                    $task->status = 0;
                } else {
                    $task->status = 1;
                }
                $task->save();

                $data = [
                    'type' => 'info',
                    'note' => json_encode([
                                    'title' => 'Task Updated',
                                    'message' => 'Task updated successfully'
                                ]),
                    'module_id' => $id,
                    'module_type' => 'deal',
                ];
                addLogActivity($data);

                return response()->json(
                    [
                        'is_success' => true,
                        'success' => __('Task successfully updated!'),
                        'status' => $task->status,
                        'status_label' => __(DealTask::$status[$task->status]),
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

    public function taskDestroy($id, $task_id)
    {
        if (\Auth::user()->can('delete task')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $task = DealTask::find($task_id);
                $task->delete();

                return redirect()->back()->with('success', __('Task successfully deleted!'))->with('status', 'tasks');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'tasks');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'tasks');
        }
    }

    public function sourceEdit($id)
    {
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $sources  = Source::where('created_by', '=', \Auth::user()->ownerId())->get();
                $selected = $deal->sources();

                if ($selected) {
                    $selected = $selected->pluck('name', 'id')->toArray();
                }

                return view('deals.sources', compact('deal', 'sources', 'selected'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function sourceUpdate($id, Request $request)
    {
        $usr = \Auth::user();

        if ($usr->can('edit deal')) {
            $deal       = Deal::find($id);
            $clients    = ClientDeal::select('client_id')->where('deal_id', '=', $id)->get()->pluck('client_id')->toArray();
            $deal_users = $deal->users->pluck('id')->toArray();

            if ($deal->created_by == $usr->ownerId()) {
                if (!empty($request->sources) && count($request->sources) > 0) {
                    $deal->sources = implode(',', $request->sources);
                } else {
                    $deal->sources = "";
                }

                $deal->save();
                ActivityLog::create(
                    [
                        'user_id' => $usr->id,
                        'deal_id' => $deal->id,
                        'log_type' => 'Update Sources',
                        'remark' => json_encode(['title' => 'Update Sources']),
                    ]
                );

                $dealArr = [
                    'deal_id' => $deal->id,
                    'name' => $deal->name,
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
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $sources = explode(',', $deal->sources);
                foreach ($sources as $key => $source) {
                    if ($source_id == $source) {
                        unset($sources[$key]);
                    }
                }
                $deal->sources = implode(',', $sources);
                $deal->save();

                return redirect()->back()->with('success', __('Sources successfully deleted!'))->with('status', 'sources');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'sources');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'sources');
        }
    }

    public function permission($id, $clientId)
    {
        if (\Auth::user()->can('edit deal')) {
            $deal     = Deal::find($id);
            $client   = User::find($clientId);
            $selected = $client->clientPermission($deal->id);
            if ($selected) {
                $selected = explode(',', $selected->permissions);
            } else {
                $selected = [];
            }
            $permissions = Deal::$permissions;

            return view('deals.permissions', compact('deal', 'client', 'selected', 'permissions'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'clients');
        }
    }

    public function permissionStore($id, $clientId, Request $request)
    {
        if (\Auth::user()->can('edit deal')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $client      = User::find($clientId);
                $permissions = $client->clientPermission($deal->id);
                if ($permissions) {
                    if (!empty($request->permissions) && count($request->permissions) > 0) {
                        $permissions->permissions = implode(',', $request->permissions);
                    } else {
                        $permissions->permissions = "";
                    }
                    $permissions->save();

                    return redirect()->back()->with('success', __('Permissions successfully updated!'))->with('status', 'clients');
                } elseif (!empty($request->permissions) && count($request->permissions) > 0) {
                    ClientPermission::create(
                        [
                            'client_id' => $clientId,
                            'deal_id' => $deal->id,
                            'permissions' => implode(',', $request->permissions),
                        ]
                    );

                    return redirect()->back()->with('success', __('Permissions successfully updated!'))->with('status', 'clients');
                } else {
                    return redirect()->back()->with('error', __('Invalid Permission.'))->with('status', 'clients');
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'clients');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'clients');
        }
    }

    public function jsonUser(Request $request)
    {
        $users = [];
        if (!empty($request->deal_id)) {
            $deal  = Deal::find($request->deal_id);
            $users = $deal->users->pluck('name', 'id');
        }

        return response()->json($users, 200);
    }

    public function changePipeline(Request $request)
    {
        $user                   = \Auth::user();
        $user->default_pipeline = $request->default_pipeline_id;
        $user->save();

        return redirect()->back();
    }

    public function discussionCreate($id)
    {
        $deal = Deal::find($id);
        if ($deal->created_by == \Auth::user()->ownerId()) {
            return view('deals.discussions', compact('deal'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function discussionStore($id, Request $request)
    {

        $usr        = \Auth::user();
        $deal       = Deal::find($id);
        $deal_users = $deal->users->pluck('id')->toArray();

        if ($deal->created_by == $usr->creatorId()) {

            $discussion             = new DealDiscussion();
            $discussion->comment    = $request->comment;
            $discussion->deal_id    = $deal->id;
            $discussion->created_by = \Auth::user()->id;
            $discussion->save();

            $leadArr = [
                'lead_id' => $deal->id,
                'name' => $deal->name,
                'updated_by' => $usr->id,
            ];

            $discussions = DealDiscussion::select('deal_discussions.id', 'deal_discussions.comment', 'deal_discussions.created_at', 'users.name', 'users.avatar')->join('users', 'deal_discussions.created_by', 'users.id')->where(['deal_id' => $id])->orderBy('deal_discussions.created_by', 'DESC')->get()->toArray();
            $html = view('deals.getDiscussions', compact('discussions'))->render();

            return json_encode([
                'status' => 'success',
                'html' => $html,
                'message' => __('Message successfully added!')
            ]);
        } else {

            return json_encode([
                'status' => 'success',
                'html' => $html,
                'message' => __('Permission Denied.')
            ]);
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $deal         = Deal::where('id', '=', $id)->first();
        $deal->status = $request->deal_status;
        $deal->save();

        return redirect()->back();
    }

    // Deal Calls
    public function callCreate($id)
    {
        if (\Auth::user()->can('create deal call')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $users = UserDeal::where('deal_id', '=', $deal->id)->get();

                return view('deals.calls', compact('deal', 'users'));
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
        $usr = \Auth::user();

        if ($usr->can('create deal call')) {
            $deal = Deal::find($id);
            if ($deal->created_by == $usr->ownerId()) {
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

                DealCall::create(
                    [
                        'deal_id' => $deal->id,
                        'subject' => $request->subject,
                        'call_type' => $request->call_type,
                        'duration' => $request->duration,
                        'user_id' => $request->user_id,
                        'description' => $request->description,
                        'call_result' => $request->call_result,
                    ]
                );

                ActivityLog::create(
                    [
                        'user_id' => $usr->id,
                        'deal_id' => $deal->id,
                        'log_type' => 'Create Deal Call',
                        'remark' => json_encode(['title' => 'Create new Deal Call']),
                    ]
                );

                $dealArr = [
                    'deal_id' => $deal->id,
                    'name' => $deal->name,
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
        if (\Auth::user()->can('edit deal call')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $call  = DealCall::find($call_id);
                $users = UserDeal::where('deal_id', '=', $deal->id)->get();

                return view('deals.calls', compact('call', 'deal', 'users'));
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
        if (\Auth::user()->can('edit deal call')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
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

                $call = DealCall::find($call_id);

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
        if (\Auth::user()->can('delete deal call')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                $task = DealCall::find($call_id);
                $task->delete();

                return redirect()->back()->with('success', __('Call successfully deleted!'))->with('status', 'calls');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'calls');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'calls');
        }
    }

    // Deal email
    public function emailCreate($id)
    {
        if (\Auth::user()->can('create deal email')) {
            $deal = Deal::find($id);
            if ($deal->created_by == \Auth::user()->ownerId()) {
                return view('deals.emails', compact('deal'));
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
        if (\Auth::user()->can('create deal email')) {
            $deal = Deal::find($id);

            if ($deal->created_by == \Auth::user()->ownerId()) {
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

                DealEmail::create(
                    [
                        'deal_id' => $deal->id,
                        'to' => $request->to,
                        'subject' => $request->subject,
                        'description' => $request->description,
                    ]
                );

                $dealEmail =
                    [
                        'deal_name' => $deal->name,
                        'to' => $request->to,
                        'subject' => $request->subject,
                        'description' => $request->description,
                    ];

                //                dd($deal->name);


                try {
                    Mail::to($request->to)->send(new SendDealEmail($dealEmail, $settings));
                } catch (\Exception $e) {
                    dd($e);
                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                }


                ActivityLog::create(
                    [
                        'user_id' => \Auth::user()->id,
                        'deal_id' => $deal->id,
                        'log_type' => 'Create Deal Email',
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

    public function createApplication($id)
    {

        if (\Auth::user()->can('create application')) {

            $deal_passport = Deal::select(['users.*'])->join('client_deals', 'client_deals.deal_id', 'deals.id')->join('users', 'users.id', 'client_deals.client_id')->where(['deals.id' => $id])->first();

            if (!$deal_passport || empty($deal_passport->passport_number)) {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('No passport found.'),
                    ],
                    401
                );
            }

            $universities = University::get()->pluck('name', 'id');
            $universities->prepend('Select Institute');
            $stages = Stage::get()->pluck('name', 'id')->toArray();

            $statuses = [
                'Pending' => 'Pending',
                'Approved' => 'Approved',
                'Rejected' => 'Rejected'
            ];

            return view('deals.create-application', compact('universities', 'statuses', 'id', 'deal_passport', 'stages'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function storeApplication(Request $request)
    {


        if (\Auth::user()->can('create application')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'university' => 'required',
                    'course' => 'required',
                    'status' => 'required',
                    'intake_month' => 'required'
                ]
            );


            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }



            //check application exist or not
            $passport_number = $request->passport_number;
            $university_name = University::select('name')->where(['id' => (int)$request->university])->first()->name;
            $university_name = str_replace(' ', '-', $university_name);

            $deal = Deal::findOrFail($request->id);
            $userName = optional(
                User::find(
                    optional(
                        ClientDeal::where('deal_id', $request->id)->first()
                    )->client_id
                )
            )->name;
            $brandName=optional(User::find($deal->brand_id))->name;
            $branchname=optional(Branch::find($deal->branch_id))->name;
            $is_exist = DealApplication::where(['application_key' => $userName .'-'. $passport_number . '-' . $university_name .'-'. $request->intake_month .'-'. $request->id])->first();


            if ($passport_number && $is_exist) {
                return json_encode([
                    'status' => 'error',
                    'message' => __('Application already created by '.allUsers()[$is_exist->created_by].' for '.allUniversities()[$is_exist->university_id].' for '. $branchname.' for '.$userName.' for '.$brandName)
                ]);
            }

            $new_app = DealApplication::create([
                'application_key' =>  $userName .'-'. $passport_number . '-' . $university_name .'-'. $request->intake_month .'-'. $request->id,
                'deal_id' => $request->id,
                'university_id' => (int)$request->university,
                'course' => $request->course,
                'stage_id' => $request->status,
                'external_app_id' => $request->application_key,
                'intake' =>$request->intake_month,
                'name' => $deal->name . '-' . $request->course . '-' . $university_name . '-' . $request->application_key,
                'created_by' => \Auth::user()->id
            ]);


            //Add Stage History
            $data_for_stage_history = [
                'stage_id' => $request->status,
                'type_id' => $new_app->id,
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
                'module_id' => $new_app->id,
                'module_type' => 'application',
            ];
            addLogActivity($data);

            return json_encode([
                'status' => 'success',
                'app_id' => $new_app->id,
                'message' => __('Application successfully created!')
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => __('Permission Denied.')
            ]);
        }
    }

    public function editApplication($id)
    {

        if (\Auth::user()->can('edit application')) {
            $universities = University::get()->pluck('name', 'id');
            $statuses = [
                'Pending' => 'Pending',
                'Approved' => 'Approved',
                'Rejected' => 'Rejected'
            ];
            $application = DealApplication::where('id', $id)->first();
            $deal_passport = Deal::select(['users.*'])->join('client_deals', 'client_deals.deal_id', 'deals.id')->join('users', 'users.id', 'client_deals.client_id')->where(['deals.id' => $application->deal_id])->first();
            $stages = Stage::get()->pluck('name', 'id')->toArray();

            return view('deals.edit-application', compact('application', 'universities', 'statuses', 'deal_passport', 'stages'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function updateApplication(Request $request, $id)
    {

        if (\Auth::user()->can('edit application')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'university' => 'required',
                    'course' => 'required',
                    'status' => 'required',
                    'intake' => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $passport_number = $request->passport_number;
            $university_name = University::select('name')->where(['id' => (int)$request->university])->first()->name;
            $university_name = str_replace(' ', '-', $university_name);

            // $is_exist = DealApplication::where(['application_key' => $passport_number . '-' . $university_name])->first();
            // if ($passport_number && $is_exist) {
            //     return json_encode([
            //         'status' => 'error',
            //         'message' => __('Application already exist')
            //     ]);
            // }

            $application = DealApplication::where('id', $id)->first();

            $deal = Deal::findOrFail($application->deal_id);


            $application->application_key = $passport_number . '-' . $university_name;
            $application->university_id = $request->university;
            $application->stage_id = $request->status;
            $application->course = $request->course;

            $application->external_app_id = $request->application_key;
            $application->intake = date('Y-m-d', strtotime($request->intake));
            $application->name = $deal->name . '-' . $request->course . '-' . $university_name . '-' . $request->application_key;
            $application->update();

            return json_encode([
                'status' => 'success',
                'app_id' => $application->id,
                'message' => __('Application successfully updated!')
            ]);
        } else {

            return json_encode([
                'status' => 'error',
                'message' => __('Permission Denied.')
            ]);
        }
    }

    public function destroyApplication($id)
    {

        if (\Auth::user()->can('delete application')) {
            DealApplication::where('id', $id)->delete();
            return redirect()->back()->with('success', __('Application successfully deleted!'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }



    /////////////////////Deal User Tasks

    private function TasksFilter()
    {
        $filters = [];
        if (isset($_GET['subjects']) && !empty($_GET['subjects'])) {
            $filters['subjects'] = $_GET['subjects'];
        }

        if (isset($_GET['assigned_to']) && !empty($_GET['assigned_to'])) {
            $filters['assigned_to'] = $_GET['assigned_to'];
        }

        if (isset($_GET['brands']) && !empty($_GET['brands'])) {
            $filters['created_by'] = $_GET['brands'];
        }

        if (isset($_GET['due_date']) && !empty($_GET['due_date'])) {
            $filters['due_date'] = $_GET['due_date'];
        }
        if (isset($_GET['status']) && !empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }

        return $filters;
    }
    public function userTasks()
    {

        $start = 0;
        $num_results_on_page = 50;
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $num_of_result_per_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = ($page - 1) * $num_results_on_page;
        } else {
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
        }

        if (\Auth::user()->can('manage task') || \Auth::user()->type == 'super admin' || \Auth::user()->type == 'company') {
            $tasks = DealTask::select(['deal_tasks.*'])->join('users', 'users.id', '=', 'deal_tasks.assigned_to');
            $companies = FiltersBrands();
            $brand_ids = array_keys($companies);
            $filters = $this->TasksFilter();

            foreach ($filters as $column => $value) {
                if ($column === 'subjects') {
                    $tasks->whereIn('deal_tasks.name', $value);
                } elseif ($column === 'assigned_to') {
                    $tasks->whereIn('assigned_to', $value);
                } elseif ($column === 'created_by') {
                    $tasks->whereIn('brand_id', $value);
                } elseif ($column == 'due_date') {
                    $tasks->whereDate('due_date', 'LIKE', '%' . substr($value, 0, 10) . '%');
                }elseif ($column == 'status') {
                    $tasks->where('status',$value);
                }
            }

            if(!isset($_GET['status'])){
                $tasks->where('status', 0);
            }
            $tasks->whereIn('deal_tasks.brand_id', $brand_ids);
            $tasks->orWhere('deal_tasks.assigned_to', \Auth::user()->id);

            

            

            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true' && isset($_GET['search']) && !empty($_GET['search'])) {
                $g_search = $_GET['search'];
                $tasks->Where('deal_tasks.name', 'like', '%' . $g_search . '%');
              //  $tasks->orWhere('tasks.email', 'like', '%' . $g_search . '%');
                $tasks->orWhere('deal_tasks.due_date', 'like', '%' . $g_search . '%');
            }


            $total_records = $tasks->count();

            $tasks = $tasks->orderBy('created_at', 'DESC')->skip($start)->take($num_results_on_page)->get();
            $priorities = DealTask::$priorities;
            $user_type = User::get()->pluck('type', 'id')->toArray();
            $users = User::get()->pluck('name', 'id')->toArray();
            $brands = User::where('type', 'company')->get()->pluck('name', 'id')->toArray();
            $branches = array();
            $branches = Branch::get();

            $assign_to = array();
            if(\Auth::user()->type == 'super admin'){
                $assign_to = User::whereNotIn('type', ['client', 'company', 'super admin', 'organization', 'team'])
                ->get();
            }else{
                $assign_to = User::whereNotIn('type', ['client', 'company', 'super admin', 'organization', 'team'])
                ->where('created_by', \Auth::user()->id)->get();
            }



            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
                $html = view('deals.tasks_list_ajax',compact('tasks','assign_to','branches', 'priorities', 'user_type', 'users', 'total_records', 'brands'))->render();

                return json_encode([
                    'status' => 'success',
                    'html' => $html
                ]);
            }




            return view('deals.deal_tasks', compact('tasks','assign_to','branches', 'priorities', 'user_type', 'users', 'total_records', 'brands'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function tasksCron(){
        $date = \Carbon\Carbon::now();
        $date = $date->format('Y-m-d');

        $remainder_date_tasks = DealTask::where('remainder_date','=',$date)->get();
        $due_date_tasks = DealTask::where('due_date','=',$date)->get();

        if(sizeof($remainder_date_tasks) > 0){
            foreach($remainder_date_tasks as $task){
                $link       = '';
                // $link       = route('deals.show', [$tasks->id,]);
                $text       = "Today is task (".$task->name.") remainder date.";
                $icon       = "fa fa-tasks";
                $icon_color = 'bg-primary';

                $date = \Carbon\Carbon::now()->diffForHumans();
                $html = '<a href="' . $link . '" class="list-group-item list-group-item-action nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <span class="avatar ' . $icon_color . ' text-white rounded-circle"><i class="' . $icon . '"></i></span>
                                    </div>
                                    <div class="flex-fill ml-3">
                                        <div class="h6 text-sm mb-0">' . $text . '</div>
                                        <small class="text-muted ">' . $date . '</small>
                                    </div>
                                </div>
                            </a>';

                $notification = new Notification;
                $notification->user_id = 0;
                $notification->type = 'Task Remainder';
                $notification->data = $html;
                $notification->is_read = 0;
                $notification->save();

            }


        }

        if(sizeof($due_date_tasks) > 0){
            foreach($due_date_tasks as $task){
                $link       = '';
                // $link       = route('deals.show', [$tasks->id,]);
                $text       = "Today is task (".$task->name.") Due date.";
                $icon       = "fa fa-tasks";
                $icon_color = 'bg-primary';

                $date = \Carbon\Carbon::now()->diffForHumans();
                $html = '<a href="' . $link . '" class="list-group-item list-group-item-action nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <span class="avatar ' . $icon_color . ' text-white rounded-circle"><i class="' . $icon . '"></i></span>
                                    </div>
                                    <div class="flex-fill ml-3">
                                        <div class="h6 text-sm mb-0">' . $text . '</div>
                                        <small class="text-muted ">' . $date . '</small>
                                    </div>
                                </div>
                            </a>';

                $notification = new Notification;
                $notification->user_id = 0;
                $notification->type = 'Task Due Date';
                $notification->data = $html;
                $notification->is_read = 0;
                $notification->save();

            }


        }

    }

    public function dealDriveLink(Request $request)
    {
        $id = $request->id;
        $link = $request->link;

        Deal::where('id', $id)
            ->update(['drive_link' => $link]);

        return true;
    }

    public function getTaskDetails()
    {


        $taskId = $_GET['task_id'];

        $task = DealTask::FindOrFail($taskId);

        if (\Auth::user()->type == 'super admin') {
            $branches = Branch::get()->pluck('name', 'id')->toArray();
        } else {
            $branches = Branch::where('created_by', \Auth::user()->id)->get()->pluck('name', 'id')->toArray();
        }


        $users = User::get()->pluck('name', 'id')->toArray();
        $deals = Deal::get()->pluck('name', 'id')->toArray();
        $stages = Stage::get()->pluck('name', 'id')->toArray();

        $discussions = TaskDiscussion::select('task_discussions.id', 'task_discussions.comment', 'task_discussions.created_at', 'users.name', 'users.avatar')
            ->join('users', 'task_discussions.created_by', 'users.id')
            ->where(['task_discussions.task_id' => $taskId])
            ->orderBy('task_discussions.created_at', 'DESC')
            ->get()
            ->toArray();
            $log_activities = getLogActivity($taskId, 'task');

        $html = view('deals.task_details', compact('task', 'branches', 'users', 'deals', 'stages','log_activities', 'discussions'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function TaskStatusChange(Request $request)
    {
       if(!empty($request->input('id'))){
        DealTask::findorfail($request->input('id'))->update(['status'=>'1']);
        return json_encode([
            'status' => 'success',
            'message' => 'Update User Tasks Successfully '
        ]);
       }
    }

    public function fetchOrgField(Request $request)
    {
        $id = $request->id;
        $name = $request->name;

        $org = User::select([$name == 'type' ? 'organizations.type' : $name])->join('organizations', 'organizations.user_id', '=', 'users.id')->where('users.id', $id)->first();


        $task = DealTask::findOrFail($id);


        $data['task'] = $task;
        $data['name'] = $name;

        if ($name == 'branch_id') {
            $types = Branch::get()->pluck('name', 'id')->toArray();
            if (\Auth::user()->type == 'super admin') {
                $types = Branch::get()->pluck('name', 'id')->toArray();
            } else {
                $types = Branch::where('created_by', \Auth::user()->id)->get()->pluck('name', 'id')->toArray();
            }
            $data['types'] = $types;
        } elseif ($name == 'organization_id') {
            $data['types'] = User::where('type', 'orgranization')->get()->pluck('name', 'id')->toArray();
        } elseif ($name == 'assigned_to') {
            $data['types'] = User::where('type', 'employee')->get()->pluck('name', 'id')->toArray();
        } elseif ($name == 'status') {
            $data['types'] = [
                'On Going' => 'On Going',
                'Completed' => 'Completed'
            ];
        } elseif ($name == 'deal_id') {
            $data['types'] = Deal::get()->pluck('name', 'id')->toArray();
        }
        $html = view('deals.task_field_edit', $data)->render();

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }


    public function updateTaskData(Request $request)
    {

        $id = $request->id;
        $name = $request->name;
        $value = $request->value;

        DealTask::where('id', $id)
            ->update([
                "$name" => $value
            ]);

        $data['name'] = $name;
        $task = DealTask::findOrFail($id);
        $data['task']  = $task;

        $value = '';

        if ($name == 'branch_id') {
            $value = Branch::where('id', $id)->first()->name;
        } elseif ($name == 'organization_id') {
            $value = User::findOrFail($task->organization_id)->name;
        } elseif ($name == 'assigned_to') {
            $value = User::findOrFail($task->assigned_to)->name;
        } elseif ($name == 'status') {
            $value = $task->status;
        } elseif ($name == 'deal_id') {
            $value = Deal::findOrFail($task->deal_id)->name;
        } else {
            $value = $task->$name;
        }



        //$value
        $data['value'] = $value;

        $html = view('deals.task_field_fetch', $data)->render();


        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' => 'Organization ' . $name . ' updated successfully'
        ]);
    }

    public function taskDiscussionCreate($id)
    {

        $task = DealTask::findOrFail($id);
        return view('deals.task_discussions', compact('task'));
    }

    public function taskDiscussionStore($id, Request $request)
    {
        $usr= \Auth::user();
        $discussion = !empty($request->id) ? TaskDiscussion::find($request->id) : new TaskDiscussion();
        $discussion->fill([
            'comment'    => $request->comment,
            'task_id'    => $id,
            'created_by' => \Auth::id(),
        ])->save();

        $discussions = TaskDiscussion::select('task_discussions.id', 'task_discussions.comment', 'task_discussions.created_at', 'users.name', 'users.avatar')
            ->join('users', 'task_discussions.created_by', 'users.id')
            ->where(['task_discussions.task_id' => $id])
            ->orderBy('task_discussions.created_at', 'DESC')
            ->get()
            ->toArray();
        $html = view('deals.getDiscussions', compact('discussions','id'))->render();
        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' => __('Message successfully added!')
        ]);
    }

    public function taskDiscussionDelete($id,$taskID){
        TaskDiscussion::find($id)->delete();

        $discussions = TaskDiscussion::select('task_discussions.id', 'task_discussions.comment', 'task_discussions.created_at', 'users.name', 'users.avatar')
            ->join('users', 'task_discussions.created_by', 'users.id')
            ->where(['task_discussions.task_id' => $taskID])
            ->orderBy('task_discussions.created_at', 'DESC')
            ->get()
            ->toArray();
        $html = view('deals.getDiscussions', compact('discussions','id'))->render();
        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' => __('Message successfully added!')
        ]);
    }


    public function getDealDetails()
    {

        $deal_id = $_GET['deal_id'];
        $deal = Deal::where('id', $deal_id)->first();

        if ($deal->is_active) {

            $branches = Branch::get()->pluck('name', 'id')->toArray();
            $organizations = User::where('type', 'organization')->get()->pluck('name', 'id')->toArray();
            $universities = University::get()->pluck('name', 'id')->toArray();
            $stages = Stage::get()->pluck('name', 'id')->toArray();
            // $application = DealApplication::where('deal_id', $deal->id)->first();
            $users = User::get()->pluck('name', 'id')->toArray();
            $clientDeal = ClientDeal::where('deal_id', $deal->id)->first();
            $discussions = DealDiscussion::select('deal_discussions.id', 'deal_discussions.comment', 'deal_discussions.created_at', 'users.name', 'users.avatar')->join('users', 'deal_discussions.created_by', 'users.id')->where(['deal_id' => $deal->id])->orderBy('deal_discussions.created_by', 'DESC')->get()->toArray();
            $notes = DealNote::where('deal_id', $deal->id)->orderBy('created_at', 'DESC')->get();

            $applications = DealApplication::where('deal_id', $deal->id)->get();
            $tasks = DealTask::where(['related_to' => $deal->id, 'related_type' => 'deal'])->orderBy('status')->get();
            $log_activities = getLogActivity($deal->id, 'deal');

             //Getting lead stages history
             $stage_histories = StageHistory::where('type', 'deal')->where('type_id', $deal->id)->pluck('stage_id')->toArray();

            $html = view('deals.deal_details', compact('deal', 'branches', 'organizations', 'universities', 'stages', 'applications', 'users', 'clientDeal', 'discussions', 'notes', 'tasks', 'log_activities', 'stage_histories'))->render();

            return json_encode([
                'status' => 'success',
                'html' => $html
            ]);
        } else {

            return json_encode([
                'status' => 'error',
                'html' => __('Permission Denied.')
            ]);
        }
    }

    public function fetchDealField(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $deal = Deal::select([$name])->where('id', $id)->first();

        $data['deal'] = $deal;
        $data['name'] = $name;

        if ($name == 'organization_id') {
            $organizations = User::where('type', 'organization')->get()->pluck('name', 'id')->toArray();
            $data['organizations'] = $organizations;
        } else if ($name == 'intake_month') {
            $months = [
                'JAN' => 'January',
                'FEB' => 'February',
                'MAR' => 'March',
                'APR' => 'April',
                'MAY' => 'May',
                'JUN' => 'June',
                'JUL' => 'July',
                'AUG' => 'August',
                'SEP' => 'September',
                'OCT' => 'October',
                'NOV' => 'November',
                'DEC' => 'December'
            ];
            $data['months'] = $months;
        } else if ($name == 'intake_year') {
            $currentYear = date('Y');
            $years = [];
            for ($i = 0; $i < 5; $i++) {
                $nextYear = $currentYear + $i;
                $years[$nextYear] = $nextYear;
            }
            $data['years'] = $years;
        } else if ($name == 'stage_id') {
            $stages = Stage::get()->pluck('name', 'id')->toArray();
            $data['stages'] = $stages;
        } else if ($name == 'assigned_to') {
            $employees = User::where('type', 'employee')->get()->pluck('name', 'id')->toArray();
            $data['employees'] = $employees;
        } else if ($name == 'branch_id') {

            if (\Auth::user()->type == 'super admin') {
                $branches = Branch::get()->pluck('name', 'id')->toArray();
            } else {
                $branches = Branch::where('created_by', \Auth::user()->id)->get()->pluck('name', 'id')->toArray();
            }

            $data['branches'] = $branches;
        }

        $html = view('deals.deal_field_edit', $data)->render();

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function updateDealData(Request $request)
    {

        $id = $request->id;
        $name = $request->name;
        $value = $request->value;
        $deal_change = true;




        if ($name == 'organization_id') {
            $organizations = User::where('type', 'organization')->get()->pluck('name', 'id')->toArray();
            $data['organizations'] = $organizations;
        } else if ($name == 'intake_month') {
            $months = [
                'JAN' => 'January',
                'FEB' => 'February',
                'MAR' => 'March',
                'APR' => 'April',
                'MAY' => 'May',
                'JUN' => 'June',
                'JUL' => 'July',
                'AUG' => 'August',
                'SEP' => 'September',
                'OCT' => 'October',
                'NOV' => 'November',
                'DEC' => 'December'
            ];
            $data['months'] = $months;
        } else if ($name == 'intake_year') {
            $currentYear = date('Y');
            $years = [];
            for ($i = 0; $i < 5; $i++) {
                $nextYear = $currentYear + $i;
                $years[$nextYear] = $nextYear;
            }
            $data['years'] = $years;
        } else if ($name == 'stage_id') {
            $stages = Stage::get()->pluck('name', 'id')->toArray();
            $data['stages'] = $stages;
        } else if ($name == 'assigned_to') {
            $deal_change = false;

            DealTask::where('deal_id', $id)->update([
                "assigned_to" => $value
            ]);

            $employees = User::where('type', 'employee')->get()->pluck('name', 'id')->toArray();
            $data['employees'] = $employees;
        } else if ($name == 'branch_id') {
            if (\Auth::user()->type == 'super admin') {
                $branches = Branch::get()->pluck('name', 'id')->toArray();
            } else {
                $branches = Branch::where('created_by', \Auth::user()->id)->get()->pluck('name', 'id')->toArray();
            }
            $data['branches'] = $branches;
        }

        if ($deal_change) {
            Deal::where('id', $id)->update([
                "$name" => $value
            ]);
        }

        $deal = Deal::find($id);
        $data['deal'] = $deal;
        $data['name'] = $name;
        $html = view('deals.deal_field_edit', $data)->render();


        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' => 'Lead ' . $name . ' updated successfully'
        ]);
    }

    public function savedDataField(Request  $request)
    {
        $id = $request->id;
        $name = $request->name;


        if ($name == 'organization_id') {
            $organizations = User::where('type', 'organization')->get()->pluck('name', 'id')->toArray();
            $data['organizations'] = $organizations;
        } else if ($name == 'intake_month') {
            $months = [
                'JAN' => 'January',
                'FEB' => 'February',
                'MAR' => 'March',
                'APR' => 'April',
                'MAY' => 'May',
                'JUN' => 'June',
                'JUL' => 'July',
                'AUG' => 'August',
                'SEP' => 'September',
                'OCT' => 'October',
                'NOV' => 'November',
                'DEC' => 'December'
            ];
            $data['months'] = $months;
        } else if ($name == 'intake_year') {
            $currentYear = date('Y');
            $years = [];
            for ($i = 0; $i < 5; $i++) {
                $nextYear = $currentYear + $i;
                $years[$nextYear] = $nextYear;
            }
            $data['years'] = $years;
        } else if ($name == 'stage_id') {
            $stages = Stage::get()->pluck('name', 'id')->toArray();
            $data['stages'] = $stages;
        } else if ($name == 'assigned_to') {
            $employees = User::where('type', 'employee')->get()->pluck('name', 'id')->toArray();
            $data['employees'] = $employees;
        } else if ($name == 'branch_id') {
            if (\Auth::user()->type == 'super admin') {
                $branches = Branch::get()->pluck('name', 'id')->toArray();
            } else {
                $branches = Branch::where('created_by', \Auth::user()->id)->get()->pluck('name', 'id')->toArray();
            }
            $data['branches'] = $branches;
        }

        $deal = Deal::find($id);
        $data['deal'] = $deal;
        $data['name'] = $name;
        $html = view('deals.deal_field_edit', $data)->render();


        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' => 'Lead ' . $name . ' updated successfully'
        ]);
    }


    public function notesCreate($id)
    {
        $deal = Deal::find($id);
        return view('deals.notes', compact('deal'));
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
            $note = DealNote::where('id', $request->note_id)->first();
            $note->title = $request->input('title');
            $note->description = $request->input('description');
            $note->update();

            $notes = DealNote::where('deal_id', $id)->orderBy('created_at', 'DESC')->get();
            $html = view('deals.getNotes', compact('notes'))->render();

            return json_encode([
                'status' => 'success',
                'html' => $html,
                'message' =>  __('Notes updated successfully')
            ]);
        }
        $note = new DealNote();
        $note->title = $request->input('title');
        $note->description = $request->input('description');
        $note->created_by = \Auth::user()->id;
        $note->deal_id = $id;
        $note->save();

        $notes = DealNote::where('deal_id', $id)->orderBy('created_at', 'DESC')->get();
        $html = view('deals.getNotes', compact('notes'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' =>  __('Notes added successfully')
        ]);

        //return redirect()->back()->with('success', __('Notes added successfully'));
    }

    public function notesEdit($id)
    {
        $note = DealNote::where('id', $id)->first();
        return view('deals.notes_edit', compact('note'));
    }

    public function notesUpdate(Request $request, $id)
    {


        $validator = \Validator::make(
            $request->all(),
            [
                'title' => 'required',
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

        $note = DealNote::where('id', $request->note_id)->first();
        $note->title = $request->input('title');
        $note->description = $request->input('description');
        $note->update();

        $notes = DealNote::where('deal_id', $id)->orderBy('created_at', 'DESC')->get();
        $html = view('deals.getNotes', compact('notes'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' =>  __('Notes updated successfully')
        ]);
    }


    public function notesDelete(Request $request, $id)
    {

        $note = DealNote::where('id', $id)->first();
        $note->delete();

        $notes = DealNote::where('deal_id', $request->deal_id)->orderBy('created_at', 'DESC')->get();
        $html = view('leads.getNotes', compact('notes'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' =>  __('Notes deleted successfully')
        ]);
    }

    public function updateDealStage()
    {
        $deal_id = $_GET['deal_id'];
        $stage_id = $_GET['stage_id'];
        if(isset($_GET['application_id'])){
            $application_id = $_GET['application_id'];
            DealApplication::where('id', '!=', $application_id)->update(['status' => 0]);
        }


        Deal::where('id', $deal_id)->update(['stage_id' => $stage_id]);
        $data = [
            'type' => 'info',
            'note' => json_encode([
                            'title' => 'Deal Stage Updated',
                            'message' => 'Deal stage updated'
                        ]),
            'module_id' => $deal_id,
            'module_type' => 'deal',
        ];
        addLogActivity($data);


        //Add Stage History
        $data_for_stage_history = [
            'stage_id' => $stage_id,
            'type_id' => $deal_id,
            'type' => 'deal'
        ];
        addLeadHistory($data_for_stage_history);

        return json_encode([
            'status' => 'success',
            'message' => 'Deal stage successfully udpated!!!'
        ]);
    }

    public function getDealApplications(){

    }

    ////////////////////////////////////////////////////////////
    public function detailApplication($id)
    {
        $application = DealApplication::where('id', $id)->first();
        $stages = Stage::get()->pluck('name', 'id')->toArray();
        $universities = University::get()->pluck('name', 'id')->toArray();

        //Getting lead stages history
        $stage_histories = StageHistory::where('type', 'application')->where('type_id', $id)->pluck('stage_id')->toArray();


        $html = view('deals.detail_application', compact('application', 'stages', 'universities', 'stage_histories'))->render();
        return json_encode([
            'status' => 'success',
            'app_id' => $application->id,
            'html' => $html
        ]);
    }

    public function updateBulkTaskStatus(Request $request){



        $ids = explode(',', $request->task_ids);
        $status = $request->status;

        DealTask::whereIn('id', $ids)->update(['status' => $status]);


        foreach($ids as $id){

            $task = DealTask::findOrFail($id);

            $data = [
                'type' => 'info',
                'note' => json_encode([
                                'title' => 'Task Updated',
                                'message' => 'Task status updated'
                            ]),
                'module_id' => $task->deal_id,
                'module_type' => 'deal',
            ];
            addLogActivity($data);
        }
        return redirect()->route('deals.get.user.tasks')->with('success', 'Tasks status updated successfully');
    }

    public function updateBulkTask(Request $request){

        $ids = explode(',',$request->tasks_ids);

        if(isset($request->task_name)){

            DealTask::whereIn('id',$ids)->update(['name' => $request->task_name]);
            return redirect()->route('deals.get.user.tasks')->with('success', 'Tasks updated successfully');

        }elseif(isset($request->branch_id)){

            DealTask::whereIn('id',$ids)->update(['branch_id' => $request->branch_id]);
            return redirect()->route('deals.get.user.tasks')->with('success', 'Tasks updated successfully');

        }elseif(isset($request->status)){

            DealTask::whereIn('id',$ids)->update(['status' => $request->status]);
            return redirect()->route('deals.get.user.tasks')->with('success', 'Tasks updated successfully');

        }elseif(isset($request->due_date)){

            DealTask::whereIn('id',$ids)->update(['due_date' => $request->due_date]);
            return redirect()->route('deals.get.user.tasks')->with('success', 'Tasks updated successfully');

        }elseif(isset($request->start_date)){

            DealTask::whereIn('id',$ids)->update(['start_date' => $request->start_date]);
            return redirect()->route('deals.get.user.tasks')->with('success', 'Tasks updated successfully');

        }elseif(isset($request->remainder_date) && isset($request->remainder_time)){

            DealTask::whereIn('id',$ids)->update(['remainder_date' => $request->remainder_date]);
            return redirect()->route('deals.get.user.tasks')->with('success', 'Tasks updated successfully');

        }elseif(isset($request->description)){

            DealTask::whereIn('id',$ids)->update(['description' => $request->description]);
            return redirect()->route('deals.get.user.tasks')->with('success', 'Tasks updated successfully');

        }elseif(isset($request->visibility)){

            DealTask::whereIn('id',$ids)->update(['visibility' => $request->visibility]);
            return redirect()->route('deals.get.user.tasks')->with('success', 'Tasks updated successfully');

        }elseif(isset($request->assigned_to)){

            DealTask::whereIn('id',$ids)->update(['assigned_to' => $request->assigned_to]);
            return redirect()->route('deals.get.user.tasks')->with('success', 'Tasks updated successfully');

        }
    }

    public function deleteBulkTasks(Request $request){
        DealTask::whereIn('id', explode(',', $request->ids))->delete();
        return redirect()->route('deals.get.user.tasks')->with('success', 'Tasks deleted successfully');
    }

    public function deleteBulkDeals(Request $request){
        if($request->ids != null){
            Deal::whereIn('id', explode(',', $request->ids))->delete();
            return redirect()->route('deals.list')->with('success', 'Deals deleted successfully');
        }else{
            return redirect()->route('deals.list')->with('error', 'Atleast select 1 deal.');
        }
    }

    public function getCompanyEmployees(){
        $id = $_GET['id'];

        $employees =  User::where('brand_id', $id)->pluck('name', 'id')->toArray();
        $branches = Branch::whereRaw('FIND_IN_SET(?, brands)', [$id])->pluck('name', 'id')->toArray();

        $html = ' <select class="form form-control assigned_to select2" id="choices-multiple4" name="assigned_to" required> <option value="">Assign to</option> ';
        foreach ($employees as $key => $user) {
            $html .= '<option value="' . $key . '">' . $user . '</option> ';
        }
        $html .= '</select>';

        $html1 = ' <select class="form form-control branch_id select2" id="choices-multiple4" name="branch_id" required> <option value="">Select Branch</option> ';
        foreach ($branches as $key => $branch) {
            $html1 .= '<option value="' . $key . '">' . $branch . '</option> ';
        }
        $html1 .= '</select>';

        return json_encode([
            'status' => 'success',
            'employees' => $html,
            'branches' => $html1,
        ]);

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function updateBulkDeal(Request $request){

        $ids = explode(',',$request->deal_ids);

        if(isset($request->name)){

            Deal::whereIn('id',$ids)->update(['name' => $request->name]);
            return redirect()->route('deals.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->intake_month)){

            Deal::whereIn('id',$ids)->update(['intake_month' => $request->intake_month]);
            return redirect()->route('deals.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->intake_year)){

            Deal::whereIn('id',$ids)->update(['intake_year' => $request->intake_year]);
            return redirect()->route('deals.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->contact)){

            Deal::whereIn('id',$ids)->update(['contact' => $request->contact]);
            return redirect()->route('deals.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->assigned_to)){

            Deal::whereIn('id',$ids)->update(['assigned_to' => $request->assigned_to]);
            return redirect()->route('deals.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->category)){

            Deal::whereIn('id',$ids)->update(['category' => $request->category]);
            return redirect()->route('deals.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->university_id)){

            Deal::whereIn('id',$ids)->update(['university_id' => $request->university_id]);
            return redirect()->route('deals.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->organization_id)){

            Deal::whereIn('id',$ids)->update(['organization_id' => $request->organization_id]);
            return redirect()->route('deals.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->branch_id)){

            Deal::whereIn('id',$ids)->update(['branch_id' => $request->branch_id]);
            return redirect()->route('deals.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->pipeline_id)){

            Deal::whereIn('id',$ids)->update(['pipeline_id' => $request->pipeline_id]);
            return redirect()->route('deals.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->stage_id)){

            Deal::whereIn('id',$ids)->update(['stage_id' => $request->stage_id]);
            return redirect()->route('deals.list')->with('success', 'Leads updated successfully');

        }elseif(isset($request->deal_description)){

            Deal::whereIn('id',$ids)->update(['description' => $request->deal_description]);
            return redirect()->route('deals.list')->with('success', 'Leads updated successfully');

        }
    }

    public function updateBulkApplication(Request $request){

        $ids = explode(',',$request->app_ids);
        // dd($ids);
        if(isset($request->university)){

            DealApplication::whereIn('id',$ids)->update(['university_id' => $request->university]);
            return redirect()->route('applications.index')->with('success', 'Applications updated successfully');

        }elseif(isset($request->course)){

            DealApplication::whereIn('id',$ids)->update(['course' => $request->course]);
            return redirect()->route('applications.index')->with('success', 'Applications updated successfully');

        }elseif(isset($request->application_key)){

            DealApplication::whereIn('id',$ids)->update(['application_key' => $request->application_key]);
            return redirect()->route('applications.index')->with('success', 'Applications updated successfully');

        }elseif(isset($request->contact)){

            DealApplication::whereIn('id',$ids)->update(['intake' => $request->intake]);
            return redirect()->route('applications.index')->with('success', 'Applications updated successfully');

        }elseif(isset($request->status)){
            $status = (int)$request->status;
            DealApplication::whereIn('id',$ids)->update(['stage_id' => $status]);
            return redirect()->route('applications.index')->with('success', 'Applications updated successfully');

        }
    }


}
