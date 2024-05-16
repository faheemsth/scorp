<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;
use App\Models\UserEmailTemplate;
use App\Models\Utility;
use Illuminate\Http\Request;
use App\Models\Pipeline;
use App\Models\Region;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usr = \Auth::user();
        if($usr->type == 'super admin' || $usr->type == 'company')
        {
            $EmailTemplates = EmailTemplate::all();
            return view('settings.company', compact('EmailTemplates'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

//        if(\Auth::user()->can('Create Email Template'))
//        {
            return view('email_templates.create');
//        }
//        else
//        {
//            return redirect()->back()->with('error', __('Permission denied.'));
//        }
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
//        return redirect()->back()->with('error', 'Permission denied.');

        $usr = \Auth::user();

//        if(\Auth::user()->can('Create Email Template'))
//        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $EmailTemplate             = new EmailTemplate();
            $EmailTemplate->name       = $request->name;
            $EmailTemplate->created_by = $usr->id;
            $EmailTemplate->save();

            return redirect()->route('email_template.index')->with('success', __('Email Template successfully created.'));
//        }
//        else
//        {
//            return redirect()->back()->with('error', __('Permission denied.'));
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\EmailTemplate $emailTemplate
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return redirect()->back()->with('error', 'Permission denied.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\EmailTemplate $emailTemplate
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        return redirect()->back()->with('error', 'Permission denied.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\EmailTemplate $emailTemplate
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {


//        if(\Auth::user()->can('Edit Email Template'))
//        {
            $validator = \Validator::make(
                $request->all(), [
                                    'from' => 'required',
                                    'subject' => 'required',
                                    'content' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

        $emailTemplate       = EmailTemplate::where('id',$id)->first();
//            dd($emailTemplate);
        $emailTemplate->from = $request->from;

        $emailTemplate->save();

        $emailLangTemplate = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', '=', $request->lang)->first();

        // if record not found then create new record else update it.
        if(empty($emailLangTemplate))
        {
            $emailLangTemplate            = new EmailTemplateLang();
            $emailLangTemplate->parent_id = $id;
            $emailLangTemplate->lang      = $request['lang'];
            $emailLangTemplate->subject   = $request['subject'];
            $emailLangTemplate->content   = $request['content'];
            $emailLangTemplate->save();
        }
        else
        {
            $emailLangTemplate->subject = $request['subject'];
            $emailLangTemplate->content = $request['content'];
            $emailLangTemplate->save();
        }

        return redirect()->route(
            'manage.email.language', [
                $emailTemplate->id,
                $request->lang,
            ]
        )->with('success', __('Email Template successfully updated.'));
//        }
//        else
//        {
//            return redirect()->back()->with('error', __('Permission denied.'));
//        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\EmailTemplate $emailTemplate
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        return redirect()->back()->with('error', 'Permission denied.');
    }

    // Used For View Email Template Language Wise
    public function manageEmailLang($id, $lang = 'en')
    {

        $usr = \Auth::user();
        $leads_query = EmailTemplate::query();
        if (
            $usr->can('view lead') ||
            $usr->can('manage lead') ||
            \Auth::user()->type == 'super admin' ||
            \Auth::user()->type == 'Admin Team'
        ) {
            $companies = FiltersBrands();
            $brand_ids = array_keys($companies);

            $userType = \Auth::user()->type;

            if (in_array($userType, ['super admin', 'Admin Team']) || \Auth::user()->can('level 1')) {
                // No additional conditions, leave $leads_query as it is
            } elseif ($userType === 'company') {
                $leads_query->where('brand_id', \Auth::user()->id);
            } elseif (in_array($userType, ['Project Director', 'Project Manager']) || \Auth::user()->can('level 2')) {
                $leads_query->whereIn('brand_id', $brand_ids);
            } elseif (($userType === 'Region Manager' || \Auth::user()->can('level 3')) && !empty(\Auth::user()->region_id)) {
                $leads_query->where('region_id', \Auth::user()->region_id);
            } elseif (($userType === 'Branch Manager' || in_array($userType, ['Admissions Officer', 'Admissions Manager', 'Marketing Officer'])) || (\Auth::user()->can('level 4') && !empty(\Auth::user()->branch_id))) {
                $leads_query->where('branch_id', \Auth::user()->branch_id);
            } else {
                $leads_query->where('user_id', \Auth::user()->id);
            }

            $leads_query->where('id',$id)->groupBy('id')->orderBy('created_at', 'desc');
        }
        $EmailTemplates = $leads_query->get();


        if($id != 0 && $EmailTemplates->count() < 1)
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }

            $languages         = Utility::languages();
            $emailTemplate     = EmailTemplate::first();
//             $currEmailTempLang = EmailTemplateLang::where('lang', $lang)->first();
            $currEmailTempLang = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', $lang)->first();

            if(!isset($currEmailTempLang) || empty($currEmailTempLang))
            {
//                $currEmailTempLang       = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', 'en')->first();
                $currEmailTempLang = EmailTemplateLang::where('lang', $lang)->first();
                if(empty($currEmailTempLang)){
                    return redirect()->back()->with('error', 'This Email Template Lang Not Exist.');
                }

                $currEmailTempLang->lang = $lang;
            }

                $emailTemplate     = EmailTemplate::where('id', '=', $id)->first();

            $usr = \Auth::user();
            $leads_query = EmailTemplate::query();
            if (
                $usr->can('view lead') ||
                $usr->can('manage lead') ||
                \Auth::user()->type == 'super admin' ||
                \Auth::user()->type == 'Admin Team'
            ) {
                $companies = FiltersBrands();
                $brand_ids = array_keys($companies);

                $userType = \Auth::user()->type;

                if (in_array($userType, ['super admin', 'Admin Team']) || \Auth::user()->can('level 1')) {
                    // No additional conditions, leave $leads_query as it is
                } elseif ($userType === 'company') {
                    $leads_query->where('brand_id', \Auth::user()->id);
                } elseif (in_array($userType, ['Project Director', 'Project Manager']) || \Auth::user()->can('level 2')) {
                    $leads_query->whereIn('brand_id', $brand_ids);
                } elseif (($userType === 'Region Manager' || \Auth::user()->can('level 3')) && !empty(\Auth::user()->region_id)) {
                    $leads_query->where('region_id', \Auth::user()->region_id);
                } elseif (($userType === 'Branch Manager' || in_array($userType, ['Admissions Officer', 'Admissions Manager', 'Marketing Officer'])) || (\Auth::user()->can('level 4') && !empty(\Auth::user()->branch_id))) {
                    $leads_query->where('branch_id', \Auth::user()->branch_id);
                } else {
                    $leads_query->where('user_id', \Auth::user()->id);
                }

                $leads_query->groupBy('id')->orderBy('created_at', 'desc');
            }
            $EmailTemplates = $leads_query->get();
            return view('email_templates.show', compact('emailTemplate', 'languages', 'currEmailTempLang','EmailTemplates'));


    }

    // Used For Store Email Template Language Wise
    public function storeEmailLang(Request $request, $id)
    {
//        dd($request,$id);

//        if(\Auth::user()->can('Edit Email Template Lang'))
//        {
            $validator = \Validator::make(
                $request->all(), [
                                   'subject' => 'required',
                                   'content' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $emailLangTemplate = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', '=', $request->lang)->first();

            // if record not found then create new record else update it.
            if(empty($emailLangTemplate))
            {
                $emailLangTemplate            = new EmailTemplateLang();
                $emailLangTemplate->parent_id = $id;
                $emailLangTemplate->lang      = $request['lang'];
                $emailLangTemplate->subject   = $request['subject'];
                $emailLangTemplate->content   = $request['content'];
                $emailLangTemplate->save();
            }
            else
            {
                $emailLangTemplate->subject = $request['subject'];
                $emailLangTemplate->content = $request['content'];
                $emailLangTemplate->save();
            }

            return redirect()->route(
                'manage.email.language', [
                                           $id,
                                           $request->lang,
                                       ]
            )->with('success', __('Email Template Detail successfully updated.'));
//        }
//        else
//        {
//            return redirect()->back()->with('error', 'Permission denied.');
//        }
    }

    // Used For Update Status Company Wise.
    public function updateStatus(Request $request, $id)
    {


        $usr = \Auth::user();

        if($usr->type == 'super admin' || $usr->type == 'company')
        {

            $user_email = UserEmailTemplate::where('id', '=', $id)->where('user_id', '=', $usr->id)->first();

            if(!empty($user_email))
            {
                if($request->status == 1)
                {
                    $user_email->is_active = 0;
                }
                else
                {
                    $user_email->is_active = 1;
                }
//                dd($user_email->is_active);


                $user_email->save();

                return response()->json(
                    [
                        'is_success' => true,
                        'success' => __('Status successfully updated!'),
                    ], 200
                );
            }
            else
            {
                return response()->json(
                    [
                        'is_success' => false,
                        'success' => __('Permission Denied.'),
                    ], 401
                );
            }
        }
    }

    public function updateEmailContent(Request $request, $id){
        $email = EmailTemplateLang::where('id', $id)->first();
        $email->content = $request->content;
        $email->save();
        return response()->json(
            [
                'is_success' => true,
                'success' => __('Status successfully updated!'),
            ], 200
        );
    }


























    public function email_template_type_list(Request $request)
    {
        $start = 0;
        if (!empty($_GET['perPage'])) {
            $num_results_on_page = $_GET['perPage'];
        } else {
            $num_results_on_page = env("RESULTS_ON_PAGE");
        }
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = ($page - 1) * $num_results_on_page;
        } else {
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
        }

        $pipeline = Pipeline::first();
        $leads_query = EmailTemplate::query();
        $total_records = $leads_query->count();

        $users = allUsers();
        $branches = Branch::get()->pluck('name', 'id')->ToArray();
        $regiones = Region::get()->pluck('name', 'id')->ToArray();

        $users_with_roles = \DB::table('roles')->pluck('name', 'id')->toArray();
        $EmailMarketings = $leads_query->skip($start)->take($num_results_on_page)->get();

        return view('EmailTemplate.list', compact('branches','regiones','pipeline', 'EmailMarketings', 'total_records', 'users', 'users_with_roles'));
    }
    public function email_template_type_create(Request $request)
    {
        $filter = BrandsRegionsBranches();
        $users = allUsers();
        $companies = $filter['brands'];
        $regions = $filter['regions'];
        $branches = $filter['branches'];
        $employees = $filter['employees'];
        $users_with_roles = \DB::table('roles')->get();
        return view('EmailTemplate.create', compact('users', 'companies', 'branches', 'regions', 'employees', 'users_with_roles'));
    }
    public function email_template_type_save(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'brand_id' => 'required|numeric|min:1',
            'region_id' => 'required|numeric|min:1',
            'lead_branch' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }


        $emailMarketing = new EmailTemplate;
        $emailMarketing->name = $request->name;
        $emailMarketing->brand_id = $request->brand_id;
        $emailMarketing->region_id = $request->region_id;
        $emailMarketing->branch_id = $request->lead_branch;
        $emailMarketing->created_by = \Auth::id();

        if ($emailMarketing->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Email Marketing successfully created!',
                'id' => $emailMarketing->id,
            ]);
        }
    }

    public function email_template_type_show(Request $request)
    {
        $emailMarketing = EmailTemplate::find($request->id);
        $users = allUsers();
        $branches = Branch::get()->pluck('name', 'id')->ToArray();
        $regiones = Region::get()->pluck('name', 'id')->ToArray();

        $users_with_roles = \DB::table('roles')->pluck('name', 'id')->toArray();
        $html =  view('EmailTemplate.EmailDetail', compact('regiones','branches','emailMarketing', 'users', 'users_with_roles'))->render();
        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }
    public function email_template_type_update(Request $request)
    {
        $emailMarketing = EmailTemplate::where('id',$request->id)->first();
        $filter = BrandsRegionsBranchesForEdit($emailMarketing->brand_id, $emailMarketing->getRawOriginal()['region_id'], $emailMarketing->branch_id);
        $users = allUsers();
        $companies = $filter['brands'];
        $regions = $filter['regions'];
        $branches = $filter['branches'];
        $employees = $filter['employees'];
        $users_with_roles = \DB::table('roles')->get();
        // dd($filter);
        return view('EmailTemplate.edit', compact('emailMarketing', 'users', 'companies', 'branches', 'regions', 'employees', 'users_with_roles'));
    }
    public function email_template_type_updateSave(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'brand_id' => 'required|numeric|min:1',
            'region_id' => 'required|numeric|min:1',
            'lead_branch' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }


        $emailMarketing = EmailTemplate::find($request->id);
        $emailMarketing->name = $request->name;
        $emailMarketing->brand_id = $request->brand_id;
        $emailMarketing->region_id = $request->region_id;
        $emailMarketing->branch_id = $request->lead_branch;
        $emailMarketing->created_by = \Auth::id();

        if ($emailMarketing->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Email Marketing successfully created!',
                'id' => $request->id,
            ]);
        }
    }
    public function email_template_type_delete(Request $request)
    {
        $emailMarketing = EmailTemplate::find($request->id);
        if ($emailMarketing->delete()) {
            return back()->with('success', __('User successfully deleted .'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }




}
