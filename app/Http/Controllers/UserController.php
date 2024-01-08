<?php

namespace App\Http\Controllers;

use App\Mail\AutoGeneratedPassword;
use App\Models\CustomField;
use App\Models\Employee;
use App\Models\ExperienceCertificate;
use App\Models\GenerateOfferLetter;
use App\Models\JoiningLetter;
use App\Models\NOC;
use App\Models\User;
use App\Models\UserCompany;
use Auth;
use File;
use App\Models\Utility;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserToDo;
use App\Models\CompanyPermission;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Session;
use Spatie\Permission\Models\Role;
use App\Models\Branch;



class UserController extends Controller
{

    public function index()
    {
        $user = \Auth::user();

        $num_results_on_page = 10;

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = ($page - 1) * $num_results_on_page;
        } else {
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = 0;
        }

        if (\Auth::user()->can('manage user')) {
            if (\Auth::user()->type == 'super admin') {
                $users = User::where('created_by', '=', $user->creatorId())
                    ->where(function ($query) {
                        $query->where('type', '=', 'company')
                            ->orWhere('type', '=', 'team');
                    });

                    if (isset($_GET['Brand']) && !empty($_GET['Brand'])) {
                        $brandId = intval($_GET['Brand']); // Assuming it's an integer, adjust accordingly
                        $users->where('id', $brandId);
                        }

                        if (isset($_GET['Director']) && !empty($_GET['Director'])) {
                        $directorId = intval($_GET['Director']); // Assuming it's an integer, adjust accordingly
                        $users->where('project_director_id', $directorId);
                        }

                $users = $users->skip($start)->take($num_results_on_page)->paginate($num_results_on_page);
            } else {
                $users = User::where('created_by', '=', $user->creatorId())->where('type', '!=', 'client')
                    ->skip($start)->take($num_results_on_page)->paginate($num_results_on_page);
            }
            $total_records = $users->total();

            $projectDirectors = allUsers();
            // return view('user.index-Old')->with('users', $users);
            $Brands = User::where('type','company')->pluck('name', 'id')->toArray();
            $ProjectDirector = User::where('type', 'Project Director')->pluck('name', 'id')->toArray();
            return view('user.index', compact('total_records', 'projectDirectors','Brands','ProjectDirector'))->with('users', $users);
        } else {
            return redirect()->back();
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create user')) {
            $autoGeneratedPassword = Str::random(8);
            $projectDirectors = User::where('type', 'Project Director')->pluck('name', 'id')->toArray();
            $projectDirectors = ['0' => 'Select Project Director'] + $projectDirectors;
            return view('user.create', compact('autoGeneratedPassword', 'projectDirectors'));
        } else {
            return redirect()->back();
        }
    }


    function generateUniqueEmail()
    {
        do {
            // Generate a unique email
            $uniqueEmail = Str::random(10) . '@example.com';

            // Check if the email already exists in the user table
            $existingUser = User::where('email', $uniqueEmail)->first();

        } while ($existingUser);

        return $uniqueEmail;
    }

    public function store(Request $request)
    {
        //dd($request->input());
        if (\Auth::user()->can('create user')) {

            $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'domain_link' => 'required',
                    'website_link' => 'required',
                    'drive_link' => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }


            if (\Auth::user()->type == 'super admin') {
                $psw = '1234';

                $user               = new User();
                $user['name']       = $request->name;
                $user['email']      = $this->generateUniqueEmail();
                $user['password']   = Hash::make('1234');
                $user['type']       =  $request->role;
                $user['default_pipeline'] = 1;
                $user['plan'] = 1;
                $user['lang']       = !empty($default_language) ? $default_language->value : '';
               $user['created_by'] = \Auth::user()->creatorId();
               $user['plan']       = Plan::first()->id;
               $user['domain_link'] = $request->domain_link;
               $user['website_link'] = $request->website_link;
               $user['drive_link'] = $request->drive_link;
               $user['project_director_id'] = $request->project_director;

                $user->save();

                $role_r = Role::findByName($request->role);
                $user->assignRole($role_r);
                //                $user->userDefaultData();
                $user->userDefaultDataRegister($user->id);
                $user->userWarehouseRegister($user->id);

                //default bank account for new company
                $user->userDefaultBankAccount($user->id);

                Utility::chartOfAccountTypeData($user->id);
                Utility::chartOfAccountData($user);
                // default chart of account for new company
                Utility::chartOfAccountData1($user->id);

                Utility::pipeline_lead_deal_Stage($user->id);
                Utility::project_task_stages($user->id);
                Utility::labels($user->id);
                Utility::sources($user->id);
                Utility::jobStage($user->id);
                GenerateOfferLetter::defaultOfferLetterRegister($user->id);
                ExperienceCertificate::defaultExpCertificatRegister($user->id);
                JoiningLetter::defaultJoiningLetterRegister($user->id);
                NOC::defaultNocCertificateRegister($user->id);
            } else {
                $objUser    = \Auth::user()->creatorId();
                $objUser = User::find($objUser);
                $user = User::find(\Auth::user()->created_by);
                $total_user = $objUser->countUsers();
                $plan       = Plan::find($objUser->plan);


                if ($total_user < $plan->max_users || $plan->max_users == -1) {
                    $role_r                = Role::findById($request->role);
                    $psw                   = $request->password;
                    $request['password']   = Hash::make($request->password);
                    $request['type']       = $role_r->name;
                    $request['lang']       = !empty($default_language) ? $default_language->value : 'en';
                    $request['created_by'] = \Auth::user()->creatorId();
                    $user = User::create($request->all());
                    $user->branch_id = $request->branch_id;
                    $user['date_of_birth'] = $request->dob;
                    $user['phone'] = $request->phone;

                    $user->assignRole($role_r);
                    if ($request['type'] != 'client')
                        \App\Models\Utility::employeeDetails($user->id, \Auth::user()->creatorId());
                } else {
                    return redirect()->back()->with('error', __('Your user limit is over, Please upgrade plan.'));
                }
            }

            // Send Email
            // $setings = Utility::settings();
            // if ($setings['new_user'] == 1) {
            //     $user->password = $psw;
            //     $user->type = $role_r->name;

            //     $userArr = [
            //         'email' => $user->email,
            //         'password' => $user->password,
            //     ];
            //     $resp = Utility::sendEmailTemplate('new_user', [$user->id => $user->email], $userArr);


            //     return redirect()->route('users.index')->with('success', __('User successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            // }
            return redirect()->route('users.index')->with('success', __('User successfully created.'));
        } else {
            return redirect()->back();
        }
    }

    public function edit($id)
    {

        if (\Auth::user()->can('edit user')) {
            $user              = User::findOrFail($id);
            $projectDirectors = User::where('type', 'Project Director')->pluck('name', 'id')->toArray();
            $projectDirectors = ['0' => 'Select Project Director'] + $projectDirectors;
            return view('user.edit', compact('user', 'projectDirectors'));
        } else {
            return redirect()->back();
        }
    }


    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit user')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'domain_link' => 'required',
                    'website_link' => 'required',
                    'drive_link' => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }


            if (\Auth::user()->type == 'super admin') {
                $user = User::findOrFail($id);

                //                $role = Role::findById($request->role);
                $role = Role::findByName('company');
                $input = $request->all();
                $input['type'] = $role->name;
                $user->fill($input)->save();
                $user->name = $request->name;
                $user->domain_link =  $request->domain_link;
                $user->website_link = $request->website_link;
                $user->drive_link = $request->drive_link;
                $user->project_director_id = $request->project_director;
                $user->update();

                $roles[] = $role->id;
                $user->roles()->sync($roles);

                return redirect()->route('users.index')->with(
                    'success',
                    'User successfully updated.'
                );
            } else {
                $user = User::findOrFail($id);

                $role          = Role::findById($request->role);
                $input         = $request->all();
                $input['type'] = $role->name;
                $user->fill($input)->save();
                $user->name = $request->name;
                $user->domain_link =  $request->domain_link;
                $user->website_link = $request->website_link;
                $user->drive_link = $request->drive_link;
                $user->project_director_id = $request->project_director;
                $user->update();


               // Utility::employeeDetailsUpdate($user->id, \Auth::user()->creatorId());
               // CustomField::saveData($user, $request->customField);

                $roles[] = $request->role;
                $user->roles()->sync($roles);

                return redirect()->route('users.index')->with(
                    'success',
                    'User successfully updated.'
                );
            }
        } else {
            return redirect()->back();
        }
    }


    public function destroy($id)
    {

        if (\Auth::user()->can('delete user')) {
            $user = User::find($id);
            if ($user) {
                $user->delete();

                // if (\Auth::user()->type == 'super admin') {
                //     if ($user->delete_status == 0) {
                //         $user->delete_status = 1;
                //     } else {
                //         $user->delete_status = 0;
                //     }
                //     $user->save();
                // }
                // if (\Auth::user()->type == 'company') {
                //     $employee = Employee::where(['user_id' => $user->id])->delete();
                //     if ($employee) {
                //         $delete_user = User::where(['id' => $user->id])->delete();
                //         if ($delete_user) {
                //             return redirect()->route('users.index')->with('success', __('User successfully deleted .'));
                //         } else {
                //             return redirect()->back()->with('error', __('Something is wrong.'));
                //         }
                //     } else {
                //         return redirect()->back()->with('error', __('Something is wrong.'));
                //     }
                // }

                return redirect()->route('users.index')->with('success', __('User successfully deleted .'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back();
        }
    }

    public function profile()
    {
        $userDetail              = \Auth::user();
        $userDetail->customField = CustomField::getData($userDetail, 'user');
        $customFields            = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'user')->get();

        return view('user.profile', compact('userDetail', 'customFields'));
    }

    public function editprofile(Request $request)
    {
        $userDetail = \Auth::user();
        $user       = User::findOrFail($userDetail['id']);
        $this->validate(
            $request,
            [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users,email,' . $userDetail['id'],
            ]
        );
        if ($request->hasFile('profile')) {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $settings = Utility::getStorageSetting();
            if ($settings['storage_setting'] == 'local') {
                $dir        = '/uploads/avatar/';
               // $dir        = 'storage/';
            } else {
                $dir        = 'uploads/avatar/';
            }

            $image_path = $dir . $userDetail['avatar'];

            if (File::exists($image_path)) {
                File::delete($image_path);
            }


            $url = '';
            $path = Utility::upload_file($request, 'profile', $fileNameToStore, $dir, []);

            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                return redirect()->route('profile', \Auth::user()->id)->with('error', __($path['msg']));
            }

            //            $dir        = storage_path('uploads/avatar/');
            //            $image_path = $dir . $userDetail['avatar'];
            //
            //            if(File::exists($image_path))
            //            {
            //                File::delete($image_path);
            //            }
            //
            //            if(!file_exists($dir))
            //            {
            //                mkdir($dir, 0777, true);
            //            }
            //            $path = $request->file('profile')->storeAs('uploads/avatar/', $fileNameToStore);

        }

        if (!empty($request->profile)) {
            $user['avatar'] = $fileNameToStore;
        }
        $user['name']  = $request['name'];
        $user['email'] = $request['email'];
        $user->save();
        CustomField::saveData($user, $request->customField);

        return redirect()->route('crm.dashboard')->with(
            'success',
            'Profile successfully updated.'
        );
    }

    public function updatePassword(Request $request)
    {

        if (Auth::Check()) {
            $request->validate(
                [
                    'old_password' => 'required',
                    'password' => 'required|min:6',
                    'password_confirmation' => 'required|same:password',
                ]
            );
            $objUser          = Auth::user();
            $request_data     = $request->All();
            $current_password = $objUser->password;
            if (Hash::check($request_data['old_password'], $current_password)) {
                $user_id            = Auth::User()->id;
                $obj_user           = User::find($user_id);
                $obj_user->password = Hash::make($request_data['password']);;
                $obj_user->save();

                return redirect()->route('profile', $objUser->id)->with('success', __('Password successfully updated.'));
            } else {
                return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
            }
        } else {
            return redirect()->route('profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
        }
    }
    // User To do module
    public function todo_store(Request $request)
    {
        $request->validate(
            ['title' => 'required|max:120']
        );

        $post            = $request->all();
        $post['user_id'] = Auth::user()->id;
        $todo            = UserToDo::create($post);


        $todo->updateUrl = route(
            'todo.update',
            [
                $todo->id,
            ]
        );
        $todo->deleteUrl = route(
            'todo.destroy',
            [
                $todo->id,
            ]
        );

        return $todo->toJson();
    }

    public function todo_update($todo_id)
    {
        $user_todo = UserToDo::find($todo_id);
        if ($user_todo->is_complete == 0) {
            $user_todo->is_complete = 1;
        } else {
            $user_todo->is_complete = 0;
        }
        $user_todo->save();
        return $user_todo->toJson();
    }

    public function todo_destroy($id)
    {
        $todo = UserToDo::find($id);
        $todo->delete();

        return true;
    }

    // change mode 'dark or light'
    public function changeMode()
    {
        $usr = \Auth::user();
        if ($usr->mode == 'light') {
            $usr->mode      = 'dark';
            $usr->dark_mode = 1;
        } else {
            $usr->mode      = 'light';
            $usr->dark_mode = 0;
        }
        $usr->save();

        return redirect()->back();
    }

    public function upgradePlan($user_id)
    {
        $user = User::find($user_id);
        $plans = Plan::get();
        return view('user.plan', compact('user', 'plans'));
    }
    public function activePlan($user_id, $plan_id)
    {

        $user       = User::find($user_id);
        $assignPlan = $user->assignPlan($plan_id);
        $plan       = Plan::find($plan_id);
        if ($assignPlan['is_success'] == true && !empty($plan)) {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            Order::create(
                [
                    'order_id' => $orderID,
                    'name' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $plan->price,
                    'price_currency' => isset(\Auth::user()->planPrice()['currency']) ? \Auth::user()->planPrice()['currency'] : '',
                    'txn_id' => '',
                    'payment_status' => 'succeeded',
                    'receipt' => null,
                    'user_id' => $user->id,
                ]
            );

            return redirect()->back()->with('success', 'Plan successfully upgraded.');
        } else {
            return redirect()->back()->with('error', 'Plan fail to upgrade.');
        }
    }

    public function userPassword($id)
    {
        $eId        = \Crypt::decrypt($id);
        $user = User::find($eId);

        return view('user.reset', compact('user'));
    }

    public function userPasswordReset(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'password' => 'required|confirmed|same:password_confirmation',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        $user                 = User::where('id', $id)->first();
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return redirect()->route('users.index')->with(
            'success',
            'User Password successfully updated.'
        );
    }


    public function userDetail($id)
    {
        $user = User::findOrFail($id);
        $userArr = User::get()->pluck('name', 'id')->toArray();
        $html = view('user.userDetail', compact('user', 'userArr'))->render();
        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function employees()
    {

        $user = \Auth::user();
        $num_results_on_page = 50;

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = ($page - 1) * $num_results_on_page;
        } else {
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = 0;
        }

        if (\Auth::user()->can('manage employee')) {
            $excludedTypes = ['super admin', 'company', 'team', 'client'];
            if (\Auth::user()->type == 'super admin') {
                $usersQuery = User::whereNotIn('type', $excludedTypes);

                if (!empty($_GET['brand'])) {
                    $usersQuery->where('brand_id', $_GET['brand']);
                }
                if (!empty($_GET['Region'])) {
                    $usersQuery->where('region_id', $_GET['Region']);
                }

                if (!empty($_GET['Branch'])) {
                    $usersQuery->where('branch_id', $_GET['Branch']);
                }

                if (!empty($_GET['Name'])) {
                    $usersQuery->where('name', 'like', '%' . $_GET['Name'] . '%');
                }

                if (!empty($_GET['Designation'])) {
                    $usersQuery->where('type', 'like', '%' . $_GET['Designation'] . '%');
                }


                if (!empty($_GET['phone'])) {
                    $usersQuery->where('phone', 'like', '%' . $_GET['phone'] . '%');
                }

                $users = $usersQuery->skip($start)->take($num_results_on_page)->paginate($num_results_on_page);
            } else {
                $users = User::where('created_by', '=', $user->creatorId())->whereNotIn('type', $excludedTypes)->skip($start)->take($num_results_on_page)->paginate($num_results_on_page);
            }
            $brands = User::whereNotIn('type', $excludedTypes)->get();
            $brandss = User::where('type', 'company')->pluck('name', 'id')->toArray();
            $Regions = Region::pluck('name', 'id')->toArray();
            $Branchs = Branch::pluck('name', 'id')->toArray();
            $Designations = Role::where('name', '!=', 'super admin')->pluck('name', 'id')->toArray();
            $total_records = $users->total();
            return view('user.employee', compact('total_records', 'users', 'brands','Regions','brandss','Branchs','Designations'));
        } else {
            return redirect()->back();
        }
    }



    public function employeeCreate()
    {
        $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'user')->get();
        $user  = \Auth::user();

        if (\Auth::user()->type == 'super admin') {
            $branches = \App\Models\Branch::get()->pluck('name', 'id');

            $companies = FiltersBrands();
            $companies = [0 => 'Select Brand'] + $companies;



        } else {
            $branches = \App\Models\Branch::get()->pluck('name', 'id');
            $branches = [0 => 'Select Branches'] + $branches->toArray();

            $companies = FiltersBrands();
            $companies = [0 => 'Select Brand'] + $companies;

            $regions = Region::pluck('name', 'id')->toArray();



        }



        $excludedTypes = ['super admin', 'company', 'team', 'client'];
        $roles = Role::whereNotIn('name', $excludedTypes)->get()->unique('name')->pluck('name', 'name');
        $Region=Region::get()->pluck('name', 'id')->toArray();

        if (\Auth::user()->can('create employee')) {
            $autoGeneratedPassword = Str::random(10);
            return view('user.employeeCreate', compact('Region','roles', 'customFields', 'branches', 'autoGeneratedPassword', 'companies'));
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Permission Denied'
               ]);
        }
    }

    public function employeeStore(Request $request)
    {

        if (\Auth::user()->can('create employee')) {
            $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
           // if (\Auth::user()->type == 'super admin') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users',
                        'password' => 'required|min:6',
                        'dob' => 'required',
                        'phone' => 'required'
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $user               = new User();
                $user['name']       = $request->name;
                $user['email']      = $request->email;
                $psw                = $request->password;
                $user['password']   = Hash::make($request->password);
                $user['type']       =  $request->role;
                $user['branch_id'] = $request->branch_id;
                $user['region_id'] = $request->region_id;
                $user['brand_id'] = isset($request->companies) ? $request->companies : \Auth::user()->brand_id;
                $user['default_pipeline'] = 1;
                $user['plan'] = 1;
                $user['lang']       = !empty($default_language) ? $default_language->value : '';

                $user['created_by'] = $request->companies;
                $user['plan']       = Plan::first()->id;
                $user['date_of_birth'] = $request->dob;
                $user['phone'] = $request->phone;

                $user->save();

                $role_r = Role::findByName($request->role);
                $user->assignRole($role_r);
                //                $user->userDefaultData();
                $user->userDefaultDataRegister($user->id);
                $user->userWarehouseRegister($user->id);

                //default bank account for new company
                $user->userDefaultBankAccount($user->id);

                Utility::chartOfAccountTypeData($user->id);
                Utility::chartOfAccountData($user);
                // default chart of account for new company
                Utility::chartOfAccountData1($user->id);

                Utility::pipeline_lead_deal_Stage($user->id);
                Utility::project_task_stages($user->id);
                Utility::labels($user->id);
                Utility::sources($user->id);
                Utility::jobStage($user->id);
                GenerateOfferLetter::defaultOfferLetterRegister($user->id);
                ExperienceCertificate::defaultExpCertificatRegister($user->id);
                JoiningLetter::defaultJoiningLetterRegister($user->id);
                NOC::defaultNocCertificateRegister($user->id);
           /*
            } else {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users',
                        'password' => 'required|min:6',
                        'role' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }


                $objUser    = \Auth::user()->creatorId();
                $objUser = User::find($objUser);
                $user = User::find(\Auth::user()->created_by);
                $total_user = $objUser->countUsers();
                $plan       = Plan::find($objUser->plan);


                if ($total_user < $plan->max_users || $plan->max_users == -1) {
                    $role_r                = Role::findById($request->role);
                    $psw                   = $request->password;
                    $request['password']   = Hash::make($request->password);
                    $request['type']       = $role_r->name;
                    $request['lang']       = !empty($default_language) ? $default_language->value : 'en';
                    $request['created_by'] = \Auth::user()->creatorId();
                    $user = User::create($request->all());
                    $user->branch_id = $request->branch_id;
                    $user['date_of_birth'] = $request->dob;
                    $user['phone'] = $request->phone;

                    $user->assignRole($role_r);
                    if ($request['type'] != 'client')
                        \App\Models\Utility::employeeDetails($user->id, \Auth::user()->creatorId());
                } else {
                    return redirect()->back()->with('error', __('Your user limit is over, Please upgrade plan.'));
                }
            }
            */
            // Send Email
            $setings = Utility::settings();


            if ($setings['new_user'] == 1) {

                $user->password = $psw;
                $user->type = $role_r->name;

                $userArr = [
                    'email' => $user->email,
                    'password' => $user->password,
                ];
                $resp = Utility::sendEmailTemplate('new_user', [$user->id => $user->email], $userArr);


                return redirect()->route('user.employees')->with('success', __('User successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            return redirect()->route('user.employees')->with('success', __('User successfully created.'));
        } else {
            return redirect()->back();
        }
    }

    public function employeeEdit($id)
    {

        $user  = \Auth::user();
        if (\Auth::user()->type == 'super admin') {
            $branches = \App\Models\Branch::get()->pluck('name', 'id');
            $companies = User::where('type', 'company')->get()->pluck('name', 'id')->toArray();
            //  $roles = Role::where('name', 'company')->orwhere('name', 'team')->get()->pluck('name', 'name');
        } else {
            $branches = \App\Models\Branch::where(['created_by' => $user->id])->get()->pluck('name', 'id');
            $branches = [0 => 'Select Branches'] + $branches->toArray();
            // $roles = Role::whereNotIn('name', ['client', 'super admin', 'company', 'team'])->get()->pluck('name', 'id');

            $permittedCompanies = CompanyPermission::where('user_id', \Auth::user()->id)->pluck('permitted_company_id')->toArray();
            $companies = User::whereIn('id', $permittedCompanies)
               // ->orWhere('id', \Auth::user()->brand_id)
                ->get()
                ->pluck('name', 'id')
                ->toArray();
        }


        if (\Auth::user()->can('edit employee')) {
            $user              = User::findOrFail($id);
            $user->customField = CustomField::getData($user, 'user');
            $customFields      = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'user')->get();


            $excludedTypes = ['super admin', 'company', 'team', 'client'];
            $roles = Role::whereNotIn('name', $excludedTypes)->get()->unique('name')->pluck('name', 'id');
            $Region=Region::get()->pluck('name', 'id')->toArray();
        // $autoGeneratedPassword = Str::random(10);

            return view('user.employeeEdit', compact('user', 'roles', 'customFields', 'branches', 'companies','Region'));
        } else {
            return redirect()->back();
        }
    }

    public function employeeUpdate(Request $request, $id)
    {

        if (\Auth::user()->can('edit employee')) {
            if (\Auth::user()->type == 'super admin') {
                $user = User::findOrFail($id);
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users,email,' . $id,
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                $role = Role::findByName($request->role);
                //$role = Role::findByName('company');
                $input = $request->all();
                $input['type'] = $role->name;
                $user->fill($input)->save();


                CustomField::saveData($user, $request->customField);

                $roles[] = $role->id;
                $user->roles()->sync($roles);

                return redirect()->route('users.index')->with(
                    'success',
                    'User successfully updated.'
                );
            } else {
                $user = User::findOrFail($id);
                $this->validate(
                    $request,
                    [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users,email,' . $id,
                        'role' => 'required',
                    ]
                );


                $role          =  Role::findById($request->role);
                $input         = $request->all();
                $input['type'] = $role->name;
                $user->fill($input)->save();
                $user->branch_id = $request->branch_id;
                $user->date_of_birth =  $request->dob;
                $user->phone = $request->phone;
                //$user->branch_id = $request->branch_id;
                $user['region_id'] = $request->region_id;
                $user->type = $role->name;
                $user->update();
                Utility::employeeDetailsUpdate($user->id, \Auth::user()->creatorId());
                CustomField::saveData($user, $request->customField);

                $roles[] = $request->role;
                $user->roles()->sync($roles);

                return redirect()->route('user.employees')->with(
                    'success',
                    'User successfully updated.'
                );
            }
        } else {
            return redirect()->back();
        }
    }

    public function employeeShow($id)
    {
        $employee = User::findOrFail($id);
        $Region=Region::get()->pluck('name', 'id')->toArray();
        $html = view('user.employeeDetail', compact('employee','Region'))->render();
        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }
}
