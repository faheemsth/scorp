<?php

namespace App\Http\Controllers;

use App\Models\ApplicationStage;
use App\Models\Pipeline;
use Illuminate\Http\Request;

class AppStageController extends Controller
{
    public function index(){

        if(\Auth::user()->can('manage application stage'))
        {
            $application_stages = ApplicationStage::select('application_stages.*', 'pipelines.name as pipeline')
            ->join('pipelines', 'pipelines.id', '=', 'application_stages.pipeline_id')
            ->where('pipelines.created_by', '=', \Auth::user()->ownerId())
            ->where('application_stages.created_by', '=', \Auth::user()->ownerId())
            ->orderBy('application_stages.pipeline_id')
            ->orderBy('application_stages.order')
            ->get();
            $pipelines   = [];

            foreach($application_stages as $lead_stage)
            {
                if(!array_key_exists($lead_stage->pipeline_id, $pipelines))
                {
                    $pipelines[$lead_stage->pipeline_id]                = [];
                    $pipelines[$lead_stage->pipeline_id]['name']        = $lead_stage['pipeline'];
                    $pipelines[$lead_stage->pipeline_id]['application_stages'] = [];
                }
                $pipelines[$lead_stage->pipeline_id]['application_stages'][] = $lead_stage;
            }
            return view('application_stages.index')->with('pipelines', $pipelines);
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    public function create()
    {
        if(\Auth::user()->can('create application stage'))
        {
            $pipelines = Pipeline::where('created_by', '=', \Auth::user()->ownerId())->get()->pluck('name', 'id');

            return view('application_stages.create')->with('pipelines', $pipelines);
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }
    public function save (Request $request)
    {
        if(\Auth::user()->can('create application stage'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                                   'pipeline_id' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect('application_stages')->with('error', $messages->first());
            }
            $lead_stage              = new ApplicationStage();
            $lead_stage->name        = $request->name;
            $lead_stage->type         = $request->lead_stage_type;
            $lead_stage->pipeline_id = $request->pipeline_id;
            $lead_stage->created_by  = \Auth::user()->ownerId();
            $lead_stage->save();

            return redirect('application_stages')->with('success', __('Lead Stage successfully created!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit($id)
    {
        $leadStage=ApplicationStage::find($id);
        if(\Auth::user()->can('edit application stage'))
        {
            if($leadStage->created_by == \Auth::user()->ownerId())
            {
                $pipelines = Pipeline::where('created_by', '=', \Auth::user()->ownerId())->get()->pluck('name', 'id');

                return view('application_stages.edit', compact('leadStage', 'pipelines'));
            }
            else
            {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function update(Request $request,$id)
    {
        $leadStage=ApplicationStage::find($id);
        if(\Auth::user()->can('edit application stage'))
        {

            if($leadStage->created_by == \Auth::user()->ownerId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                       'pipeline_id' => 'required',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect('application_stages')->with('error', $messages->first());
                }

                $leadStage->name        = $request->name;
                $leadStage->pipeline_id = $request->pipeline_id;
                $leadStage->type        = $request->lead_stage_type;
                $leadStage->save();

                return redirect('application_stages')->with('success', __('Lead Stage successfully updated!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


}
