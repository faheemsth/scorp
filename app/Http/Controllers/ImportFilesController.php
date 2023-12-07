<?php

namespace App\Http\Controllers;

use Hash;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\User;
use App\Models\Label;
use App\Models\Stage;
use App\Models\Utility;
use App\Models\DealCall;
use App\Models\DealFile;
use App\Models\LeadCall;
use App\Models\LeadFile;
use App\Models\Pipeline;
use App\Models\UserDeal;
use App\Models\UserLead;
use App\Models\DealEmail;
use App\Models\LeadEmail;
use App\Models\LeadStage;
use App\Models\ClientDeal;
use App\Models\University;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Models\DealDiscussion;
use App\Models\LeadDiscussion;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Http;

class ImportFilesController extends Controller
{
    //
    public function index()
    {
        return view('import_files.index');
    }


    public function showFileData(Request $request)
    {
        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');
        $data = [];
        $skipFirstLine = true; // Add this line
        $warning_message = '';

        $incompleteRow = ''; // Store incomplete row

        while ($line = fgets($handle)) {
            //$line = explode(",", $line);

            $line = rtrim($line); // Remove trailing newline character

            if ($incompleteRow) {
                // Concatenate with incomplete row from the previous iteration
                $line = $incompleteRow . $line;
                $incompleteRow = ''; // Reset incomplete row
            }

            if (substr_count($line, '"') % 2 !== 0) {
                // Incomplete row, contains odd number of double quotes
                $incompleteRow = $line . "\n"; // Store incomplete row
                continue;
            }

             // Remove triple double quotes and extra whitespace
        //    $line = str_replace('"""', '', $line);
         //   $line = trim($line);

            $line = explode(",", $line);


            if ($skipFirstLine) {
                $skipFirstLine = false;
                continue;
            }

            //dd($line);

            // if (!isset($line[0]) || empty($line[0]) || !isset($line[1]) || empty($line[1]) || !isset($line[2]) || empty($line[2]) || !isset($line[3]) || empty($line[3]) || !isset($line[4]) || empty($line[4])) {
            //     $warning_message .= $record_no . ' ';
            //     continue;
            // }

            // if (!isset($line[3]) || empty($line[3])) {
            //     continue;
            // }


            $name = '';
            if (!isset($line[2]) || empty($line[2])) {
                $name = isset($line[3]) ? $line[3] : '';
            } else {
                $name = $line[2];
            }

            $data[] = [
                'name' => $name,
                'subject' => isset($line[0]) ? $line[0] : '',
                'email' => isset($line[3]) ? $line[3] : '',
                'phone' => isset($line[4]) ? $line[4] : '',
                'deal_stage' => isset($line[5]) ? $line[5] : '',
                'assigned_to' => isset($line[1]) ? $line[1] : '',
                'notes' => isset($line[7]) ? $line[7] : '',
                'contact_label' => isset($line[6]) ? $line[6] : '',
            ];
        }

        // return redirect()->back()->with('success', __('Lead successfully created! '));
        return view('import_files.list', ['data' => $data, 'error' => 'Lead number ' . $warning_message . ' missed due to partial data']);
    }

    private function createLead($data)
    {
        $users = $data['users'];
        $line = $data['line'];
        $usr = $data['usr'];
        $pipeline = $data['pipeline'];
        $lead_stages = $data['lead_stages'];
        $ret_message = [];
        $label = str_replace(' Lead', '', $line['contact_label']);

        //check lead exist or not            
        $assigned_to = in_array($line['assigned_to'], $users) ? $users[$line['assigned_to']] : 113;

        $labels = Label::get()->pluck('id', 'name')->toArray();
        $label_id = 1;
        if (array_key_exists($label, $labels)) {
            $label_id = $labels[$label];
        }



        try {
            // Creating new lead
            $lead = new Lead();
            $lead->name        = $line['name'];  // name
            $lead->email       = $line['email']; // email
            $lead->phone       = $line['phone']; // phone
            $lead->subject     = $line['subject']; // subject
            $lead->notes = $line['notes'];
            $lead->user_id     = $assigned_to;
            $lead->pipeline_id = $pipeline->id;
            $lead->stage_id    =  $lead_stages[0]->id;
            $lead->created_by  = $usr->id;
            $lead->labels = $label_id;
            $lead->date        = date('Y-m-d');

            if ($lead->save()) {
                // Save data in user_lead
                $is_saved = UserLead::create([
                    'user_id' => $usr->id,
                    'lead_id' => $lead->id,
                ]);

                if (!$is_saved) { // if user lead not created, then roll back
                    $lead->delete();

                    $ret_message = [
                        'status' => 'error',
                        'msg' => 'Error in creating user lead'
                    ];
                } else {
                    $ret_message = [
                        'status' => 'success',
                        'msg' => 'Created',
                        'lead' => Lead::find($lead->id)
                    ];
                }
            } else {
                $ret_message = [
                    'status' => 'error',
                    'msg' => 'Error in creating lead'
                ];
            }
        } catch (\Exception $e) {
            $ret_message = [
                'status' => 'error',
                'msg' => $e->getMessage()
            ];
        }

        return $ret_message;
    }

    private function createDeal($data)
    {
        $users = $data['users'];
        $line = $data['line'];
        $usr = $data['usr'];
        $pipeline = $data['pipeline'];
        $deal_stages = $data['deal_stages'];
        $lead = $data['lead'];
        $ret_message = [];
        $duplicated_deal = $data['duplicated_deal'];

        try {

            if ($duplicated_deal == 0) {
                $role   = Role::findByName('client');
                $client = User::create(
                    [
                        'name' => $line['name'],
                        'email' => $line['email'],
                        'password' => Hash::make('123456789'),
                        'type' => 'client',
                        'lang' => 'en',
                        'created_by' => $usr->id,
                    ]
                );

                if (!$client) {
                    //rollback lead user leads
                    UserLead::where([
                        'user_id' => $usr->id,
                        'lead_id' => $lead->id,
                    ])->delete();
                    $lead->delete();
                    $ret_message = [
                        'status' => 'error',
                        'msg' => 'Error in Creating User for Deal'
                    ];
                    return $ret_message;
                }
                $client->assignRole($role);
            } else {
                //$client = User::where(['type' => 'client', 'email' => $line['email']])->first();
                $client = $data['client'];
            }




            $deal_stage_id = Stage::orderBy('order')->first()->id; //default
            if (array_key_exists($line['deal_stage'], $deal_stages)) {
                $deal_stage_id = $deal_stages[$line['deal_stage']];
            }


            if ($line['deal_stage'] == 'CAS requested') {
                $deal_stage_id = 6;
            } elseif ($line['deal_stage'] == 'CAS received') {
                $deal_stage_id = 6;
            } elseif ($line['deal_stage'] == 'Closed Lost') {
                $deal_stage_id = 8;
            }



            $stage = Stage::where(['id' => $deal_stage_id, 'pipeline_id' => $lead->pipeline_id])
                ->orderBy('order', 'asc')
                ->first();
            if (empty($stage)) {
                UserLead::where([
                    'user_id' => $usr->id,
                    'lead_id' => $lead->id,
                ])->delete();
                $lead->delete();
                $ret_message = [
                    'status' => 'error',
                    'msg' => 'Create Deal Stages first'
                ];
                return $ret_message;
            }

            ///////created deal
            $deal              = new Deal();
            $deal->name        = $line['subject'];
            $deal->price       = 0;
            $deal->pipeline_id = $lead->pipeline_id;
            $deal->stage_id    = $stage->id;
            $deal->sources     = '';
            $deal->products    = '';
            $deal->notes       = $line['notes'];
            $deal->labels      = $lead->label;
            $deal->status      = 'Active';
            $deal->created_by  = $lead->created_by;

            if ($deal->save()) {

                $is_saved = ClientDeal::create(
                    [
                        'deal_id' => $deal->id,
                        'client_id' => $client->id,
                    ]
                );

                if (!$is_saved) {
                    //rollback client, UserLead and lead, deal
                    $deal->delete();
                    UserLead::where([
                        'user_id' => $usr->id,
                        'lead_id' => $lead->id,
                    ])->delete();
                    $lead->delete();
                    $ret_message = [
                        'status' => 'error',
                        'msg' => 'Error in Creating Client Deal'
                    ];
                    return $ret_message;
                }
            } else {
                //rollback client, UserLead and lead
                UserLead::where([
                    'user_id' => $usr->id,
                    'lead_id' => $lead->id,
                ])->delete();
                $lead->delete();
                $ret_message = [
                    'status' => 'error',
                    'msg' => 'Error in Creating Client Deal'
                ];
                return $ret_message;
            }



            $pipeline = Pipeline::find($lead->pipeline_id);
            $dArr     = [
                'deal_name' => $deal->name,
                'deal_pipeline' => $pipeline->name,
                'deal_stage' => $stage->name,
                'deal_status' => $deal->status,
                'deal_price' => 0,
            ];
            Utility::sendEmailTemplate('Assign Deal', [$client->id => $client->email], $dArr);

            // Make Entry in UserDeal Table
            $leadUsers = UserLead::where('lead_id', '=', $lead->id)->get();
            foreach ($leadUsers as $leadUser) {
                UserDeal::create(
                    [
                        'user_id' => $leadUser->user_id,
                        'deal_id' => $deal->id,
                    ]
                );
            }



            //////////////////Transfer lead discussion
            $discussions = LeadDiscussion::where('lead_id', '=', $lead->id)->where('created_by', '=', $usr->id)->get();
            if (!empty($discussions)) {
                foreach ($discussions as $discussion) {
                    DealDiscussion::create(
                        [
                            'deal_id' => $deal->id,
                            'comment' => $discussion->comment,
                            'created_by' => $discussion->created_by,
                        ]
                    );
                }
            }


            /////////////////////Files
            $files = LeadFile::where('lead_id', '=', $lead->id)->get();
            if (!empty($files)) {
                foreach ($files as $file) {
                    $location     = base_path() . '/storage/lead_files/' . $file->file_path;
                    $new_location = base_path() . '/storage/deal_files/' . $file->file_path;
                    $copied       = copy($location, $new_location);

                    if ($copied) {
                        DealFile::create(
                            [
                                'deal_id' => $deal->id,
                                'file_name' => $file->file_name,
                                'file_path' => $file->file_path,
                            ]
                        );
                    }
                }
            }




            $calls = LeadCall::where('lead_id', '=', $lead->id)->get();
            if (!empty($calls)) {
                foreach ($calls as $call) {
                    DealCall::create(
                        [
                            'deal_id' => $deal->id,
                            'subject' => $call->subject,
                            'call_type' => $call->call_type,
                            'duration' => $call->duration,
                            'user_id' => $call->user_id,
                            'description' => $call->description,
                            'call_result' => $call->call_result,
                        ]
                    );
                }
            }



            $emails = LeadEmail::where('lead_id', '=', $lead->id)->get();
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    DealEmail::create(
                        [
                            'deal_id' => $deal->id,
                            'to' => $email->to,
                            'subject' => $email->subject,
                            'description' => $email->description,
                        ]
                    );
                }
            }




            $lead->is_converted = $deal->id;
            $lead->save();
        } catch (Exception $e) {
            // Handle the exception
            $ret_message = [
                'status' => 'error',
                'msg' => $e->getMessage()
            ];
            return $ret_message;
        }


        return $ret_message = [
            'status' => 'success',
            'msg' => 'Created successfully',
            'lead' => $lead,
            'deal' => $deal
        ];
    }

    public function syncFileData(Request $request)
    {
        $line = request()->post('line');

        if (empty($line['email'])) {
            return json_encode([
                'status' => 'error'
            ]);
        }

        $usr = Auth()->user();

        //fetch current pipeline
        if ($usr->default_pipeline) {
            $pipeline = Pipeline::where('id', '=', $usr->default_pipeline)->first();
            if (!$pipeline) {
                $pipeline = Pipeline::first();
            }
        } elseif (\Auth::user()->type == 'super admin') {
            $pipeline = Pipeline::first();
        } else {
            $pipeline = Pipeline::first();
        }

        //fetch users
        $users = User::where(['type' => 'Role1C1'])->get()->pluck('id', 'name')->toArray();

        //fetching stages
        $lead_stages = LeadStage::orderBy('order')->get();
        $deal_stages = Stage::orderBy('order')->get()->pluck('id', 'name')->toArray();

        $data = [
            'usr' => $usr,
            'line' => $line,
            'lead_stages' => $lead_stages,
            'deal_stages' => $deal_stages,
            'pipeline' => $pipeline,
            'users' => $users
        ];

        //if lead exist
        $is_lead_exist = Lead::where(['email' => $line['email']])->first();
        $is_user_exist = User::where(['type' => 'client', 'email' => $line['email']])->first();
        if ($is_lead_exist && $is_user_exist) {
            $data['lead'] = $is_lead_exist;
            $data['client'] = $is_user_exist;
            $data['duplicated_deal'] = 1;
        } else {
            //creating Lead
            $data['duplicated_deal'] = 0;
            $new_lead = $this->createLead($data);
            if ($new_lead['status'] == 'error') {
                return json_encode([
                    'status' => 'error'
                ]);
            }
            $data['lead'] = $new_lead['lead'];
        }

        //creating Deal
        $new_deal = $this->createDeal($data);

        if ($new_deal['status'] == 'success') {
            return json_encode([
                'status' => 'success'
            ]);
        } else {
            return json_encode([
                'status' => 'error'
            ]);
        }
    }

    ///////////////////import universities
    public function syncUniversities(Request $request)
    {
        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');
        $skipFirstLine = true;
        $line_no = 1;

        while ($line = fgets($handle)) {
            $line = explode(",", $line);

            if ($skipFirstLine) {
                $skipFirstLine = false;
                continue;
            }

            //$university = explode(' ',$line[0]);

            // if(empty($line[2]))
            // continue;

            // $course = str_replace($line[2],'',$line[1]);
            // $position = strpos($course, "-");
            // if ($position !== false && $position == 0) {
            //     $course = substr_replace($course, " ", $position, 1);
            // }

            // echo "<pre>";
            // echo $line_no++;
            // print_r($course);


            //  $pattern = "/MSc(.*?)\-".$university[0]."/";


            //  preg_match($pattern, $line[1], $matches);


            // if (isset($matches[1])) {
            //     $extractedString = trim($matches[1]);
            //     echo $extractedString;
            // } else {
            //     echo "No match found.";
            // }
            $check_university = University::where('name', $line)->first();
            if (!$check_university) {
                echo "<pre>";
                print_r($line[0]);

                $university = new University();
                $university->name = $line[0];
                $university->country = 'UK';
                $university->city = 'Manchester';
                $university->phone = '+441614960216';
                $university->created_by = \Auth()->id();
                $university->save();
            }
        }
        die();
    }
}
