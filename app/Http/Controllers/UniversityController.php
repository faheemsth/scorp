<?php

namespace App\Http\Controllers;

use App\Models\CourseDuration;
use App\Models\CourseLevel;
use App\Models\Deal;
use App\Models\DealApplication;
use App\Models\InstituteCategory;
use App\Models\Stage;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function index()
    {
        $num_results_on_page = 25;

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = ($page - 1) * $num_results_on_page;
        } else {
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = 0;
        }

        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('manage university')) {

            $universities = University::when(!empty($_GET['name']), function ($query) {
                return $query->where('name', 'like', '%' . $_GET['name'] . '%');
            })
            ->when(!empty($_GET['country']), function ($query) {
                return $query->where('country', 'like', '%' . $_GET['country'] . '%');
            })

            ->when(!empty($_GET['city']), function ($query) {
                return $query->where('city', 'like', '%' . $_GET['city'] . '%');
            })

            ->when(!empty($_GET['note']), function ($query) {
                return $query->where('note', 'like', '%' . $_GET['note'] . '%');
            })

            ->when(!empty($_GET['created_by']), function ($query) {
                return $query->where('created_by', 'like', '%' . $_GET['created_by'] . '%');
            })

            ->skip($start)
            ->take($num_results_on_page)
            ->paginate($num_results_on_page);

            $users = User::get()->pluck('name', 'id');

            $universityStatsByCountries = University::selectRaw('count(id) as total_universities, country')
                ->groupBy('country')
                ->get();
            $statuses = [];
            foreach ($universityStatsByCountries as $university) {
                $statuses[$university->country] = $university->total_universities;
            }

            $data = [
                'universities' => $universities,
                'users' => $users,
                'statuses' => $statuses,
                'total_records' => $universities->total(), // Use total() for total record count
            ];

            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
                $html = view('university.university_list_ajax', $data)->render();

                return json_encode([
                    'status' => 'success',
                    'html' => $html
                ]);
            }

            $data['users'] = allUsers();

            return view('university.index', $data);
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
        if (\Auth::user()->can('create university')) {

            //getting countries
            $countries = countries();

            //months
            $months = months();

            //getting companies
            $companies = FiltersBrands();

            $categories = InstituteCategory::pluck('name', 'id');
            $categories->prepend('', 'Select Category');

            $data = [
                'countries' => $countries,
                'companies' => $companies,
                'months'  => $months,
                'categories' => $categories
            ];

            return view('university.create', $data);
        } else {
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
        // echo "<pre>";
        // print_r($request->input());
        // die();
        //
        if (\Auth::user()->can('create university')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:20',
                    'country' => 'required|max:20',
                    'city' => 'required|max:20',
                  //  'phone' => 'required|max:20',
                   // 'note' => 'required',
                   'months' => 'required',
                   'territory' => 'required',
                   'company_id' => 'required',
                    'category_id' => 'required'
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return json_encode([
                    'status' => 'error',
                    'message' => $messages->first()
                ]);
                //return redirect()->route('university.index')->with('error', $messages->first());
            }

            $university              = new University();
            $university->name        = $request->name;
            $university->country        = $request->country;
            $university->city        = $request->city;
            $university->campuses        = $request->city;
            $university->phone        = $request->phone;
            $university->note        = $request->note;
            $university->created_by = \Auth::user()->id;
            $university->intake_months = implode(',', $request->months);
            $university->territory = implode(',', $request->territory);
            $university->company_id = $request->company_id;
            $university->resource_drive_link = $request->resource_drive_link;
            $university->application_method_drive_link = $request->application_method_drive_link;
            $university->institute_category_id = $request->category_id;
            // $image = $request->file('image');
            // $imageName = time() . '_' . $image->getClientOriginalName();
            // $image->move(public_path('images'), $imageName);
            // $university->image = 'images/' . $imageName;
            $university->save();


            $data = [
                'type' => 'info',
                'note' => json_encode([
                                'title' => 'University Created',
                                'message' => 'University Created successfully'
                            ]),
                'module_id' => 2,
                'module_type' => 'university',
            ];
            addLogActivity($data);


            return json_encode([
                'status' => 'success',
                'id' => $university->id,
                'message' => 'University created successfully.'
            ]);
           // return redirect()->route('university.index')->with('success', __('University successfully created!'));
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Permission Denied.'
            ]);
            //return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function show(University $university)
    {
        //
        return redirect()->route('university.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        //
        if (\Auth::user()->can('edit university')) {
            $university = University::find($id);

            //getting countries
            $countries = countries();

            //months
            $months = months();

            //getting companies
            $companies = companies();

            $categories = InstituteCategory::pluck('name', 'id');
            $categories->prepend('', 'Select Category');

            $data = [
                'countries' => $countries,
                'companies' => $companies,
                'months'  => $months,
                'university' => $university,
                'categories' => $categories
            ];

            return view('university.edit', $data);
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, University $university)
    {
        //
        if (\Auth::user()->can('edit university')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:20',
                    'country' => 'required|max:20',
                    'city' => 'required|max:20',
                    'months' => 'required',
                    'territory' => 'required',
                    'company_id' => 'required',
                    'category_id' => 'required'
                    //'phone' => 'required|max:20',
                    //'note' => 'required'
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return json_encode([
                    'status' => 'error',
                    'message' => $messages->first()
                ]);
               // return redirect()->route('users')->with('error', $messages->first());
            }

            $university->name        = $request->name;
            $university->country        = $request->country;
            $university->city        = $request->city;
            $university->campuses        = $request->city;
            $university->phone        = $request->phone;
            $university->note        = $request->note;
            $university->intake_months = implode(',', $request->months);
            $university->territory = implode(',', $request->territory);
            $university->company_id = $request->company_id;
            $university->resource_drive_link = $request->resource_drive_link;
            $university->application_method_drive_link = $request->application_method_drive_link;
            $university->institute_category_id = $request->category_id;
            $university->save();

            $data = [
                'type' => 'info',
                'note' => json_encode([
                                'title' => 'University Updated',
                                'message' => 'University updated successfully'
                            ]),
                'module_id' => 2,
                'module_type' => 'university',
            ];
            addLogActivity($data);

            return json_encode([
                'status' => 'success',
                'id'  => $university->id,
                'message' => 'University updated successfully.'
            ]);

            //return redirect()->route('university.index')->with('success', __('University successfully updated!'));
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Permission Denied.'
            ]);
            //return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if (\Auth::user()->can('delete university')) {
            University::find($id)->delete();

            $data = [
                'type' => 'info',
                'note' => json_encode([
                                'title' => 'University Deleted',
                                'message' => 'University deleted successfully'
                            ]),
                'module_id' => 2,
                'module_type' => 'university',
            ];
            addLogActivity($data);

            return redirect()->route('')->with('success', __('University successfully deleted!'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function universityDetail($id)
    {
        $university = University::findOrFail($id);

        //related applications
        $applications = DealApplication::where('university_id', $id)->get();

        //related admissions
        $deals = Deal::where('university_id', $id)->get();

        $dealArr = Deal::get()->pluck('name', 'id')->toArray();
        $stages = Stage::get()->pluck('name', 'id')->toArray();
        $organizations = User::where('type', 'organization')->pluck('name', 'id')->toArray();

        $users = User::get()->pluck('name', 'id')->toArray();

        $html = view('university.universityDetail', compact('university', 'applications', 'deals', 'users', 'dealArr', 'stages', 'organizations'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function getIntakeMonths()
    {
        $id = $_GET['id'];
        $university = University::where('id', $id)->first();
        $html = '<option value=""> Select month </option>';
        


        if ($university) {

            $intake_months = $university->intake_months;
            $uni_months = explode(',', $intake_months);
            $months = months();


            foreach ($months as $key => $month) {
                if (in_array($key, $uni_months)) {
                    $html .= '<option value="' . $key . '"> ' . $month . ' </option>';
                }
            }
           // $html .= '</select>';
        }
                
        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }
}
