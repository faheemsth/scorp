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
use App\Models\City;
use App\Models\AgencyNote;
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
                    return [
                        'name' => $country['name']['common'],
                        'code' => isset($country['cca2']) ? $country['cca2'] : null,
                    ];
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

        if (isset($_GET['city']) && !empty($_GET['city'])) {
            $filters['city'] = $_GET['city'];
        }
        if (isset($_GET['brand_id']) && !empty($_GET['brand_id'])) {
            $filters['brand_id'] = $_GET['brand_id'];
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
                    $org_query->where('users.name', 'LIKE', '%' . $value . '%');
                } elseif ($column === 'phone') {
                    $org_query->where('agencies.phone', 'LIKE', '%' . $value . '%');
                } elseif ($column === 'email') {
                    $org_query->where('users.email', 'LIKE', '%' . $value . '%');
                } elseif ($column === 'country') {
                    $org_query->where('agencies.billing_country', 'like', '%' . $value . '%');
                } elseif ($column === 'city') {
                    $org_query->where('agencies.city', 'like', '%' . $value . '%');
                }elseif ($column === 'brand_id') {
                    $org_query->where('agencies.brand_id',$value);
                }
            }
            
            //if list global search
            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true' && isset($_GET['search']) && !empty($_GET['search'])) {
                $g_search = $_GET['search'];
                $org_query->where('users.name', 'like', '%' . $g_search . '%');
                $org_query->orWhere('agencies.phone', 'like', '%' . $g_search . '%');
                $org_query->orWhere('users.email', 'like', '%' . $g_search . '%');
                $org_query->orWhere('agencies.billing_country', 'like', '%' . $g_search . '%');
                $org_query->where('agencies.city', 'like', '%' . $g_search . '%');
                $org_query->where('agencies.brand_id',$g_search);
            }

            $total_records  = $org_query->count();
            $organizations = $org_query->orderBy('agencies.created_at', 'desc')->skip($start)->take($num_results_on_page)->get();

            if(!empty($_GET) && !empty($_GET['country'])){
                $country_parts = explode("-", isset($_GET['country']) ? $_GET['country'] : '');
                $citiese = City::where('country_code', $country_parts[1])->get();
            }else{
                $citiese=[];
            }
            $org_types = OrganizationType::get()->pluck('name', 'id');
            $countries = $this->countries_list();
            // $countries = [];
            $user_type = User::get()->pluck('type', 'id')->toArray();
            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
                $html = view('agency.agency_ajax_list', compact('organizations', 'org_types', 'countries', 'user_type'))->render();
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
                $filter = BrandsRegionsBranches();
                $companies = $filter['brands'];
                return view('agency.index', compact('companies','citiese','organizations', 'org_types', 'countries', 'user_type', 'total_records'));
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
            $filter = BrandsRegionsBranches();
            $companies = $filter['brands'];
            $data = [
                'org_types' => $org_types,
                'countries' => $countries,
                'user_type' => $user_type,
                'companies' => $companies,
            ];

            return view('agency.organization_create',  $data);
        } else {

            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function getCitiesOnCode(Request $request)
    {
        $countryCode = $request->input('code');
        
        $cities = City::where('country_code', $countryCode)->pluck('name', 'id')->toArray();
        
        $html = '<select class="form-control city select2" id="city" name="city">';
        $html .= '<option value="">Select Cities</option>';
        
        foreach ($cities as $id => $name) {
            $html .= '<option value="' . $name . '">' . $name . '</option>';
        }
        
        $html .= '</select>';
        
        return response()->json([
            'html' => $html,
            'status' => 'success'
        ]);
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
            $org->brand_id = $request->brand_id;
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
            $org->city = $request->city;
            $org->c_address = $request->c_address;
            $org->save();


            //Log
            $data = [
                'type' => 'info',
                'note' => json_encode([
                    'title' => 'Agency Created',
                    'message' => 'Agency created successfully'
                ]),
                'module_id' => $user->id,
                'module_type' => 'agency',
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
        // sheraz
        $org_query = Agency::select(
            'agencies.*',
            'users.name as username',
            'users.email as useremail',
            'users.id as UserId',
        )
        ->leftJoin('users', 'users.id', '=', 'agencies.user_id')
        ->where('agencies.id', $request->id)->first();
        $log_activities = getLogActivity($org_query->id, 'agency');
        
        $filter = BrandsRegionsBranches();
        $companies = $filter['brands'];
        $tasks = \App\Models\DealTask::where(['related_to' => $org_query->id, 'related_type' => 'agency'])->get();
        $html = view('agency.AgencyDetail', compact('companies','org_query','log_activities','tasks'))->render();
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
            $country_parts = explode("-", $org_query->billing_country);
            $country_code = end($country_parts);
            $cities = City::where('country_code', $country_code)->pluck('name', 'id')->toArray();

            $filter = BrandsRegionsBranches();
            $companies = $filter['brands'];
            return view('agency.organization_edit', ['companies' => $companies,'cities' => $cities,'org_query' => $org_query , 'countries' => $countries]);
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
        $user->type = 'agency';
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
        $org->brand_id = $request->brand_id;
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
        $org->city = $request->city;
        $org->c_address = $request->c_address;
        $org->save();
        //Log
        $data = [
            'type' => 'info',
            'note' => json_encode([
                'title' => 'Organization Updated',
                'message' => 'Organization updated successfully'
            ]),
            'module_id' => $user->id,
            'module_type' => 'agency',
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

    public function deleteBulkAgency(Request $request)
    {
        if ($request->ids != null) {
            $Agencies = Agency::whereIn('id', explode(',', $request->ids))->get();
            foreach($Agencies as $Agency){
               User::where('id', $Agency->user_id)->where('type', '=', 'agency')->delete();
               $Agency->delete();
            }
            return redirect()->route('agency.index')->with('success', 'Agency deleted successfully');
        } else {
            return redirect()->route('agency.index')->with('error', 'Atleast select 1 organization.');
        }
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

        if ($request->note_id != null && $request->note_id != '') {
            $note = AgencyNote::where('id', $request->note_id)->first();
            // $note->title = $request->input('title');
            $note->description = $request->input('description');
            $note->update();

            $data = [
                'type' => 'info',
                'note' => json_encode([
                    'title' => 'Agency Notes Updated',
                    'message' => 'Agency notes updated successfully'
                ]),
                'module_id' => $request->id,
                'module_type' => 'agency',
                'notification_type' => 'Agency Notes Updated'
            ];
            addLogActivity($data);


            $notesQuery = AgencyNote::where('agency_id', $id);

            if(\Auth::user()->type != 'super admin' && \Auth::user()->type != 'Project Director' && \Auth::user()->type != 'Project Manager') {
                    $notesQuery->where('created_by', \Auth::user()->id);
            }
            $notes = $notesQuery->orderBy('created_at', 'DESC')->get();
            $html = view('leads.getNotes', compact('notes'))->render();

            return json_encode([
                'status' => 'success',
                'html' => $html,
                'message' =>  __('Notes updated successfully')
            ]);
        }
        $note = new AgencyNote;
        // $note->title = $request->input('title');
        $note->description = $request->input('description');
        $session_id = Session::get('auth_type_id');
        if ($session_id != null) {
            $note->created_by  = $session_id;
        } else {
            $note->created_by  = \Auth::user()->id;
        }
        $note->agency_id = $id;
        $note->save();


        $data = [
            'type' => 'info',
            'note' => json_encode([
                'title' => 'Notes created',
                'message' => 'Noted created successfully'
            ]),
            'module_id' => $id,
            'module_type' => 'agency',
            'notification_type' => 'Notes created'
        ];
        addLogActivity($data);


        $notesQuery = AgencyNote::where('agency_id', $id);

        if(\Auth::user()->type != 'super admin' && \Auth::user()->type != 'Project Director' && \Auth::user()->type != 'Project Manager') {
                $notesQuery->where('created_by', \Auth::user()->id);
        }
        $notes = $notesQuery->orderBy('created_at', 'DESC')->get();

        $html = view('leads.getNotes', compact('notes'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' =>  __('Notes added successfully')
        ]);

        //return redirect()->back()->with('success', __('Notes added successfully'));
    }

    public function UpdateFromAgencyNoteForm(Request $request)
    {
        $note = AgencyNote::where('id', $request->id)->first();

        $html = view('agency.getNotesForm', compact('note'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' =>  __('Notes added successfully')
        ]);
    }


    public function notesEdit($id)
    {
        $note = AgencyNote::where('id', $id)->first();
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

        $note = AgencyNote::where('id', $request->note_id)->first();
        $note->title = $request->input('title');
        $note->description = $request->input('description');
        $note->update();

        $data = [
            'type' => 'info',
            'note' => json_encode([
                'title' => 'Agency Notes Updated',
                'message' => 'Agency notes updated successfully'
            ]),
            'module_id' => $request->id,
            'module_type' => 'agency',
            'notification_type' => 'Agency Notes Updated'
        ];
        addLogActivity($data);


        $notes = AgencyNote::where('agency_id', $id)->orderBy('created_at', 'DESC')->get();
        $html = view('agency.getNotes', compact('notes'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' =>  __('Notes updated successfully')
        ]);
    }


    public function notesDelete(Request $request, $id)
    {

        $note = AgencyNote::where('id', $id)->first();
        $note->delete();

        $notesQuery = AgencyNote::where('agency_id', $request->lead_id);
        if(\Auth::user()->type != 'super admin' && \Auth::user()->type != 'Project Director' && \Auth::user()->type != 'Project Manager') {
                $notesQuery->where('created_by', \Auth::user()->id);
        }
        $notes = $notesQuery->orderBy('created_at', 'DESC')->get();
        $html = view('leads.getNotes', compact('notes'))->render();


        $data = [
            'type' => 'info',
            'note' => json_encode([
                'title' => 'Agency Notes Deleted',
                'message' => 'Agency notes deleted successfully'
            ]),
            'module_id' => $request->lead_id,
            'module_type' => 'agency',
            'notification_type' => 'Agency Notes Deleted'
        ];
        addLogActivity($data);


        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' =>  __('Notes deleted successfully')
        ]);
    }

}
