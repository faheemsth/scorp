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
        if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team')
        {
            $leads_query->where('status', '1')->orWhere('status','0');
        }else{
            $leads_query->where('status', '1');
        }
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

            $leads_query->where('id',$id)->orderBy('name', 'asc');
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
            if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team')
            {
                $leads_query->where('status', '1')->orWhere('status','0');
            }else{
                $leads_query->where('status', '1');
            }
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

                $leads_query->groupBy('id')->orderBy('name', 'asc');
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
        $email_query = EmailTemplate::query();
        if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team')
        {
            $email_query->where('status', '1')->orWhere('status','0');
        }else{
            $email_query->where('status', '1');
        }
        if (
            \Auth::user()->can('view lead') ||
            \Auth::user()->can('manage lead') ||
            \Auth::user()->type == 'super admin' ||
            \Auth::user()->type == 'Admin Team'
        ) {
            $companies = FiltersBrands();
            $brand_ids = array_keys($companies);

            $userType = \Auth::user()->type;

            if (in_array($userType, ['super admin', 'Admin Team']) || \Auth::user()->can('level 1')) {
                // No additional conditions, leave $email_query as it is
            } elseif ($userType === 'company') {
                $email_query->where('brand_id', \Auth::user()->id);
            } elseif (in_array($userType, ['Project Director', 'Project Manager']) || \Auth::user()->can('level 2')) {
                $email_query->whereIn('brand_id', $brand_ids);
            } elseif (($userType === 'Region Manager' || \Auth::user()->can('level 3')) && !empty(\Auth::user()->region_id)) {
                $email_query->where('region_id', \Auth::user()->region_id);
            } elseif (($userType === 'Branch Manager' || in_array($userType, ['Admissions Officer', 'Admissions Manager', 'Marketing Officer'])) || (\Auth::user()->can('level 4') && !empty(\Auth::user()->branch_id))) {
                $email_query->where('branch_id', \Auth::user()->branch_id);
            } else {
                $email_query->where('user_id', \Auth::user()->id);
            }
        }

        $total_records = $email_query->count();

        $users = allUsers();
        $branches = Branch::get()->pluck('name', 'id')->ToArray();
        $regiones = Region::get()->pluck('name', 'id')->ToArray();

        $users_with_roles = \DB::table('roles')->pluck('name', 'id')->toArray();
        $EmailMarketings = $email_query->orderBy('name', 'asc')->skip($start)->take($num_results_on_page)->get();

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
        $emailMarketing->slug = 'lead_assigned';
        $emailMarketing->brand_id = $request->brand_id;
        $emailMarketing->region_id = $request->region_id;
        $emailMarketing->branch_id = $request->lead_branch;
        $emailMarketing->status = !empty($request->status)? $request->status: '0';
        $emailMarketing->created_by = \Auth::id();

        if ($emailMarketing->save()) {

             $defaultTemplate = [

            'lead_assigned' => [
                'subject' => 'Lead Assigned',
                'lang' => [
                    'ar' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: " open="" sans";"="">﻿</span><span style="font-family: " open="" sans";"="">مرحبا,</span><br style="font-family: sans-serif;"></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"="">تم تعيين عميل محتمل جديد لك.</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"="">اسم العميل المحتمل&nbsp;: {lead_name}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span open="" sans";"="" style="">الرصاص البريد الإلكتروني<span style="font-size: 1rem;">&nbsp;: {lead_email}</span></span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"="">خط أنابيب الرصاص&nbsp;: {lead_pipeline}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"="">مرحلة الرصاص&nbsp;: {lead_stage}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"="">الموضوع الرئيسي: {lead_subject}</span></p><p></p>',
                    'da' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: " open="" sans";"="">Hej,</span><br style="font-family: sans-serif;"></p><p><span style="font-family: " open="" sans";"="">Ny bly er blevet tildelt dig.</span></p><p><span style="font-size: 1rem; font-weight: bolder; font-family: " open="" sans";"="">Lead-e-mail</span><span style="font-size: 1rem; font-family: " open="" sans";"="">&nbsp;</span><span style="font-size: 1rem; font-family: " open="" sans";"="">: {lead_email}</span></p><p><span style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: " open="" sans";"="">Blyrørledning</span><span style="font-family: " open="" sans";"="">&nbsp;</span><span style="font-family: " open="" sans";"="">: {lead_pipeline}</span></span></p><p><span style="font-size: 1rem; font-weight: bolder; font-family: " open="" sans";"="">Lead scenen</span><span style="font-size: 1rem; font-family: " open="" sans";"="">&nbsp;</span><span style="font-size: 1rem; font-family: " open="" sans";"="">: {lead_stage}</span></p><p></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: " open="" sans";"="">Blynavn</span><span style="font-family: " open="" sans";"="">&nbsp;</span><span style="font-family: " open="" sans";"="">: {lead_name}</span></span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span open="" sans";"=""><b>Lead Emne</b>: {lead_subject}</span><span style="font-family: sans-serif;"><span style="font-family: " open="" sans";"=""><br></span><br></span></p><p></p>',
                    'de' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif;">Hallo,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Neuer Lead wurde Ihnen zugewiesen.</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif; font-weight: bolder;" open="" sans";"="">Lead Name</span><span style="font-family: sans-serif;" open="" sans";"="">&nbsp;</span><span style="" open="" sans";"=""><font face="sans-serif">:</font> {lead_name}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif; font-weight: bolder;" open="" sans";"="">Lead-E-Mail</span><span style="font-family: sans-serif;" open="" sans";"="">&nbsp;</span><span style="" open="" sans";"=""><font face="sans-serif">: </font>{lead_email}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif; font-weight: bolder;" open="" sans";"="">Lead Pipeline</span><span style="font-family: sans-serif;" open="" sans";"="">&nbsp;</span><span style="" open="" sans";"=""><font face="sans-serif">:</font> {lead_pipeline}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif; font-weight: bolder;" open="" sans";"="">Lead Stage</span><span style="font-family: sans-serif;" open="" sans";"="">&nbsp;</span><span style="" open="" sans";"=""><font face="sans-serif">: </font>{lead_stage}</span></p><p style="line-height: 28px;"><span style="font-family: " open="" sans";"=""><b>Lead Emne</b>: {lead_subject}</span></p><p></p>',
                    'en' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: " open="" sans";"="">﻿</span><span style="font-family: " open="" sans";"="">Hello,</span><br style="font-family: sans-serif;"><span style="font-family: " open="" sans";"="">New Lead has been Assign to you.</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"=""><b>Lead Name</b></span><span style="" open="" sans";"="">&nbsp;: {lead_name}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span open="" sans";"="" style="font-size: 1rem;"><b>Lead Email</b></span><span open="" sans";"="" style="font-size: 1rem;">&nbsp;: {lead_email}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"=""><b>Lead Pipeline</b></span><span style="" open="" sans";"="">&nbsp;: {lead_pipeline}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"=""><b>Lead Stage</b></span><span style="" open="" sans";"="">&nbsp;: {lead_stage}</span></p><p style="line-height: 28px;"><span style="" open="" sans";"=""><b>Lead Subject</b>: {lead_subject}</span></p><p></p>',
                    'es' => '<p style="line-height: 28px;">Hola,<br style=""></p><p>Se le ha asignado un nuevo plomo.</p><p></p><p style="line-height: 28px;"><b>Nombre principal</b>&nbsp;: {lead_name}</p><p style="line-height: 28px;"><b>Correo electrónico</b> principal&nbsp;: {lead_email}</p><p style="line-height: 28px;"><b>Tubería de plomo</b>&nbsp;: {lead_pipeline}</p><p style="line-height: 28px;"><b>Etapa de plomo</b>&nbsp;: {lead_stage}</p><p style="line-height: 28px;"><span open="" sans";"=""><b>Hauptthema</b>: {lead_subject}</span><br></p><p></p>',
                    'fr' => '<p style="line-height: 28px;">Bonjour,<br style=""></p><p style="">Un nouveau prospect vous a été attribué.</p><p></p><p style="line-height: 28px;"><b>Nom du responsable</b>&nbsp;: {lead_name}</p><p style="line-height: 28px;"><b>Courriel principal</b>&nbsp;: {lead_email}</p><p style="line-height: 28px;"><b>Pipeline de plomb</b>&nbsp;: {lead_pipeline}</p><p style="line-height: 28px;"><b>Étape principale</b>&nbsp;: {lead_stage}</p><p style="line-height: 28px;"><span style="" open="" sans";"=""><b>Sujet principal</b>: {lead_subject}</span></p><p></p>',
                    'it' => '<p style="line-height: 28px;">Ciao,<br style=""></p><p>New Lead è stato assegnato a te.</p><p><b>Lead Email</b>&nbsp;: {lead_email}</p><p><b>Conduttura di piombo&nbsp;: {lead_pipeline}</b></p><p><b>Lead Stage</b>&nbsp;: {lead_stage}</p><p></p><p style="line-height: 28px;"><b>Nome del lead</b>&nbsp;: {lead_name}<br></p><p style="line-height: 28px;"><span style="" open="" sans";"=""><b>Soggetto principale</b>: {lead_subject}</span></p><p></p>',
                    'ja' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: " open="" sans";"="">こんにちは、</span><br style="font-family: sans-serif;"></p><p><span style="font-family: " open="" sans";"="">新しいリードが割り当てられました。</span><br><span style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: " open="" sans";"="">リードメール</span><span style="font-family: " open="" sans";"="">&nbsp;</span><span style="font-family: " open="" sans";"="">: {lead_email}</span></span><br><span style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: " open="" sans";"="">リードパイプライン</span><span style="font-family: " open="" sans";"="">&nbsp;</span><span style="font-family: " open="" sans";"="">: {lead_pipeline}</span></span><br><span style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: " open="" sans";"="">リードステージ</span><span style="font-family: " open="" sans";"="">&nbsp;: {lead_stage}</span></span></p><p></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: " open="" sans";"="">リード名</span><span style="font-family: " open="" sans";"="">&nbsp;</span><span style="font-family: " open="" sans";"="">: {lead_name}</span><br></span></p><p style="line-height: 28px;"><span open="" sans";"="" style=""><span style="font-family: " open="" sans";"="">リードサブジェクト</span><span style="font-size: 1rem; font-family: " open="" sans";"="">: {lead_subject}</span></span></p><p></p>',
                    'nl' => '<p style="line-height: 28px;">Hallo,<br style=""></p><p style="">Nieuwe lead is aan u toegewezen.<br><b>E-mail leiden</b>&nbsp;: {lead_email}<br><b>Lead Pipeline</b>&nbsp;: {lead_pipeline}<br><b>Hoofdfase</b>&nbsp;: {lead_stage}</p><p></p><p style="line-height: 28px;"><b>Lead naam</b>&nbsp;: {lead_name}<br></p><p style="line-height: 28px;"><span style="" open="" sans";"=""><b>Hoofdonderwerp</b>: {lead_subject}</span></p><p></p>',
                    'pl' => '<p style="line-height: 28px;">Witaj,<br style="">Nowy potencjalny klient został do ciebie przypisany.</p><p style="line-height: 28px;"><b>Imię i nazwisko</b>&nbsp;: {lead_name}<br><b>Główny adres e-mail</b>&nbsp;: {lead_email}<br><b>Ołów rurociągu</b>&nbsp;: {lead_pipeline}<br><b>Etap prowadzący</b>&nbsp;: {lead_stage}</p><p style="line-height: 28px;"><span style="" open="" sans";"=""><b>Główny temat</b>: {lead_subject}</span></p><p></p>',
                    'ru' => '<p style="line-height: 28px;">Привет,<br style="">Новый Лид был назначен вам.</p><p style="line-height: 28px;"><b>Имя лидера</b>&nbsp;: {lead_name}<br><b>Ведущий Email</b>&nbsp;: {lead_email}<br><b>Ведущий трубопровод</b>&nbsp;: {lead_pipeline}<br><b>Ведущий этап</b>&nbsp;: {lead_stage}</p><p style="line-height: 28px;"><span style="" open="" sans";"=""><b>Ведущая тема</b>: {lead_subject}</span></p><p></p>',
                    'pt' => '<p style="line-height: 28px;">Olá,<br style="">O novo lead foi atribuído a você.</p><p style="line-height: 28px;"><b>Nome do lead</b>&nbsp;: {lead_name}<br><b>E-mail principal</b>&nbsp;: {lead_email}<br><b>Pipeline principal</b>&nbsp;: {lead_pipeline}<br><b>Estágio principal</b>&nbsp;: {lead_stage}</p><p style="line-height: 28px;"><span style="" open="" sans";"=""><b>Assunto principal</b>: {lead_subject}</span></p><p></p>',
                ],
            ]
        ];



            foreach ($defaultTemplate[$emailMarketing->slug]['lang'] as $lang => $content) {
                EmailTemplateLang::create(
                    [
                        'parent_id' => $emailMarketing->id,
                        'lang' => $lang,
                        'subject' => $request->name,
                        'content' => '',
                    ]
                );
            }
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
        $emailMarketing->status = !empty($request->status)? $request->status: '0';
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
    public function toggleEmailTemplateStatus(Request $request)
    {
        $emailMarketing = EmailTemplate::find($request->id);
        if (empty($emailMarketing)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email Marketing not found!',
            ]);
        }

        $statusMessage = $request->status == 1 ? 'Active' : 'Inactive';
        $emailMarketing->status = $request->status;

        if ($emailMarketing->save()) {
            return response()->json([
                'status' => 'success',
                'message' => "$statusMessage Template Status Successfully",
            ]);
        }
    }

}
