<?php

namespace App\Http\Controllers;

use http\Client;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Plan;
use App\Models\User;
use App\Models\Stage;
use App\Models\Invoice;
use App\Models\Utility;
use App\Models\Contract;
use App\Models\ClientDeal;
use App\Models\Estimation;
use App\Models\University;
use App\Models\CustomField;
use Illuminate\Http\Request;
use App\Models\ClientPermission;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            [
                'auth',
                'XSS',
            ]
        );
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

    public function index()
    {
        if(\Auth::user()->can('manage client') || \Auth::user()->type == 'super admin')
        {
            $user    = \Auth::user();

            $start = 0;
            $num_results_on_page = env("RESULTS_ON_PAGE");
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                $num_of_result_per_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
                $start = ($page - 1) * $num_results_on_page;
            } else {
                $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            }


           // $clients = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'client')->skip($start)->take($num_results_on_page)->get();
            $client_query = User::select('users.*')->where('users.type', 'client');
            if (!empty($_GET['name'])) {
                $client_query->where('users.name', 'like', '%' . $_GET['name'] . '%');
            }

            if (!empty($_GET['email'])) {
                $client_query->where('users.email', 'like', '%' . $_GET['email'] . '%');
            }

            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $g_search = $_GET['search'];
                $client_query->where(function ($query) use ($g_search) {
                    $query->where('users.name', 'like', '%' . $g_search . '%')
                          ->orWhere('users.passport_number', 'like', '%' . $g_search . '%');
                });
            }


        $total_records = $client_query->count();

         // Paginate the results
        $clients = $client_query
            ->orderBy('users.created_at', 'DESC')
            ->skip($start)
            ->take($num_results_on_page)
            ->get();


            // $clients = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'client')->skip($start)->take($num_results_on_page)->get();

            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
                $html = view('clients.clients_list_ajax', compact('clients', 'total_records'))->render();
                $pagination_html = view('layouts.pagination', [
                    'total_pages' => $total_records,
                    'num_results_on_page' =>  $num_results_on_page
                ])->render();
                return json_encode([
                    'status' => 'success',
                    'html' => $html,
                    'pagination_html' => $pagination_html
                ]);
            }

            return view('clients.index', compact('clients', 'total_records'));
        }
        else
        {

            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create(Request $request)
    {

        if(\Auth::user()->can('create client'))
        {
            if($request->ajax)
            {
                return view('clients.createAjax');
            }
            else
            {
                $customFields = CustomField::where('module', '=', 'client')->get();

                return view('clients.create', compact('customFields'));
            }
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create client'))
                {
                    $user      = \Auth::user();
                    $validator = \Validator::make(
                        $request->all(), [
                                           'name' => 'required',
                                           'email' => 'required|email|unique:users',
                                           'password' => 'required',
                                       ]
                    );

                    if($validator->fails())
                    {
                        $messages = $validator->getMessageBag();
                        if($request->ajax)
                        {
                            return response()->json(['error' => $messages->first()], 401);
                        }
                        else
                        {
                            return redirect()->back()->with('error', $messages->first());
                        }
                    }
                    $objCustomer    = \Auth::user();
                    $creator        = User::find($objCustomer->creatorId());
                    $total_client = User::where('type','client')->count();
                    // dd($total_client);
                    $plan           = Plan::find($creator->plan);
                    // if($total_client < $plan->max_clients || $plan->max_clients == -1)
                    // {
                        $role = Role::findByName('client');
                        $client = User::create(
                            [
                                'name' => $request->name,
                                'email' => $request->email,
                                'job_title' => $request->job_title,
                                'password' => Hash::make($request->password),
                                'type' => 'client',
                                'lang' => Utility::getValByName('default_language'),
                                'created_by' => $user->creatorId(),
                            ]
                        );

                        $client->passport_number = $request->passport_number;
                        $client->save();


                        //Send Email

                        $role_r = Role::findByName('client');
                        $client->assignRole($role_r);

                        $client->password = $request->password;
                        $clientArr = [
                            'client_name' => $client->name,
                            'client_email' => $client->email,
                            'client_password' =>  $client->password,
                        ];
                        $resp = Utility::sendEmailTemplate('new_client', [$client->email], $clientArr);


                        return redirect()->route('clients.index')->with('success', __('Client successfully added.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));

                    // }


                    // else
                    // {
                    //     return redirect()->back()->with('error', __('Your user limit is over, Please upgrade plan.'));
                    // }

                }
        else
        {
            if($request->ajax)
                    {
                        return response()->json(['error' => __('Permission Denied.')], 401);
                    }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
    }

    public function show(User $client)
    {
        $usr = Auth::user();
        if(!empty($client) && $usr->id == $client->creatorId() && $client->id != $usr->id && $client->type == 'client')
        {
            // For Estimations
            $estimations = $client->clientEstimations()->orderByDesc('id')->get();
            $curr_month  = $client->clientEstimations()->whereMonth('issue_date', '=', date('m'))->get();
            $curr_week   = $client->clientEstimations()->whereBetween(
                'issue_date', [
                                \Carbon\Carbon::now()->startOfWeek(),
                                \Carbon\Carbon::now()->endOfWeek(),
                            ]
            )->get();
            $last_30days = $client->clientEstimations()->whereDate('issue_date', '>', \Carbon\Carbon::now()->subDays(30))->get();
            // Estimation Summary
            $cnt_estimation                = [];
            $cnt_estimation['total']       = Estimation::getEstimationSummary($estimations);
            $cnt_estimation['this_month']  = Estimation::getEstimationSummary($curr_month);
            $cnt_estimation['this_week']   = Estimation::getEstimationSummary($curr_week);
            $cnt_estimation['last_30days'] = Estimation::getEstimationSummary($last_30days);

            $cnt_estimation['cnt_total']       = $estimations->count();
            $cnt_estimation['cnt_this_month']  = $curr_month->count();
            $cnt_estimation['cnt_this_week']   = $curr_week->count();
            $cnt_estimation['cnt_last_30days'] = $last_30days->count();

            // For Contracts
            $contracts   = $client->clientContracts()->orderByDesc('id')->get();
            $curr_month  = $client->clientContracts()->whereMonth('start_date', '=', date('m'))->get();
            $curr_week   = $client->clientContracts()->whereBetween(
                'start_date', [
                                \Carbon\Carbon::now()->startOfWeek(),
                                \Carbon\Carbon::now()->endOfWeek(),
                            ]
            )->get();
            $last_30days = $client->clientContracts()->whereDate('start_date', '>', \Carbon\Carbon::now()->subDays(30))->get();

            // Contracts Summary
            $cnt_contract                = [];
            $cnt_contract['total']       = Contract::getContractSummary($contracts);
            $cnt_contract['this_month']  = Contract::getContractSummary($curr_month);
            $cnt_contract['this_week']   = Contract::getContractSummary($curr_week);
            $cnt_contract['last_30days'] = Contract::getContractSummary($last_30days);

            $cnt_contract['cnt_total']       = $contracts->count();
            $cnt_contract['cnt_this_month']  = $curr_month->count();
            $cnt_contract['cnt_this_week']   = $curr_week->count();
            $cnt_contract['cnt_last_30days'] = $last_30days->count();

            return view('clients.show', compact('client', 'estimations', 'cnt_estimation', 'contracts', 'cnt_contract'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit(User $client)
    {
        if(\Auth::user()->can('edit client'))
        {
            $user = \Auth::user();
            // if($client->created_by == $user->creatorId() || \Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team')
            // {
                $client->customField = CustomField::getData($client, 'client');
                $customFields        = CustomField::where('module', '=', 'client')->get();

                return view('clients.edit', compact('client', 'customFields'));
            // }
            // else
            // {
            //     return response()->json(['error' => __('Invalid Client.')], 401);
            // }
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function update(User $client, Request $request)
    {

        if(\Auth::user()->can('edit client'))
        {
            // if($client->created_by == $user->creatorId())
            // {
                $validation = [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,' . $client->id,
                ];

                $post         = [];
                $post['name'] = $request->name;
                $client->name = $request->name;
                $client->password = Hash::make($request->password);

                $validator = \Validator::make($request->all(), $validation);
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
               $client->email = $request->email;
               $client->passport_number = $request->passport_number;
                $client->update();

                CustomField::saveData($client, $request->customField);

                return redirect()->back()->with('success', __('Client Updated Successfully!'));
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Invalid Client.'));
            // }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy(User $client)
    {
        $user = \Auth::user();
            if($client->created_by == $user->creatorId())
            {
                $estimation = Estimation::where('client_id', '=', $client->id)->first();
                if(empty($estimation))
                {
                  /*  ClientDeal::where('client_id', '=', $client->id)->delete();
                    ClientPermission::where('client_id', '=', $client->id)->delete();*/
                    $client->delete();
                    return redirect()->back()->with('success', __('Client Deleted Successfully!'));
                }
                else
                {
                    return redirect()->back()->with('error', __('This client has assigned some estimation.'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('Invalid Client.'));
            }
        }

    public function clientPassword($id)
    {
        $eId        = \Crypt::decrypt($id);
        $user = User::find($eId);
        $client = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'client')->first();


        return view('clients.reset', compact('user', 'client'));
    }

    public function clientPasswordReset(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'password' => 'required|confirmed|same:password_confirmation',
                           ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        $user                 = User::where('id', $id)->first();
        $user->forceFill([
                             'password' => Hash::make($request->password),
                         ])->save();

        return redirect()->route('clients.index')->with(
            'success', 'Client Password successfully updated.'
        );


    }

    public function clientDetail($id){
        $client = User::findOrFail($id);
        $deal = Deal::join('client_deals', 'client_deals.deal_id', 'deals.id')->where('client_deals.client_id', $id)->first();

        $lead = Lead::select('leads.*')
                        ->join('deals as d', 'leads.is_converted', '=', 'd.id')
                        ->join('client_deals as cd', 'cd.deal_id', '=', 'd.id')
                        ->where('cd.client_id', $id)
                        ->first();

        $organizations = User::get()->pluck('name', 'id')->toArray();

        $deals = Deal::join('client_deals', 'client_deals.deal_id', 'deals.id')->where('client_deals.client_id', $id)->get();
        $applications = Deal::select(['deal_applications.*'])->join('deal_applications', 'deal_applications.deal_id', 'deals.id')->join('client_deals','client_deals.deal_id', 'deals.id')->where('client_deals.client_id', $id)->get();
        $stages = Stage::get()->pluck('name', 'id')->toArray();
        $universities = University::get()->pluck('name', 'id')->toArray();

        $html = view('clients.clientDetail', compact('client', 'deal', 'lead', 'organizations', 'deals', 'applications', 'stages', 'universities'))->render();
        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function deleteBulkContacts(Request $request){

        if($request->ids != null){
            User::whereIn('id', explode(',', $request->ids))->where('type', '=', 'client')->delete();
            return redirect()->route('clients.index')->with('success', 'Clients deleted successfully');
        }else{
            return redirect()->route('clients.index')->with('error', 'Atleast select 1 client.');
        }

    }

    public function updateBulkContact(Request $request){

        $ids = explode(',',$request->contacts_ids);
        // dd($ids);
        if(isset($request->name)){

            User::whereIn('id',$ids)->where('type', '=', 'client')->update(['name' => $request->name]);
            return redirect()->route('clients.index')->with('success', 'Contacts updated successfully');

        }elseif(isset($request->email)){

            User::whereIn('id',$ids)->where('type', '=', 'client')->update(['email' => $request->email]);
            return redirect()->route('clients.index')->with('success', 'Contacts updated successfully');

        }elseif(isset($request->passport_number)){

            User::whereIn('id',$ids)->where('type', '=', 'client')->update(['passport_number' => $request->passport_number]);
            return redirect()->route('clients.index')->with('success', 'Contacts updated successfully');

        }elseif(isset($request->password)){
            $password = Hash::make($request->password);
            User::whereIn('id',$ids)->where('type', '=', 'client')->update(['password' => $password]);
            return redirect()->route('clients.index')->with('success', 'Contacts updated successfully');

        }
    }
}
