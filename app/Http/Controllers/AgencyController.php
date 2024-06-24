<?php

namespace App\Http\Controllers;

use App\Models\University;
use Session;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Agency;
use App\Models\User;
use App\Models\Stage;
use App\Models\Branch;
use App\Models\Region;
use App\Models\DealTask;
use App\Models\ActivityLog;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\OrganizationNote;
use App\Models\OrganizationType;
use App\Models\CompanyPermission;
use App\Models\DealApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\OrganizationDiscussion;
use Illuminate\Support\Facades\Validator;

class AgencyController extends Controller
{
    
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

    private function organizationsFilter()
    {
        $filters = [];
        if (isset($_GET['agencyname']) && !empty($_GET['agencyname'])) {
            $filters['name'] = $_GET['agencyname'];
        }

        if (isset($_GET['agencyemail']) && !empty($_GET['agencyemail'])) {
            $filters['email'] = $_GET['agencyemail'];
        }

        if (isset($_GET['agencyphone']) && !empty($_GET['agencyphone'])) {
            $filters['phone'] = $_GET['agencyphone'];
        }

        if (isset($_GET['country']) && !empty($_GET['country'])) {
            $filters['country'] = $_GET['country'];
        }

        return $filters;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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

        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('manage organization')) {

            $org_query = Agency::select(
                'agencies.*',
                'users.name as username',
                'users.email as useremail',
                'users.id as UserId',
            )
            ->leftJoin('users', 'users.id', '=', 'agencies.user_id');
           


            $filters = $this->organizationsFilter();
            foreach ($filters as $column => $value) {
                if ($column === 'name') {
                    $org_query->where('agencies.name', 'LIKE', '%' . $value . '%');
                } elseif ($column === 'phone') {
                    $org_query->where('agencies.phone', 'LIKE', '%' . $value . '%');
                } elseif ($column === 'email') {
                    $org_query->where('agencies.email', 'LIKE', '%' . $value . '%');
                } elseif ($column === 'country') {
                    $org_query->whereIn('agencies.billing_country', $value);
                }
            }

            //if list global search
            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true' && isset($_GET['search']) && !empty($_GET['search'])) {
                $g_search = $_GET['search'];
                $org_query->Where('users.name', 'like', '%' . $g_search . '%');
                $org_query->orWhere('agencies.phone', 'like', '%' . $g_search . '%');
                $org_query->orWhere('agencies.email', 'like', '%' . $g_search . '%');
                $org_query->orWhere('agencies.billing_country', 'like', '%' . $g_search . '%');
            }

            $total_records  = $org_query->count();
            $organizations = $org_query->orderBy('agencies.created_at', 'desc')->skip($start)->take($num_results_on_page)->get();


            $org_types = OrganizationType::get()->pluck('name', 'id');
            $countries = $this->countries_list();
            // $countries = [];
            $user_type = User::get()->pluck('type', 'id')->toArray();
            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
                $html = view('agency.organization_list', compact('organizations', 'org_types', 'countries', 'user_type'))->render();
                $pagination_html = view('layouts.pagination', [
                    'total_pages' => $total_records,
                    'num_results_on_page' =>  $num_results_on_page // You need to define $num_results_on_page
                ])->render();

                return json_encode([
                    'status' => 'success',
                    'html' => $html,
                    'pagination_html' => $pagination_html
                ]);
            } else {
                return view('agency.index', compact('organizations', 'org_types', 'countries', 'user_type', 'total_records'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        //

        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('create organization')) {

            $org_types = OrganizationType::get()->pluck('name', 'id');
            $countries = $this->countries_list();
            $user_type = User::get()->pluck('type', 'id')->toArray();

            $data = [
                'org_types' => $org_types,
                'countries' => $countries,
                'user_type' => $user_type
            ];
            return view('agency.organization_create',  $data);
        } else {

            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }


    public function store(Request $request)
    {

        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('create organization')) {

            //
            $validator = \Validator::make(
                $request->all(),
                [
                    'organization_name' => 'required',
                    'organization_email' => 'required|unique:users,email',
                    'organization_phone' => 'required',
                ]
            );



            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return json_encode([
                    'status' => 'error',
                    'message' => $messages->first()
                ]);
            }



            //Creating users
            $user = new User();
            $user->name = $request->organization_name;
            $user->type = 'agency';
            $user->email =  $request->organization_email;
            $user->password = Hash::make('123456789');
            $user->is_active = 1;
            $user->lang = 'en';
            $user->mode = 'light';
            $user->created_by = \Auth::user()->id;
            $user->save();

            $org = new Agency;
            $org->type = $request->organization_type;
            $org->phone =  $request->organization_phone;
            $org->website = $request->organization_website;
            $org->linkedin = $request->organization_linkedin;
            $org->facebook = $request->organization_facebook;
            $org->twitter = $request->organization_twitter;
            $org->billing_street = $request->organization_billing_street;
            $org->contactname = $request->contactname;
            $org->contactemail = $request->contactemail;
            $org->contactphone = $request->contactphone;
            $org->contactjobroll = $request->contactjobroll;
            $org->billing_country = $request->organization_billing_country;
            $org->description = $request->organization_description;
            $org->user_id = $user->id;
            $org->save();


            //Log
            $data = [
                'type' => 'info',
                'note' => json_encode([
                    'title' => 'Agency Created',
                    'message' => 'Agency created successfully'
                ]),
                'module_id' => $user->id,
                'module_type' => 'Agency',
                'notification_type' => 'Agency Created'
            ];
            addLogActivity($data);
            return json_encode([
                'status' => 'success',
                'message' => 'Agency created successfully!.',
                'org' => $org->id,
            ]);

        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Permission Denied.'
            ]);
        }
    }

    public function GetAgencyDetail(Request $request)
    {
        //
        $org_query = Agency::select(
            'agencies.*',
            'users.name as username',
            'users.email as useremail',
            'users.id as UserId',
        )
        ->leftJoin('users', 'users.id', '=', 'agencies.user_id')
        ->where('agencies.id', $request->id)->first();

        $html = view('agency.AgencyDetail', compact('org_query'))->render();
        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);

    }

   
    public function edit($id)
    {
        //
        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('edit organization')) {

            $org_query = Agency::select(
                'agencies.*',
                'users.name as username',
                'users.email as useremail',
                'users.id as UserId',
            )
            ->leftJoin('users', 'users.id', '=', 'agencies.user_id')
            ->where('agencies.id', $id)->first();
            $countries = $this->countries_list();
            return view('agency.organization_edit', ['org_query' => $org_query , 'countries' => $countries]);
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    
    public function update(Request $request, $id)
    {

        //Creating users
        $org_query = Agency::select(
            'agencies.*',
            'users.name as username',
            'users.email as useremail',
            'users.id as UserId',
        )
        ->leftJoin('users', 'users.id', '=', 'agencies.user_id')
        ->where('agencies.id', $id)->first();
        $user = User::where('id', $org_query->user_id)->first();
        $user->name = $request->organization_name;
        $user->type = 'organization';
        $user->email =  $request->organization_email;
        $user->password = Hash::make('123456789');
        $user->is_active = 1;
        $user->lang = 'en';
        $user->mode = 'light';
        $user->created_by = \Auth::user()->id;
        $user->passport_number = '';
        $user->save();



        $org = Agency::find($org_query->id);
        $org->type = $request->organization_type;
        $org->phone =  $request->organization_phone;
        $org->website = $request->organization_website;
        $org->linkedin = $request->organization_linkedin;
        $org->facebook = $request->organization_facebook;
        $org->twitter = $request->organization_twitter;
        $org->billing_street = $request->organization_billing_street;
        $org->contactname = $request->contactname;
        $org->contactemail = $request->contactemail;
        $org->contactphone = $request->contactphone;
        $org->contactjobroll = $request->contactjobroll;
        $org->billing_country = $request->organization_billing_country;
        $org->description = $request->organization_description;
        $org->user_id = $user->id;
        $org->save();
        //Log
        $data = [
            'type' => 'info',
            'note' => json_encode([
                'title' => 'Organization Updated',
                'message' => 'Organization updated successfully'
            ]),
            'module_id' => $user->id,
            'module_type' => 'organization',
            'notification_type' => 'Organization Updated'
        ];
        addLogActivity($data);

        return json_encode([
            'status' => 'success',
            'org' => $id,
            'message' =>  __('Organization successfully updated!')
        ]);

    }

    public function destroy($id)
    {

        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('delete organization')) {
            
            $org_data = Agency::select(
                'agencies.*',
                'users.name as username',
                'users.email as useremail',
                'users.id as UserId',
            )
            ->leftJoin('users', 'users.id', '=', 'agencies.user_id')
            ->where('agencies.id', $id)->first();

            if (!empty($org_data)){
                $org_data->delete();
                return redirect()->route('agency.index')->with('success', __('Organization successfully deleted!'));
            }else{
                return response()->json(['error' => __('Data Not Found')], 401);
            }
            
    
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }
}
