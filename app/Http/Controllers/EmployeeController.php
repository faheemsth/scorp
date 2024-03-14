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

        if (\Auth::user()->can('manage employee')) {
            $pagination = getPaginationDetail();
            $start = $pagination['start'];
            $limit = $pagination['num_results_on_page'];

            $employee_query = Employee::query()->join('users as u', 'employees.user_id', '=', 'u.id');

            if (\Auth::user()->type == 'company') {
                $employee_query->whereIn('u.brand_id', \Auth::user()->id);
            } elseif (\Auth::user()->can('level 1')) {
                // No filtering needed for level 1 users
            } elseif (\Auth::user()->can('level 2')) {
                $companies = FiltersBrands();
                $brand_ids = array_keys($companies);
                $employee_query->whereIn('u.brand_id', $brand_ids);
            } elseif (\Auth::user()->can('level 3')) {
                $employee_query->whereIn('u.region_id', \Auth::user()->region_id);
            } elseif (\Auth::user()->can('level 4')) {
                $employee_query->whereIn('u.branch_id', \Auth::user()->branch_id);
            } else {
                $employee_query->whereIn('u.id', \Auth::user()->id);
            }

            $total_records = $employee_query->count();
            $employees = $employee_query->skip($start)->take($limit)->get();
            $filters = BrandsRegionsBranches();
            $userRegionBranch = UserRegionBranch();

            return view('employee.index', compact('employees', 'total_records', 'filters', 'userRegionBranch'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create employee')) {
            $company_settings = Utility::settings();
            $documents        = Document::where('created_by', \Auth::user()->creatorId())->get();
            $branches         = Branch::get()->pluck('name', 'id');
            //$branches->prepend(__('Select Branch'), '');

            $departments      = Department::get()->pluck('name', 'id');
            $departments->prepend(__('Select Department'), '');

            $designations     = Designation::get()->pluck('name', 'id');
            $employees        = User::where('created_by', \Auth::user()->creatorId())->get();
            $employeesId      = \Auth::user()->employeeIdFormat($this->employeeNumber());

            return view('employee.create', compact('employees', 'employeesId', 'departments', 'designations', 'documents', 'branches', 'company_settings'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {


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
                    'department_id' => 'required',
                    'designation_id' => 'required',
                    //                                   'document.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf,doc,zip|max:20480',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->withInput()->with('error', $messages->first());
            }

            $objUser        = User::find(\Auth::user()->id);
            //$total_employee = $objUser->countEmployees();
            $total_employee = Employee::where(['created_by' => \Auth::user()->id])->count();
            $plan           = Plan::find($objUser->plan);

            // if($total_employee < $plan->max_employees || $plan->max_employees == -1)
            // {
            $user = User::create(
                [
                    'name' => $request['name'],
                    'email' => $request['email'],
                    // 'gender'=>$request['gender'],
                    'password' => Hash::make($request['password']),
                    'type' => 'employee',
                    'lang' => 'en',
                    'created_by' => \Auth::user()->id,
                ]
            );
            $user->save();
            $user->assignRole('Employee');
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Your employee limit is over, Please upgrade plan.'));
            // }


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
                    'password' => Hash::make($request['password']),
                    'employee_id' => $this->employeeNumber(),
                    'branch_id' => $request['branch_id'],
                    'department_id' => $request['department_id'],
                    'designation_id' => $request['designation_id'],
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
        $id = Crypt::decrypt($id);
        if (\Auth::user()->can('edit employee')) {
            $documents    = Document::where('created_by', \Auth::user()->creatorId())->get();
            $branches     = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branches->prepend('Select Branch', '');
            $departments  = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $designations = Designation::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employee     = Employee::find($id);
            //            $employeesId  = \Auth::user()->employeeIdFormat($employee->employee_id);
            $employeesId  = \Auth::user()->employeeIdFormat(!empty($employee) ? $employee->employee_id : '');

            $departmentData  = Department::where('created_by', \Auth::user()->creatorId())->where('branch_id', $employee->branch_id)->get()->pluck('name', 'id');


            return view('employee.edit', compact('employee', 'employeesId', 'branches', 'departments', 'designations', 'documents', 'departmentData'));
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
            $employee = Employee::findOrFail($id);
            $input    = $request->all();
            $employee->fill($input)->save();
            $employee = Employee::find($id);
            $user = User::where('id', $employee->user_id)->first();
            if (!empty($user)) {
                $user->name = $employee->name;
                $user->email = $employee->email;
                $user->save();
            }
            if ($request->salary) {
                return redirect()->route('setsalary.index')->with('success', 'Employee successfully updated.');
            }

            if (\Auth::user()->type != 'employee') {
                return redirect()->route('employee.index')->with('success', 'Employee successfully updated.');
            } else {
                return redirect()->route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id))->with('success', 'Employee successfully updated.');
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {

        if (Auth::user()->can('delete employee')) {
            $employee      = Employee::findOrFail($id);
            $user          = User::where('id', '=', $employee->user_id)->first();
            $emp_documents = EmployeeDocument::where('employee_id', $employee->employee_id)->get();
            $employee->delete();
            $user->delete();
            $dir = storage_path('uploads/document/');
            foreach ($emp_documents as $emp_document) {
                $emp_document->delete();
                if (!empty($emp_document->document_value)) {
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
            $employee = Employee::join('users as u', 'u.id', '=', 'employees.user_id')->where('u.id', $id)->first();
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
