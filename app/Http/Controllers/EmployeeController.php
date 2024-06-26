<?php

namespace App\Http\Controllers;

use File;
use App\Models\NOC;
use App\Models\Plan;
use App\Models\User;
use App\Models\Branch;
use App\Models\Utility;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Termination;
use Illuminate\Http\Request;
use App\Models\JoiningLetter;
use Maatwebsite\Excel\Writer;
use App\Exports\EmployeeExport;
use App\Imports\EmployeeImport;
use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ExperienceCertificate;
use Illuminate\Support\Facades\Crypt;
use App\Exports\DownloadEmployeeSample;
use App\Models\Region;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

//use Faker\Provider\File;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if user has permission to manage employees
        if (!\Auth::user()->can('manage employee')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        // Get pagination details
        $pagination = getPaginationDetail();
        $start = $pagination['start'];
        $limit = $pagination['num_results_on_page'];

        // Initialize the query
        $usersQuery = User::select(['users.*'])
            ->whereNotIn('type', ['super admin', 'company', 'team', 'client']);

        // Apply filters
        $request = request();
        if ($request->filled('brand')) {
            $usersQuery->where('brand_id', $request->brand);
        }
        if ($request->filled('region_id')) {
            $usersQuery->where('region_id', $request->region_id);
        }
        if ($request->filled('branch_id')) {
            $usersQuery->where('branch_id', $request->branch_id);
        }
        if ($request->filled('Name')) {
            $usersQuery->where('name', 'like', '%' . $request->Name . '%');
        }
        if ($request->filled('Designation')) {
            $usersQuery->where('type', 'like', '%' . $request->Designation . '%');
        }
        if ($request->filled('phone')) {
            $usersQuery->where('phone', 'like', '%' . $request->phone . '%');
        }

        // Apply search filter if provided
        if ($request->filled('search')) {
            $g_search = $request->search;
            $usersQuery->where(function ($query) use ($g_search) {
                $query->where('name', 'like', '%' . $g_search . '%')
                    ->orWhere('email', 'like', '%' . $g_search . '%')
                    ->orWhere('type', 'like', '%' . $g_search . '%')
                    ->orWhere('phone', 'like', '%' . $g_search . '%')
                    ->orWhere(DB::raw('(SELECT name FROM regions r WHERE r.id = users.region_id)'), 'like', '%' . $g_search . '%')
                    ->orWhere(DB::raw('(SELECT name FROM users b WHERE b.id = users.brand_id)'), 'like', '%' . $g_search . '%')
                    ->orWhere(DB::raw('(SELECT name FROM branches br WHERE br.id = users.branch_id)'), 'like', '%' . $g_search . '%');
            });
        }

        // Count total records
        $total_records = $usersQuery->count();
        // Fetch employees with pagination
        $employees = $usersQuery->skip($start)
            ->take($limit)
            ->orderBy('name', 'ASC')
            ->paginate($limit);

        // Prepare data for view
        $data = [
            'employees' => $employees,
            'total_records' => $total_records,
            'filters' => BrandsRegionsBranches(),
            'userRegionBranch' => UserRegionBranch()
        ];

        // Render view or return JSON for AJAX request
        if ($request->filled('ajaxCall') && $request->ajaxCall == 'true') {
            $html = view('employee.employeeAjax', $data)->render();
            $pagination_html = view('layouts.pagination', [
                'total_pages' => $total_records,
                'num_results_on_page' =>  $limit // You need to define $num_results_on_page
            ])->render();
            return json_encode([
                'status' => 'success',
                'html' => $html,
                'pagination_html' => $pagination_html
            ]);
        } else {
            return view('employee.index', $data);
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create employee')) {
            $company_settings = Utility::settings();
            $documents        = Document::get();
            //$branches         = Branch::get()->pluck('name', 'id');
            //$branches->prepend(__('Select Branch'), '');

            $brands = collect(FiltersBrands());
            $brands->prepend(__('Select Brand'), '');


            $regions = [];
            $branches = [];

            $roles = collect(Role::whereNotIn('name', ['super admin', 'company', 'client'])->pluck('name', 'name')->toArray());
            $roles->prepend(__('Select Role'), '');

            //$designations     = Designation::get()->pluck('name', 'id');
            $employees        = User::where('created_by', \Auth::user()->creatorId())->get();
            $employeesId      = \Auth::user()->employeeIdFormat($this->employeeNumber());

            return view('employee.create', compact('employees', 'employeesId', 'documents', 'company_settings', 'brands', 'regions', 'branches', 'roles'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {

        // echo "<pre>";
        // print_r($request->input());
        // die();
        if (\Auth::user()->can('create employee')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'dob' => 'required',
                    // 'gender' => 'required',
                    'phone' => 'required',
                    'address' => 'required',
                    'email' => 'required|unique:users',
                    'password' => 'required',
                    //'department_id' => 'required',
                    //'designation_id' => 'required',
                    //                                   'document.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf,doc,zip|max:20480',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->withInput()->with('error', $messages->first());
            }

            $user               = new User();
            $user['name']       = $request->name;
            $user['email']      = $request->email;
            $psw                = 'study1234';
            $user['password']   = Hash::make($psw);
            $user['type']       =  $request->role;
            $user['branch_id'] = $request->branch_id;
            $user['region_id'] = $request->region_id;
            $user['brand_id'] = $request->brand_id;
            $user['default_pipeline'] = 1;
            $user['plan'] = 1;
            $user['lang']       = !empty($default_language) ? $default_language->value : '';
            $user['created_by'] = \Auth::user()->id;
            $user['plan']       = Plan::first()->id;
            $user['date_of_birth'] = $request->dob;
            $user['phone'] = $request->phone;
            $user->save();

            $role_r = Role::findByName($request->role);

            if ($request->role == 'Project Director') {
                User::where('id', $request->brand_id)->update([
                    'project_director_id' => $user->id
                ]);
            } else if ($request->role == 'Project Manager') {
                User::where('id', $request->brand_id)->update([
                    'project_manager_id' => $user->id
                ]);
            } else if ($request->role == 'Region Manager') {
                Region::where('id', $request->region_id)->update([
                    'region_manager_id' => $user->id
                ]);
            } else if ($request->role == 'Branch Manager') {
                Branch::where('id', $request->branch_id)->update([
                    'branch_manager_id' => $user->id
                ]);
            }
            $user->assignRole($role_r);

            if (!empty($request->document) && !is_null($request->document)) {
                $document_implode = implode(',', array_keys($request->document));
            } else {
                $document_implode = null;
            }

            $employee = Employee::create(
                [
                    'user_id' => $user->id,
                    'name' => $request['name'],
                    'dob' => date('Y-m-d H:i:s', strtotime($request['dob'])),
                    'gender' => $request['gender'],
                    'phone' => $request['phone'],
                    'address' => $request['address'],
                    'email' => $request['email'],
                    'password' => Hash::make($psw),
                    'employee_id' => $this->employeeNumber(),
                    'branch_id' => $request['branch_id'],
                    // 'department_id' => $request['department_id'],
                    //'designation_id' => $request['designation_id'],
                    'company_doj' => $request['company_doj'],
                    'documents' => $document_implode,
                    'account_holder_name' => $request['account_holder_name'],
                    'account_number' => $request['account_number'],
                    'bank_name' => $request['bank_name'],
                    'bank_identifier_code' => $request['bank_identifier_code'],
                    'branch_location' => $request['branch_location'],
                    'tax_payer_id' => $request['tax_payer_id'],
                    'created_by' => \Auth::user()->id,
                ]
            );

            if ($request->hasFile('document')) {
                foreach ($request->document as $key => $document) {

                    $filenameWithExt = $request->file('document')[$key]->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('document')[$key]->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir             = storage_path('uploads/document/');
                    $image_path      = $dir . $filenameWithExt;

                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }

                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $path              = $request->file('document')[$key]->storeAs('uploads/document/', $fileNameToStore);
                    $employee_document = EmployeeDocument::create(
                        [
                            'employee_id' => $employee['employee_id'],
                            'document_id' => $key,
                            'document_value' => $fileNameToStore,
                            'created_by' => \Auth::user()->creatorId(),
                        ]
                    );
                    $employee_document->save();
                }
            }

            $setings = Utility::settings();

            if ($setings['new_user'] == 1) {
                $userArr = [
                    'email' => $user->email,
                    'password' => $user->password,
                ];

                $resp = Utility::sendEmailTemplate('new_user', [$user->id => $user->email], $userArr);
                return redirect()->route('employee.index')->with('success', __('Employee successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }

            return redirect()->route('employee.index')->with('success', __('Employee  successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {

        //$id = Crypt::decrypt($id);
        if (\Auth::user()->can('edit employee')) {
            $documents        = Document::get();
            $brands = collect(FiltersBrands());
            $brands->prepend(__('Select Brand'), '');


            $regions = [];
            $branches = [];


            $roles = collect(Role::whereNotIn('name', ['super admin', 'company', 'client'])->pluck('name', 'name')->toArray());
            $roles->prepend(__('Select Role'), '');
            $employee = Employee::select(['employees.*', 'u.brand_id', 'u.region_id', 'u.branch_id', 'u.type'])->join('users as u', 'u.id', '=', 'employees.user_id')->where('u.id', $id)->first();
            $employeesId  = \Auth::user()->employeeIdFormat(!empty($employee) ? $employee->employee_id : '');

            if(!empty($employee->region_id)){
                $regions = Region::where('id', $employee->region_id)->pluck('name', 'id')->toArray();
            }

            if(!empty($employee->branch_id)){
                $branches = Branch::where('id', $employee->branch_id)->pluck('name', 'id')->toArray();
            }
            return view('employee.edit', compact('employee', 'employeesId', 'documents', 'brands', 'regions', 'branches', 'roles'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {

        if (\Auth::user()->can('edit employee')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'dob' => 'required',
                    'gender' => 'required',
                    'phone' => 'required|numeric',
                    'address' => 'required',
                    //                                   'document.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf,doc,zip|max:20480',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }



            $employee = Employee::findOrFail($id);

            $user               = User::findOrFail($employee->user_id);
            $user['name']       = $request->name;
            $user['email']      = $request->email;
            $user['type']       =  $request->role;
            $user['branch_id'] = $request->branch_id;
            $user['region_id'] = $request->region_id;
            $user['brand_id'] = $request->brand_id;
            $user['date_of_birth'] = $request->dob;
            $user['phone'] = $request->phone;
            $user->save();

            $role_r = Role::findByName($request->role);

            //IF Role is Project Director ya Project Manager
            // IF Role is Region Manager or Branch Manager
            if ($request->role == 'Project Director') {
                User::where('id', $request->brand_id)->update([
                    'project_director_id' => $user->id
                ]);
            } else if ($request->role == 'Project Manager') {
                User::where('id', $request->brand_id)->update([
                    'project_manager_id' => $user->id
                ]);
            } else if ($request->role == 'Region Manager') {
                Region::where('id', $request->region_id)->update([
                    'region_manager_id' => $user->id
                ]);
            } else if ($request->role == 'Branch Manager') {
                Branch::where('id', $request->branch_id)->update([
                    'branch_manager_id' => $user->id
                ]);
            }


            $user->assignRole($role_r);






            if ($request->document) {
                foreach ($request->document as $key => $document) {
                    if (!empty($document)) {
                        $filenameWithExt = $request->file('document')[$key]->getClientOriginalName();
                        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension       = $request->file('document')[$key]->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                        //                        $dir        = storage_path('uploads/document/');
                        $dir             = 'uploads/document/';

                        $image_path = $dir . $filenameWithExt;

                        if (File::exists($image_path)) {
                            File::delete($image_path);
                        }
                        //                        if(!file_exists($dir))
                        //                        {
                        //                            mkdir($dir, 0777, true);
                        //                        }
                        //                        $path = $request->file('document')[$key]->storeAs('uploads/document/', $fileNameToStore);

                        $path = \Utility::upload_coustom_file($request, 'document', $fileNameToStore, $dir, $key, []);


                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                        } else {
                            return redirect()->back()->with('error', __($path['msg']));
                        }


                        $employee_document = EmployeeDocument::where('employee_id', $employee->employee_id)->where('document_id', $key)->first();

                        if (!empty($employee_document)) {
                            $employee_document->document_value = $fileNameToStore;
                            $employee_document->save();
                        } else {
                            $employee_document                 = new EmployeeDocument();
                            $employee_document->employee_id    = $employee->employee_id;
                            $employee_document->document_id    = $key;
                            $employee_document->document_value = $fileNameToStore;
                            $employee_document->save();
                        }
                    }
                }
            }
            return redirect()->route('employee.index')->with('success', 'Employee successfully updated.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('delete employee') || Auth::user()->type == 'super admin') {

            $user          = User::where('id', '=', $id)->first();
            $employee      = Employee::where('user_id', $user->id)->first();
            $emp_documents = EmployeeDocument::where('employee_id', $employee->employee_id)->get();
            $employee->delete();
            $user->delete();
            $dir = storage_path('uploads/document/');

            foreach ($emp_documents as $emp_document) {
                $emp_document->delete();
                if (!empty($emp_document->document_value) && file_exists($dir . $emp_document->document_value)) {
                    unlink($dir . $emp_document->document_value);
                }
            }

            return redirect()->route('employee.index')->with('success', 'Employee successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }



    public function export()
    {
        $name = 'employee_' . date('Y-m-d i:h:s');
        $data = Excel::download(new EmployeeExport(), $name . '.xlsx');
        if (ob_get_length() > 0) {
            ob_end_clean();
        }

        return $data;
    }

    public function importFile()
    {
        return view('employee.import');
    }


    public function import(Request $request)
    {

        $rules = [
            'file' => 'required|mimes:csv,txt',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }



        $objUser        = User::find(\Auth::user()->creatorId());




        $employees = (new EmployeeImport())->toArray(request()->file('file'))[0];

        $totalEmployee = count($employees) - 1;
        $errorArray    = [];
        for ($i = 1; $i <= count($employees) - 1; $i++) {
            $employee = $employees[$i];
            //user create and update
            $userByEmail = User::where('email', $employee[1])->first();
            if (!empty($userByEmail)) {
                $userData = $userByEmail;
            } else {
                $userData = new User();
            }

            $userData->name = $employee[0];
            $userData->email = $employee[1];
            $userData->password = Hash::make($employee[2]);
            $userData->type = 'employee';
            $userData->lang = 'en';
            $userData->created_by = \Auth::user()->id;
            $userData->save();
            $userData->assignRole('Employee');
            //////////////////////////////////



            $employeeByEmail = Employee::where('email', $employees[1])->first();
            if (!empty($employeeByEmail)) {
                $employeeData = $employeeByEmail;
            } else {
                $employeeData = new Employee();
                $employeeData->employee_id      = $this->employeeNumber();
            }

            // $customerData->customer_id             = $customer[0];
            $employeeData->user_id = $userData->id;
            $employeeData->name = $employee[0];
            $employeeData->email    = $employee[1];
            $employeeData->password     = Hash::make($employee[2]);
            $employeeData->phone     =  $employee[3];
            $employeeData->dob          = $employee[4];
            $employeeData->gender        =  $employee[5];
            $employeeData->address  = $employee[6];
            $employeeData->branch_id      = $employee[7];
            $employeeData->department_id  = $employee[8];
            $employeeData->designation_id    = $employee[9];
            $employeeData->company_doj = $employee[10];
            $employeeData->account_holder_name    = $employee[11];
            $employeeData->account_number   = $employee[12];
            $employeeData->bank_name     = $employee[13];
            $employeeData->bank_identifier_code = $employee[14];
            $employeeData->branch_location          = $employee[15];
            $employeeData->tax_payer_id       = $employee[16];
            $employeeData->created_by          = \Auth::user()->id;
            $employeeData->created_at          = date('Y-m-d H:i:s');
            $employeeData->updated_at          = date('Y-m-d H:i:s');

            if (empty($employeeData)) {
                $errorArray[] = $employeeData;
            } else {
                $employeeData->save();
            }

            //sending email
            $setings = Utility::settings();

            if ($setings['new_user'] == 1) {
                $userArr = [
                    'email' => $userData->email,
                    'password' => $userData->password,
                ];

                $resp = Utility::sendEmailTemplate('new_user', [$userData->id => $userData->email], $userArr);
            }
        }

        if (empty($errorArray)) {
            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        } else {
            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalCustomer . ' ' . 'record');


            foreach ($errorArray as $errorData) {

                $errorRecord[] = implode(',', $errorData);
            }

            \Session::put('errorArray', $errorRecord);
        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }

    public function show($id)
    {

        if (\Auth::user()->can('view employee')) {
            $employee = Employee::join('users as u', 'u.id', '=', 'employees.user_id')->where('employees.id', $id)->first();
            $documents = Document::get();
            $userRegionBranch = UserRegionBranch();

            //$empId        = Crypt::decrypt($id);
            //$documents    = Document::where('created_by', \Auth::user()->creatorId())->get();
            $branches     = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments  = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $designations = Designation::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            //$employee     = Employee::where('id', $empId)->first();

            $employeesId  = \Auth::user()->employeeIdFormat(!empty($employee) ? $employee->employee_id : '');


            $html = view('employee.show', compact('employee', 'employeesId', 'branches', 'departments', 'designations', 'documents', 'userRegionBranch'))->render();
            return json_encode([
                'status' => 'success',
                'html' => $html
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'msg' => 'Permissioned denied.'
            ]);

            //return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function json(Request $request)
    {
        // dd($request->department_id);
        $designations = Designation::where('department_id', $request->department_id)->get()->pluck('name', 'id')->toArray();

        return response()->json($designations);
    }

    function employeeNumber()
    {
        $latest = Employee::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->employee_id + 1;
    }

    public function profile(Request $request)
    {
        if (\Auth::user()->can('manage employee profile')) {
            $employees = Employee::where('created_by', \Auth::user()->creatorId());
            if (!empty($request->branch)) {
                $employees->where('branch_id', $request->branch);
            }
            if (!empty($request->department)) {
                $employees->where('department_id', $request->department);
            }
            if (!empty($request->designation)) {
                $employees->where('designation_id', $request->designation);
            }
            $employees = $employees->get();

            $brances = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $brances->prepend('All', '');

            $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments->prepend('All', '');

            $designations = Designation::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $designations->prepend('All', '');

            return view('employee.profile', compact('employees', 'departments', 'designations', 'brances'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function profileShow($id)
    {
        if (\Auth::user()->can('show employee profile')) {
            $empId        = Crypt::decrypt($id);
            $documents    = Document::where('created_by', \Auth::user()->creatorId())->get();
            $branches     = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments  = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $designations = Designation::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employee     = Employee::find($empId);
            $employeesId  = \Auth::user()->employeeIdFormat($employee->employee_id);

            return view('employee.show', compact('employee', 'employeesId', 'branches', 'departments', 'designations', 'documents'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function lastLogin()
    {
        $users = User::where('created_by', \Auth::user()->creatorId())->get();

        return view('employee.lastLogin', compact('users'));
    }

    public function employeeJson(Request $request)
    {
        $employees = Employee::where('branch_id', $request->branch)->get()->pluck('name', 'id')->toArray();

        return response()->json($employees);
    }

    public function getdepartment(Request $request)
    {

        if ($request->branch_id == 0) {
            $departments = Department::get()->pluck('name', 'id')->toArray();
        } else {
            $departments = Department::where('branch_id', $request->branch_id)->get()->pluck('name', 'id')->toArray();
        }

        return response()->json($departments);
    }

    public function joiningletterPdf($id)
    {
        $users = \Auth::user();

        $currantLang = $users->currentLanguage();
        $joiningletter = JoiningLetter::where(['lang' =>   $currantLang, 'created_by' => \Auth::user()->creatorId()])->first();
        $date = date('Y-m-d');
        $employees = Employee::find($id);
        $settings = Utility::settings();
        $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
        $result = date("H:i", strtotime($settings['company_end_time']) - $secs);
        $obj = [
            'date' =>  \Auth::user()->dateFormat($date),
            'app_name' => env('APP_NAME'),
            'employee_name' => $employees->name,
            'address' => !empty($employees->address) ? $employees->address : '',
            'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
            'start_date' => !empty($employees->company_doj) ? $employees->company_doj : '',
            'branch' => !empty($employees->Branch->name) ? $employees->Branch->name : '',
            'start_time' => !empty($settings['company_start_time']) ? $settings['company_start_time'] : '',
            'end_time' => !empty($settings['company_end_time']) ? $settings['company_end_time'] : '',
            'total_hours' => $result,
        ];

        $joiningletter->content = JoiningLetter::replaceVariable($joiningletter->content, $obj);
        return view('employee.template.joiningletterpdf', compact('joiningletter', 'employees'));
    }
    public function joiningletterDoc($id)
    {
        $users = \Auth::user();

        $currantLang = $users->currentLanguage();
        $joiningletter = JoiningLetter::where(['lang' =>   $currantLang, 'created_by' => \Auth::user()->creatorId()])->first();
        $date = date('Y-m-d');
        $employees = Employee::find($id);
        $settings = Utility::settings();
        $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
        $result = date("H:i", strtotime($settings['company_end_time']) - $secs);



        $obj = [
            'date' =>  \Auth::user()->dateFormat($date),

            'app_name' => env('APP_NAME'),
            'employee_name' => $employees->name,
            'address' => !empty($employees->address) ? $employees->address : '',
            'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
            'start_date' => !empty($employees->company_doj) ? $employees->company_doj : '',
            'branch' => !empty($employees->Branch->name) ? $employees->Branch->name : '',
            'start_time' => !empty($settings['company_start_time']) ? $settings['company_start_time'] : '',
            'end_time' => !empty($settings['company_end_time']) ? $settings['company_end_time'] : '',
            'total_hours' => $result,
            //

        ];
        // dd($obj);
        $joiningletter->content = JoiningLetter::replaceVariable($joiningletter->content, $obj);
        return view('employee.template.joiningletterdocx', compact('joiningletter', 'employees'));
    }
    public function ExpCertificatePdf($id)
    {
        $currantLang = \Cookie::get('LANGUAGE');
        if (!isset($currantLang)) {
            $currantLang = 'en';
        }
        $termination = Termination::where('employee_id', $id)->first();
        $experience_certificate = ExperienceCertificate::where(['lang' =>   $currantLang, 'created_by' => \Auth::user()->creatorId()])->first();
        $date = date('Y-m-d');
        $employees = Employee::find($id);
        // dd($employees->salaryType->name);
        $settings = Utility::settings();
        $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
        $result = date("H:i", strtotime($settings['company_end_time']) - $secs);
        $date1 = date_create($employees->company_doj);
        $date2 = date_create($employees->termination_date);
        $diff  = date_diff($date1, $date2);
        $duration = $diff->format("%a days");

        if (!empty($termination->termination_date)) {

            $obj = [
                'date' =>  \Auth::user()->dateFormat($date),
                'app_name' => env('APP_NAME'),
                'employee_name' => $employees->name,
                'payroll' => !empty($employees->salaryType->name) ? $employees->salaryType->name : '',
                'duration' => $duration,
                'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',

            ];
        } else {
            return redirect()->back()->with('error', __('Termination date is required.'));
        }


        $experience_certificate->content = ExperienceCertificate::replaceVariable($experience_certificate->content, $obj);
        return view('employee.template.ExpCertificatepdf', compact('experience_certificate', 'employees'));
    }
    public function ExpCertificateDoc($id)
    {
        $currantLang = \Cookie::get('LANGUAGE');
        if (!isset($currantLang)) {
            $currantLang = 'en';
        }
        $termination = Termination::where('employee_id', $id)->first();
        $experience_certificate = ExperienceCertificate::where(['lang' =>   $currantLang, 'created_by' => \Auth::user()->creatorId()])->first();
        $date = date('Y-m-d');
        $employees = Employee::find($id);
        $settings = Utility::settings();
        $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
        $result = date("H:i", strtotime($settings['company_end_time']) - $secs);
        $date1 = date_create($employees->company_doj);
        $date2 = date_create($employees->termination_date);
        $diff  = date_diff($date1, $date2);
        $duration = $diff->format("%a days");
        if (!empty($termination->termination_date)) {
            $obj = [
                'date' =>  \Auth::user()->dateFormat($date),
                'app_name' => env('APP_NAME'),
                'employee_name' => $employees->name,
                'payroll' => !empty($employees->salaryType->name) ? $employees->salaryType->name : '',
                'duration' => $duration,
                'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',

            ];
        } else {
            return redirect()->back()->with('error', __('Termination date is required.'));
        }

        $experience_certificate->content = ExperienceCertificate::replaceVariable($experience_certificate->content, $obj);
        return view('employee.template.ExpCertificatedocx', compact('experience_certificate', 'employees'));
    }
    public function NocPdf($id)
    {
        $users = \Auth::user();

        $currantLang = $users->currentLanguage();
        $noc_certificate = NOC::where(['lang' =>   $currantLang, 'created_by' => \Auth::user()->creatorId()])->first();
        $date = date('Y-m-d');
        $employees = Employee::find($id);
        $settings = Utility::settings();
        $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
        $result = date("H:i", strtotime($settings['company_end_time']) - $secs);


        $obj = [
            'date' =>  \Auth::user()->dateFormat($date),
            'employee_name' => $employees->name,
            'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
            'app_name' => env('APP_NAME'),
        ];

        $noc_certificate->content = NOC::replaceVariable($noc_certificate->content, $obj);
        return view('employee.template.Nocpdf', compact('noc_certificate', 'employees'));
    }
    public function NocDoc($id)
    {
        $users = \Auth::user();

        $currantLang = $users->currentLanguage();
        $noc_certificate = NOC::where(['lang' =>   $currantLang, 'created_by' => \Auth::user()->creatorId()])->first();
        $date = date('Y-m-d');
        $employees = Employee::find($id);
        $settings = Utility::settings();
        $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
        $result = date("H:i", strtotime($settings['company_end_time']) - $secs);


        $obj = [
            'date' =>  \Auth::user()->dateFormat($date),
            'employee_name' => $employees->name,
            'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
            'app_name' => env('APP_NAME'),
        ];

        $noc_certificate->content = NOC::replaceVariable($noc_certificate->content, $obj);
        return view('employee.template.Nocdocx', compact('noc_certificate', 'employees'));
    }


    public function downloadSample()
    {
        $data = Excel::download(new DownloadEmployeeSample(), 'DownloadEmployeeSample.csv');
        if (ob_get_length() > 0) {
            ob_end_clean();
        }

        return $data;
    }
}
