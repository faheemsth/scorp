<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Deal;
use App\Models\Lead;
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

class OrganizationController extends Controller
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

    // public function countries_list()
    // {
    //     $url = "https://restcountries.com/v3.1/all";  // URL of the API endpoint

    //     $response = file_get_contents($url);  // Fetch the data

    //     $countries = [
    //         'pakistan' => 'Pakistan',
    //         'india' => 'India'
    //     ];

    //     return $countries;

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


    private function organizationsFilter()
    {
        $filters = [];
        if (isset($_GET['name']) && !empty($_GET['name'])) {
            $filters['name'] = $_GET['name'];
        }


        if (isset($_GET['phone']) && !empty($_GET['phone'])) {
            $filters['phone'] = $_GET['phone'];
        }

        if (isset($_GET['street']) && !empty($_GET['street'])) {
            $filters['street'] = $_GET['street'];
        }

        if (isset($_GET['state']) && !empty($_GET['state'])) {
            $filters['state'] = $_GET['state'];
        }

        if (isset($_GET['city']) && !empty($_GET['city'])) {
            $filters['city'] = $_GET['city'];
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
        //
        //$organizations = Organization::get();
        // if (\Auth::user()->type == 'super admin') {

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

            $org_query = User::select(['users.*'])->join('organizations', 'organizations.user_id', '=', 'users.id')->where('users.type', 'organization');
            $filters = $this->organizationsFilter();
            foreach ($filters as $column => $value) {
                if ($column === 'name') {
                    $org_query->whereIn('name', $value);
                } elseif ($column === 'phone') {
                    $org_query->where('organizations.phone', 'LIKE', '%' . $value . '%');
                } elseif ($column === 'street') {
                    $org_query->where('organizations.billing_street', 'LIKE', '%' . $value . '%');
                } elseif ($column == 'city') {
                    $org_query->where('organizations.billing_city', 'LIKE', '%' . $value . '%');
                } elseif ($column == 'state') {
                    $org_query->where('organizations.billing_state', 'LIKE', '%' . $value . '%');
                } elseif ($column === 'country') {
                    $org_query->whereIn('organizations.billing_country', $value);
                }
            }

            //if list global search
            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true' && isset($_GET['search']) && !empty($_GET['search'])) {
                $g_search = $_GET['search'];
                $org_query->Where('users.name', 'like', '%' . $g_search . '%');
                $org_query->orWhere('organizations.billing_street', 'like', '%' . $g_search . '%');
                $org_query->orWhere('organizations.billing_city', 'like', '%' . $g_search . '%');
                $org_query->orWhere('organizations.billing_state', 'like', '%' . $g_search . '%');
                $org_query->orWhere('organizations.billing_country', 'like', '%' . $g_search . '%');
            }

            $total_records  = $org_query->count();
            $organizations = $org_query->orderBy('organizations.created_at', 'desc')->skip($start)->take($num_results_on_page)->get();


            $org_types = OrganizationType::get()->pluck('name', 'id');
            $countries = $this->countries_list();
            $user_type = User::get()->pluck('type', 'id')->toArray();
            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
                $html = view('organizations.organization_list', compact('organizations', 'org_types', 'countries', 'user_type'))->render();
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
                return view('organizations.index', compact('organizations', 'org_types', 'countries', 'user_type', 'total_records'));
            }
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
            return view('organizations.organization_create',  $data);
        } else {
            // return json_encode([
            //     'status' => 'error',
            //     'message' =>  'Permission Denied.'
            // ]);

            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('create organization')) {

            //
            $validator = \Validator::make(
                $request->all(),
                [
                    'organization_name' => 'required',
                    'organization_type' => 'required',
                    'organization_email' => 'required|unique:users,email',
                    'organization_phone' => 'required',
                    //'organization_website' => 'required',
                    //'organization_linkedin' => 'required',
                    //'organization_facebook' => 'required',
                    //'organization_twitter' => 'required',
                    //'organization_billing_street' => 'required',
                    //'organization_billing_city' => 'required',
                    //'organization_billing_state' => 'required',
                    //'organization_billing_postal_code' => 'required',
                    //'organization_billing_country' => 'required',
                    // 'organization_description' => 'required'
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
            $user->type = 'organization';
            $user->email =  $request->organization_email;
            $user->password = Hash::make('123456789');
            $user->is_active = 1;
            $user->lang = 'en';
            $user->mode = 'light';
            $user->created_by = \Auth::user()->id;
            //$user->passport_number = '';
            $user->save();

            $org =  Organization::create([
                'type' => $request->organization_type,
                'phone' =>  $request->organization_phone,
                'website' => $request->organization_website,
                'linkedin' => $request->organization_linkedin,
                'facebook' => $request->organization_facebook,
                'twitter' => $request->organization_twitter,
                'billing_street' => $request->organization_billing_street,
                'contactname' => $request->contactname,
                'contactemail' => $request->contactemail,
                'contactphone' => $request->contactphone,
                'contactjobroll' => $request->contactjobroll,
                'billing_country' => $request->organization_billing_country,
                'description' => $request->organization_description,
            ]);

            $org->user_id = $user->id;
            $org->save();


            //Log
            $data = [
                'type' => 'info',
                'note' => json_encode([
                    'title' => 'Organization Created',
                    'message' => 'Organization created successfully'
                ]),
                'module_id' => $user->id,
                'module_type' => 'organization',
                'notification_type' => 'Organization Created'
            ];
            addLogActivity($data);


            //$org_data = Organization::where('user_id', $user->id)->first();
            //$html = view('organizations.new_organization', ['org' => $user, 'org_data' => $org_data])->render();

            return json_encode([
                'status' => 'success',
                'message' => 'Organization created successfully!.',
                // 'html' => $html,
                'org' => $user
            ]);
            // return redirect()->back()->with('success', 'Organization created successfully!.');
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Permission Denied.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('edit organization')) {

            $org = User::where('id', $id)->first();
            $org_detail = Organization::where('user_id', $org->id)->first();
            $org_types = OrganizationType::get()->pluck('name', 'id');
            $countries = $this->countries_list();
            return view('organizations.organization_edit', ['org' => $org, 'org_detail' => $org_detail, 'org_types' => $org_types, 'countries' => $countries]);
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        //Creating users
        $user = User::where('id', $id)->first();
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



        Organization::where('user_id', $user->id)->update([
            'user_id' => $user->id,
            'type' => $request->organization_type,
            'phone' =>  $request->organization_phone,
            'website' => $request->organization_website,
            'linkedin' => $request->organization_linkedin,
            'facebook' => $request->organization_facebook,
            'twitter' => $request->organization_twitter,
            'billing_street' => $request->organization_billing_street,
            'contactname' => $request->contactname,
            'contactemail' => $request->contactemail,
            'contactphone' => $request->contactphone,
            'contactjobroll' => $request->contactjobroll,
            'billing_country' => $request->organization_billing_country,
            'description' => $request->organization_description,
        ]);

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
            'org_id' => $user->id,
            'message' =>  __('Organization successfully updated!')
        ]);

        ///return redirect()->back()->with('success', 'Organization updated successfully!.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('delete organization')) {
            //
            // if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
            $org = User::find($id);
            $org->delete();

            $org_data = Organization::where('user_id', $id)->first();
            if ($org_data)
                $org_data->delete();


            // return json_encode([
            //     'status' => 'success',
            //     'message' => __('Organization successfully deleted!')
            // ]);
            return redirect()->route('organization.index')->with('success', __('Organization successfully deleted!'));
            // } else {
            //     return redirect()->back()->with('error', __('Permission Denied.'));
            // }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }




    public function getOrganizationDetails()
    {


        $org_id = $_GET['org_id'];


        $org = User::where(['id' => $org_id, 'type' => 'organization'])->first();
        $org_detail = Organization::where('user_id', $org->id)->first();

        $types = OrganizationType::get()->pluck('name', 'id')->toArray();
        $discussions = OrganizationDiscussion::select('organization_discussions.id', 'organization_discussions.comment', 'organization_discussions.created_at', 'users.name', 'users.avatar')->join('users', 'organization_discussions.created_by', 'users.id')->where(['organization_discussions.organization_id' => $org->id])->orderBy('organization_discussions.created_at', 'DESC')->get()->toArray();
        $tasks = \App\Models\DealTask::where(['related_to' => $org->id, 'related_type' => 'organization'])->get();
        $log_activities = getLogActivity($org->id, 'organization');

        $html = view('organizations.organizationDetail', compact('org', 'org_detail', 'types', 'discussions', 'tasks', 'log_activities'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }




    public function driveLink(Request $request)
    {
        $id = $request->id;
        if (isset($request->link)) {

            $link = $request->link;
            Organization::where('id', $id)
                ->update(['drive_link' => $link]);
        }


        $link = Organization::where('id', $id)->first();

        return json_encode([
            'status' => 'success',
            'message' => __('Drive Link added successfully'),
            'org_id' => $id,
            'link' => isset($link->drive_link) ?  $link->drive_link : ''
        ]);
    }


    public function driveLinkUpdate(Request $request)
    {
        $id = $request->id;

        $link = $request->link;
        Organization::where('id', $id)
            ->update(['drive_link' => $link]);
        $msg = '';
        if (empty($link)) {
            $msg = __('Drive Link updated successfully');
        } else {
            $msg = __('Drive Link deleted successfully');
        }
        return json_encode([
            'status' => 'success',
            'message' => $msg,
            'org_id' => $id
        ]);
    }



    public function fetchOrgField(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $org = User::select([$name == 'type' ? 'organizations.type' : $name])->join('organizations', 'organizations.user_id', '=', 'users.id')->where('users.id', $id)->first();


        $data['org'] = $org;
        $data['name'] = $name;

        if ($name == 'type') {
            $types = OrganizationType::get()->pluck('name', 'id')->toArray();
            $data['types'] = $types;
        }

        $html = view('organizations.org_field_edit', $data)->render();

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }


    public function updateOrgData(Request $request)
    {

        $id = $request->id;
        $name = $request->name;
        $value = $request->value;

        if ($name == 'name' || $name == 'email') {
            User::where(['id' => $id, 'type' => 'organization'])->update([
                "$name" => $value
            ]);
        } else {
            Organization::where('user_id', $id)->update([
                "$name" => $value
            ]);
        }

        $org = User::select([$name == 'type' ? 'organizations.type' : $name])->join('organizations', 'organizations.user_id', '=', 'users.id')->where('users.id', $id)->first();


        $data['org'] = $org;
        $data['name'] = $name;

        if ($name == 'type') {
            $types = OrganizationType::get()->pluck('name', 'id')->toArray();
            $data['types'] = $types;
        }


        $html = view('organizations.org_field_fetch', $data)->render();


        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' => 'Organization ' . $name . ' updated successfully'
        ]);
    }

    public function FetchAddress(Request $request)
    {
        $org_id = $request->id;

        $org = Organization::where('user_id', $org_id)->first();

        $countries = $this->countries_list();
        $html = view('organizations.address_edit', compact('org', 'countries'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function saveAddress(Request $request)
    {
        $org_id = $request->id;
        $street = $request->street;
        $city = $request->city;
        $state = $request->state;
        $postal_code = $request->postal_code;
        $country = $request->country;

        Organization::where('user_id', $org_id)->update([
            'billing_street' => $street,
            'billing_city' => $city,
            'billing_state' => $state,
            'billing_postal_code' => $postal_code,
            'billing_country' => $country,

        ]);

        $org = Organization::where('user_id', $org_id)->first();
        $countries = $this->countries_list();
        $html = view('organizations.address_fetch', compact('org', 'countries'))->render();

        return json_encode([
            'status' => 'success',
            'message' => 'Organization address updated successfully.',
            'html' => $html
        ]);
    }

    public function editTags($id)
    {
        return view('organizations.tags');
    }


    public function discussionCreate($id)
    {
        $organization = User::where('type', 'organization')->first();
        return view('organizations.discussions', compact('organization'));
        // $lead = Lead::find($id);
        // if ($lead->created_by == \Auth::user()->creatorId()) {
        //     return view('leads.discussions', compact('lead'));
        // } else {
        //     return response()->json(['error' => __('Permission Denied.')], 401);
        // }
    }

    public function discussionStore($id, Request $request)
    {

        $usr        = \Auth::user();

        // $org_id = User::where('id', $id)->first();

        //if ($lead->created_by == $usr->creatorId()) {
        $discussion             = new OrganizationDiscussion();
        $discussion->comment    = $request->comment;
        $discussion->organization_id    = $id;
        $discussion->created_by = $usr->id;
        $discussion->save();

        $discussions = OrganizationDiscussion::select('organization_discussions.id', 'organization_discussions.comment', 'organization_discussions.created_at', 'users.name', 'users.avatar')->join('users', 'organization_discussions.created_by', 'users.id')->where(['organization_discussions.organization_id' => $id])->orderBy('organization_discussions.created_at', 'DESC')->get()->toArray();


        $html = view('organizations.getDiscussions', compact('discussions'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' => __('Message successfully added!')
        ]);

        return redirect()->back()->with('success', __('Message successfully added!'))->with('status', 'discussion');
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'discussion');
        // }
    }

    public function notesCreate($id)
    {
        $organization = User::where('id', $id)->first();
        return view('organizations.notes', compact('organization'));
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


        // $id = $request->id;
        // $note = new OrganizationNote();
        // $note->title = $request->input('title');
        // $note->description = $request->input('description');
        // $note->created_by = \Auth::user()->id;
        // $note->organization_id = $id;
        // $note->save();


        $id = $request->id;

        if ($request->note_id != null && $request->note_id != '') {
            $note = OrganizationNote::where('id', $request->note_id)->first();
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
                'notification_type' => 'Lead Notes Updated'
            ];
            addLogActivity($data);


            $notes = OrganizationNote::where('organization_id', $id)->orderBy('created_at', 'DESC')->get();
            $html = view('leads.getNotes', compact('notes'))->render();

            return json_encode([
                'status' => 'success',
                'html' => $html,
                'message' =>  __('Notes updated successfully')
            ]);
        }
        $note = new OrganizationNote;
        // $note->title = $request->input('title');
        $note->description = $request->input('description');
        $session_id = Session::get('auth_type_id');
        if ($session_id != null) {
            $note->created_by  = $session_id;
        } else {
            $note->created_by  = \Auth::user()->id;
        }
        $note->organization_id = $id;
        $note->save();


        $data = [
            'type' => 'info',
            'note' => json_encode([
                'title' => 'Notes created',
                'message' => 'Noted created successfully'
            ]),
            'module_id' => $id,
            'module_type' => 'lead',
            'notification_type' => 'Notes created'
        ];
        addLogActivity($data);

        $notes = OrganizationNote::where('organization_id', $id)->orderBy('created_at', 'DESC')->get();
        $html = view('organizations.getNotes', compact('notes'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' =>  __('Notes added successfully')
        ]);

        //return redirect()->back()->with('success', __('Notes added successfully'));
    }

    public function notesEdit($id)
    {
        $note = OrganizationNote::where('id', $id)->first();
        return view('organizations.notes_edit', compact('note'));
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

        $note = OrganizationNote::where('id', $request->note_id)->first();
        $note->title = $request->input('title');
        $note->description = $request->input('description');
        $note->update();

        $notes = OrganizationNote::where('organization_id', $id)->orderBy('created_at', 'DESC')->get();
        $html = view('organizations.getNotes', compact('notes'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' =>  __('Notes updated successfully')
        ]);
    }


    //organization_id
    public function notesDelete(Request $request, $id)
    {

        $note = OrganizationNote::where('id', $id)->first();
        $note->delete();

        $notes = OrganizationNote::where('organization_id', $request->organization_id)->orderBy('created_at', 'DESC')->get();
        $html = view('organizations.getNotes', compact('notes'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html,
            'message' =>  __('Notes deleted successfully')
        ]);

        //return redirect()->route('leads.list')->with('success', __('Notes deleted successfully'));
    }



    public function GetBranchByType()
    {
        $type = $_GET['type'];
        $BranchId = $_GET['id'];

        if ($type == 'lead') {
            $leads = \App\Models\Lead::where('branch_id', $BranchId)->get()->pluck('name', 'id')->toArray();
            $html = '<select class="form form-control select2" id="choices-multiple8" name="related_to" > <option value="">Related To</option> ';
            foreach ($leads as $key => $lead) {
                $html .= '<option value="' . $key . '">' . $lead . '</option> ';
            }
            $html .= '</select>';
            return json_encode([
                'status' => 'success',
                'branches' => $html,
            ]);
        } else if ($type == 'organization') {
            $users = User::where('type', 'organization')->get()->pluck('name', 'id')->toArray();
            $html = '<select class="form form-control select2" id="choices-multiple8" name="related_to" > <option value="">Related To</option> ';
            foreach ($users as $key => $user) {
                $html .= '<option value="' . $key . '">' . $user . '</option> ';
            }
            $html .= '</select>';
            return json_encode([
                'status' => 'success',
                'branches' => $html,
            ]);
        } else if ($type == 'deal') {
            $users = Deal::where('branch_id', $BranchId)->get()->pluck('name', 'id')->toArray();
            $html = '<select class="form form-control select2" id="choices-multiple8" name="related_to" > <option value="">Related To</option> ';
            foreach ($users as $key => $user) {
                $html .= '<option value="' . $key . '">' . $user . '</option> ';
            }
            $html .= '</select>';
            return json_encode([
                'status' => 'success',
                'branches' => $html,
            ]);
        }else if ($type == 'application') {
            $users = DealApplication::join('deals', 'deals.id', '=', 'deal_applications.deal_id')
                        ->where('deals.branch_id', $BranchId)
                        ->get()
                        ->pluck('application_key', 'id')
                        ->toArray();
            $html = '<select class="form form-control select2" id="choices-multiple8" name="related_to" > <option value="">Related To</option> ';
            foreach ($users as $key => $user) {
                $html .= '<option value="' . $key . '">' . $user . '</option> ';
            }
            $html .= '</select>';
            return json_encode([
                'status' => 'success',
                'branches' => $html,
            ]);
        } else {
            $branches = User::where('branch_id', $BranchId)->where('type', 'organization')->pluck('name', 'id')->toArray();
            $html = '<select class="form form-control select2" id="branch_id" name="related_to" > <option value="">Related To</option> ';
            foreach ($branches as $key => $branch) {
                $html .= '<option value="' . $key . '">' . $branch . '</option> ';
            }
            $html .= '</select>';
            return json_encode([
                'status' => 'success',
                'branches' => $html,
            ]);
        }
    }
    public function taskCreate(Request $request, $id)
    {

        if (\Auth::user()->can('create task')) {
            $deals = Deal::get()->pluck('name', 'id')->toArray();
            $orgs = User::where('type', 'organization')->get()->pluck('name', 'id')->toArray();
            $priorities = DealTask::$priorities;
            $status     = DealTask::$status;
            $users = User::orderBy('name', 'ASC')->get()->pluck('name', 'id')->toArray();

            $stages = Stage::get()->pluck('name', 'id')->toArray();
            /// $branches = ['' => 'Select Branch'];
            // $employees = User::where('type', 'employee')->get()->pluck('name', 'id')->toArray();
            $teams = User::where('type', 'team')->get()->pluck('name', 'id')->toArray();
            $user_type = User::get()->pluck('type', 'id')->toArray();


            $employees = [];
            if (\Auth::user()->type == 'company') {
                $employees =  User::where('created_by', $id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            }


            $type = '';
            $typeId = '';

            $relateds = [];
            $organization = null;
            if (isset($_GET['typeid']) && $_GET['type'] == 'organization') {
                $organization = User::where('id', $_GET['typeid'])->first();
            }

            $lead = null;
            if (isset($_GET['typeid']) && $_GET['type'] == 'lead') {
                $lead = Lead::where('id', $_GET['typeid'])->first();
            }


            $deal = null;
            if (isset($_GET['typeid']) && $_GET['type'] == 'deal') {
                $deal = Deal::where('id', $_GET['typeid'])->first();
            }

            $application = null;
            if (isset($_GET['typeid']) && $_GET['type'] == 'application') {
                $application = DealApplication::where('id', $_GET['typeid'])->first();
            }



            if (isset($_GET['type']) && isset($_GET['typeid'])) {
                $type = $_GET['type'];
                $typeId = $_GET['typeid'];

                if ($type == 'lead') {
                    $relateds = \App\Models\Lead::get()->pluck('name', 'id')->toArray();
                } else if ($type == 'organization') {
                    $relateds = User::where('type', 'organization')->pluck('name', 'id')->toArray();
                } else if ($type == 'deal') {
                    $relateds = Deal::orderBy('name', 'ASC')->get()->pluck('name', 'id')->toArray();
                }else if ($type == 'application') {
                    $relateds = DealApplication::orderBy('name', 'ASC')->get()->pluck('application_key', 'id')->toArray();
                }
            }
            // if(\Auth::user()->type == 'company'){
            //     // $Region = Region::where('brands', \Auth::user()->id)->orderBy('name', 'ASC')->pluck('name', 'id');
            //     $Region = DB::table('regions')->whereRaw('FIND_IN_SET('.\Auth::user()->id.', brands)')->orderBy('name', 'ASC')->pluck('name', 'id');
            //     // dd($Region);
            //     $Region= ['' => 'Select Region'] + $Region->toArray();
            // }else{
            //     $Region= ['' => 'Select Region'];
            // }

            //$Region= ['' => 'Select Region'];

            //function will return all the relevent brands, regions,leads
            $filter = BrandsRegionsBranches();
            $companies = $filter['brands'];
            $Region = $filter['regions'];
            $branches = $filter['branches'];
            $employees = $filter['employees'];

            return view('organizations.tasks', compact('Region', 'users', 'deals', 'organization', 'orgs', 'priorities', 'status', 'branches', 'stages', 'employees', 'teams', 'companies', 'user_type', 'type', 'typeId', 'relateds', 'lead', 'deal', 'branches', 'application'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function taskStore($id, Request $request)
    {
        $usr = \Auth::user();
        if (\Auth::user()->can('create task')) {

            $rules = [
                'task_name' => 'required',
                'brand_id' => 'required|integer|min:1',
                'region_id' => 'required|integer|min:1',
                'branch_id' => 'required|integer|min:1',
                'assigned_to' => 'required|integer|min:1',
                'assign_type' => 'required',
                'due_date' => 'required',
                'start_date' => 'required',
            ];

            $messages = [
                'brand_id.min' => 'The brand id must be Required',
                'region_id.min' => 'The Region id must be Required',
                'branch_id.min' => 'The branch id must be Required',
                'assigned_to.min' => 'The Assigned id must be Required',
            ];

            $validator = \Validator::make($request->all(), $rules, $messages);


            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return json_encode([
                    'status' => 'error',
                    'message' => $messages->first()
                ]);
            }


            ///////////
            $dealTask = new  DealTask();
            $dealTask->deal_id = isset($request->related_to) ? $request->related_to : 0;
            $dealTask->related_to = isset($request->related_to) ? $request->related_to : 0;
            $dealTask->related_type = isset($request->related_type) ? $request->related_type : '';

            $dealTask->name = $request->task_name;
            $dealTask->branch_id = $request->branch_id;
            $dealTask->region_id = $request->region_id;

            $dealTask->brand_id = Session::get('auth_type_id') != null ? \Auth::user()->id : $request->brand_id;
            $dealTask->created_by = Session::get('auth_type_id') != null ? Session::get('auth_type_id') : \Auth::user()->id;

            $dealTask->assigned_to = $request->assigned_to;
            $dealTask->assigned_type = $request->assign_type;

            $dealTask->due_date = isset($request->due_date) ? $request->due_date : '';
            $dealTask->start_date = $request->start_date;
            $dealTask->date = $request->start_date;
            $dealTask->status = 0;
            $dealTask->remainder_date = $request->remainder_date;
            $dealTask->description = $request->description;
            $dealTask->visibility = $request->visibility;
            $dealTask->priority = 1;
            $dealTask->time = isset($request->remainder_time) ? $request->remainder_time : '';
            $dealTask->save();



            // ActivityLog::create(
            //     [
            //         'user_id' => $usr->id,
            //         'deal_id' => $dealTask->,
            //         'log_type' => 'Create Task',
            //         'remark' => json_encode(['title' => $dealTask->name]),
            //     ]
            // );


            //store Activity Log
            $remarks = [
                'title' => 'Task Created',
                'message' => 'Task Created successfully'
            ];

            //store Log
            $data = [
                'type' => 'info',
                'note' => json_encode($remarks),
                'module_id' => $dealTask->id,
                'module_type' => 'task',
                'notification_type' => 'Task created'
            ];
            addLogActivity($data);

            return json_encode([
                'status' => 'success',
                'task_id' => $dealTask->id,
                'message' => __('Task successfully created!')
            ]);
        } else {

            return json_encode([
                'status' => 'error',
                'message' => __('Permission Denied.')
            ]);
        }
    }

    public function taskEdit($id)
    {

        if (\Auth::user()->can('edit task')) {

            $task = DealTask::where('id', $id)->first();

            $deals = Deal::orderBy('name', 'ASC')->get()->pluck('name', 'id')->toArray();
            $orgs = User::where('type', 'organization')->orderBy('name', 'ASC')->get()->pluck('name', 'id')->toArray();

            $priorities = DealTask::$priorities;
            $status     = DealTask::$status;


            // if (\Auth::user()->type == 'super admin') {
            //     $branches = Branch::where('brands',DealTask::where('id', $id)->first()->brand_id)->get()->pluck('name', 'id')->toArray();
            // } else {

            //     $branches = Branch::where('brands',DealTask::where('id', $id)->first()->brand_id)->where('created_by', \Auth::user()->id)->get()->pluck('name', 'id')->toArray();
            // }

            // $branches = Branch::where('id',DealTask::where('id', $id)->first()->branch_id)->get()->pluck('name', 'id')->toArray();
            $stages = Stage::get()->pluck('name', 'id')->toArray();


            if ($task->assigned_type == 'company') {
                $users = User::where('type', 'company')->orderBy('name', 'ASC')->get()->pluck('name', 'id')->toArray();
            } else {
                $users = User::where('type', 'employee')->orderBy('name', 'ASC')->get()->pluck('name', 'id')->toArray();
            }


            $related_to = [];
            if ($task->related_type == 'organization') {
                $related_to = User::where('type', 'organization')->orderBy('name', 'ASC')->get()->pluck('name', 'id')->toArray();
            } else if ($task->related_type == 'lead') {
                $related_to = \App\Models\Lead::orderBy('name', 'ASC')->get()->pluck('name', 'id')->toArray();
            } else if ($task->related_type == 'deal') {
                $related_to = Deal::orderBy('name', 'ASC')->get()->pluck('name', 'id')->toArray();
            }

            // if(\Auth::user()->type == 'super admin'){
            //     $companies = User::where('type', 'company')->get()->pluck('name', 'id')->toArray();
            // }else if(\Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager'){
            //     $com_permissions = \App\Models\CompanyPermission::where('user_id', \Auth::user()->id)->where('active', 'true')->get();
            //     $companies = User::where('type', 'company')->whereIn('id', $com_permissions)->orwhere('id', \Auth::user()->created_by)->get()->pluck('name', 'id')->toArray();
            // }else if(\Auth::user()->type == 'company'){
            //     $companies = User::where('type', 'company')->where('id', \Auth::user()->id)->get()->pluck('name', 'id')->toArray();
            // }
            // $companies = FiltersBrands();

            $employees = [];
            // if(\Auth::user()->type == 'company'){
            //    $employees =  User::where('brand_id', DealTask::where('id', $id)->first()->brand_id)->where('created_by', $id)->pluck('name', 'id')->toArray();
            // }else if(\Auth::user()->type == 'super admin'){
            //     $employees =  User::where('brand_id', DealTask::where('id', $id)->first()->brand_id)->pluck('name', 'id')->toArray();
            // }

            //$employees =  User::where('branch_id', $task->branch_id)->pluck('name', 'id')->toArray();
            //$Region=Region::whereRaw('FIND_IN_SET(?, brands)', [$task->brand_id])->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $stages = Stage::get()->pluck('name', 'id')->toArray();
            // dd($branches);

            $filter = BrandsRegionsBranchesForEdit($task->brand_id, $task->region_id, $task->branch_id);
            $companies = $filter['brands'];
            $Region = $filter['regions'];
            $branches = $filter['branches'];
            $employees = $filter['employees'];

            return view('organizations.task_edit', compact('Region', 'task', 'users', 'deals', 'orgs', 'priorities', 'status', 'branches', 'stages', 'related_to', 'companies', 'employees'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function taskUpdate($id, Request $request)
    {
        $usr = \Auth::user();

        if (\Auth::user()->can('edit task')) {

            $rules = [
                'task_name' => 'required',
                'due_date' => 'required',
                'start_date' => 'required',
                'visibility' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return json_encode([
                    'status' => 'error',
                    'message' => $messages->first()
                ]);
            }




            $dealTask = DealTask::where('id', $id)->first();

            $is_status_change = false;
            if ($dealTask->status != $request->status) {
                $is_status_change = true;
            }
            // $dealTask->deal_id = $request->related_to;
            //$dealTask->related_to = $request->related_to;
            //$dealTask->related_type = $request->related_type;

            $dealTask->name = $request->task_name;
            if (isset($request->branch_id)) {
                $dealTask->branch_id = $request->branch_id;
            }

            //$dealTask->organization_id = isset($request->organization_id) ? $request->organization_id : '';

            if (isset($request->assigned_to)) {
                $dealTask->assigned_to = $request->assigned_to;
            }
            if (isset($request->brand_id)) {
                $dealTask->brand_id = $request->brand_id;
            }

            $dealTask->assigned_type = $request->assign_type;
            if (isset($request->region_id)) {
                $dealTask->region_id = $request->region_id;
            }

            // $dealTask->deal_stage_id = $request->stage_id;
            $dealTask->due_date = isset($request->due_date) ? $request->due_date : '';
            $dealTask->start_date = $request->start_date;
            $dealTask->date = $request->start_date;
            if (isset($request->status)) {
                $dealTask->status = $request->status;
            }
            $dealTask->remainder_date = $request->remainder_date;
            $dealTask->description = $request->description;
            $dealTask->visibility = $request->visibility;
            $dealTask->priority = 1;
            $dealTask->time = isset($request->remainder_time) ? $request->remainder_time : '';
            $dealTask->save();



            ActivityLog::create(
                [
                    'user_id' => $usr->id,
                    'deal_id' => $dealTask->deal_id,
                    'log_type' => 'Update Task',
                    'remark' => json_encode(['title' => $dealTask->name]),
                ]
            );

            //store Activity Log
            $remarks = [
                'title' => 'Task Update',
                'message' => 'Task updated successfully'
            ];

            //store Log
            $data = [
                'type' => 'info',
                'note' => json_encode($remarks),
                'module_id' => $dealTask->id,
                'module_type' => 'task',
                'notification_type' => 'Task Update'
            ];
            addLogActivity($data);


            if ($is_status_change) {
                //store Activity Log
                $remarks = [
                    'title' => 'Task Update',
                    'message' => 'Task status updated'
                ];

                //store Log
                $data = [
                    'type' => 'info',
                    'note' => json_encode($remarks),
                    'module_id' => $dealTask->id,
                    'module_type' => 'task',
                    'notification_type' => 'Task Update'
                ];
                addLogActivity($data);
            }


            return json_encode([
                'status' => 'success',
                'task_id' => $dealTask->id,
                'message' => __('Task successfully updated!')
            ]);
        } else {

            return json_encode([
                'status' => 'error',
                'message' => __('Permission Denied.')
            ]);
        }
    }

    //organization_id
    public function taskDelete(Request $request, $id)
    {
        if (\Auth::user()->can('delete task')) {

            $task = DealTask::where('id', $id)->first();
            $task->delete();
            //$tasks = DealTask::where('organization_id', $request->organization_id)->get();
            // $html = view('organizations.all_tasks', compact('tasks'))->render();

            //store Activity Log


            //store Log
            $data = [
                'type' => 'info',
                'note' => json_encode([
                    'title' => 'Task Deleted',
                    'message' => 'Task deleted successfully'
                ]),
                'module_id' => 1,
                'module_type' => 'task',
                'notification_type' => 'Task Deleted'
            ];
            addLogActivity($data);

            return json_encode([
                'status' => 'success',
                //'html' => $html,
                'message' =>  __('Task deleted successfully')
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => __('Permission Denied.')
            ]);
        }
    }

    public function getTaskUsers(Request $request)
    {
        $type = $request->type;
        $branch_id = $request->branch_id;

        // if (\Auth::user()->type == 'Project Manager' || \Auth::user()->type == 'Project Director') {

        //     $currentUserCompany = \App\Models\User::where('type', 'company')->find(\Auth()->user()->created_by);
        // } else if (\Auth::user()->type == 'super admin') {
        //     $currentUserCompany = \App\Models\User::where('type', 'company')->first();
        // } else {
        //     $currentUserCompany = \App\Models\User::where('type', 'company')->find(\Auth()->user()->id);
        // }

        // $com_permissions = \App\Models\CompanyPermission::where('company_id', $currentUserCompany->id)->where('active', 'true')->get();


        $html = '';

        $users = User::whereNotIn('type', ['client', 'company', 'super admin', 'organization', 'team'])
            ->where('branch_id', $branch_id)
            ->pluck('name', 'id');
        $html = ' <select class="form form-control assigned_to select2" id="choices-multiple4" name="assigned_to"> <option value="">Select Employee</option> ';
        foreach ($users as $key => $user) {
            $html .= '<option value="' . $key . '">' . $user . '</option> ';
        }
        $html .= '</select>';
        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }


    public function getTaskRelatedToUsers(Request $request)
    {

        $type = $request->type;
        if ($type == 'organization') {
            $users = User::where(['type' => 'organization', 'created_by' => \Auth::user()->id])->get()->pluck('name', 'id')->toArray();
        } else if ($type == 'lead') {
            // $users = \App\Models\Lead::where(['created_by' => \Auth::user()->id])->get()->pluck('name', 'id')->toArray();
            $users = \App\Models\Lead::where(['brand_id' => $request->brand_id])->get()->pluck('name', 'id')->toArray();
        } else if ($type == 'deal') {
            $users = Deal::where(['created_by' => \Auth::user()->id])->get()->pluck('name', 'id')->toArray();
        }

        $html = '<select class="form form-control related_to select2" id="choices-multiple7" name="related_to"><option value="">Related to</option> ';

        foreach ($users as $key => $user) {
            $html .= '<option value="' . $key . '">' . $user . '</option> ';
        }

        $html .= '</select>';

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }



    public function taskDeleted($id)
    {
        $task = DealTask::findOrFail($id);
        $task->delete();
        return redirect()->route('deals.get.user.tasks')->with('success', __('Task successfully deleted!'));
    }

    public function deleteBulkOrganizations(Request $request)
    {
        if ($request->ids != null) {
            User::whereIn('id', explode(',', $request->ids))->where('type', '=', 'organization')->delete();
            return redirect()->route('organization.index')->with('success', 'Organization deleted successfully');
        } else {
            return redirect()->route('organization.index')->with('error', 'Atleast select 1 organization.');
        }
    }
}
