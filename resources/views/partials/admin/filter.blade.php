@php
    use App\Models\Utility;
    // $logo=asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_logo = Utility::getValByName('company_logo_dark');
    $company_logos = Utility::getValByName('company_logo_light');
    $company_small_logo = Utility::getValByName('company_small_logo');
    $setting = \App\Models\Utility::colorset();
    $mode_setting = \App\Models\Utility::mode_layout();
    $emailTemplate = \App\Models\EmailTemplate::first();
    $lang = Auth::user()->lang;

@endphp
<style>
     #myDIV{
        background-color: #CCC;
        z-index: 1021;
    }
</style>

<div id="wrapper" id="savefilter">

    <div class="sidebar" id="myDIV" style="display: none;" >
        //There Showing Save Filter Items
    </div>

</div>
